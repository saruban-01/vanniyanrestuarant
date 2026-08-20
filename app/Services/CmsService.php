<?php

namespace App\Services;

use App\Models\CmsPage;
use App\Models\CmsPageVersion;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CmsService
{
    /**
     * Get the latest published content for a page slug.
     */
    public function getPublishedContent(string $slug)
    {
        return Cache::rememberForever("cms_page_{$slug}_published", function () use ($slug) {
            $page = CmsPage::where('slug', $slug)->first();
            if (!$page) return null;

            $version = $page->publishedVersion()->first();
            if (!$version) return null;

            return [
                'content' => $version->content,
                'seo_meta' => $version->seo_meta,
            ];
        });
    }

    /**
     * Get the current draft content, or fall back to published if no draft exists.
     */
    public function getDraftOrPublishedContent(string $slug)
    {
        $page = CmsPage::where('slug', $slug)->first();
        if (!$page) return null;

        return $page->draftVersion()->first() ?? $page->publishedVersion()->first();
    }

    /**
     * Save a new draft for a page.
     */
    public function saveDraft(string $slug, array $content, array $seoMeta = [])
    {
        return DB::transaction(function () use ($slug, $content, $seoMeta) {
            $page = CmsPage::firstOrCreate(
                ['slug' => $slug],
                ['title' => ucfirst($slug)]
            );

            // Get or create draft version
            $draft = $page->draftVersion()->first();
            $nextVersionNumber = $page->versions()->max('version_number') + 1;

            if ($draft) {
                $draft->update([
                    'content' => $content,
                    'seo_meta' => $seoMeta,
                    'created_by_admin_id' => auth('admin')->id(),
                ]);
            } else {
                $draft = $page->versions()->create([
                    'version_number' => $nextVersionNumber,
                    'status' => 'DRAFT',
                    'content' => $content,
                    'seo_meta' => $seoMeta,
                    'created_by_admin_id' => auth('admin')->id(),
                ]);
            }

            return $draft;
        });
    }

    /**
     * Publish the current draft for a page.
     */
    public function publishDraft(string $slug)
    {
        return DB::transaction(function () use ($slug) {
            $page = CmsPage::where('slug', $slug)->firstOrFail();
            $draft = $page->draftVersion()->first();

            if (!$draft) {
                throw new \Exception('No draft exists to publish.');
            }

            // Archive the current published version if it exists
            $published = $page->publishedVersion()->first();
            if ($published) {
                $published->update(['status' => 'ARCHIVED']);
            }

            // Mark draft as published
            $draft->update(['status' => 'PUBLISHED']);
            $page->update(['is_published' => true]);

            // Clear cache
            Cache::forget("cms_page_{$slug}_published");

            // Audit
            \App\Models\AuditLog::create([
                'action' => 'PUBLISH',
                'module' => 'CMS_PAGE',
                'record_type' => 'CmsPage',
                'record_id' => $page->id,
                'description' => "Published new version {$draft->version_number} of {$slug} page.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $draft;
        });
    }

    /**
     * Restore an archived version.
     */
    public function restoreVersion(string $slug, int $versionId)
    {
        return DB::transaction(function () use ($slug, $versionId) {
            $page = CmsPage::where('slug', $slug)->firstOrFail();
            $historicalVersion = CmsPageVersion::findOrFail($versionId);

            if ($historicalVersion->cms_page_id !== $page->id) {
                throw new \Exception('Version does not belong to this page.');
            }

            // Save the historical version content as a new draft
            return $this->saveDraft($slug, $historicalVersion->content, $historicalVersion->seo_meta);
        });
    }
}
