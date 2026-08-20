<?php

namespace App\Services;

use App\Models\RestaurantSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Central service for the public legal documents (Privacy Policy and
 * Terms & Conditions) plus the HTML sitemap helpers.
 *
 * - Content is stored through the central RestaurantSetting system.
 * - All HTML is sanitized on save AND on read (defense in depth) so the
 *   public pages can never render scripts, iframes or event handlers.
 * - Draft/published workflow: only published content is public.
 * - updated_by / published_at are recorded for every publish.
 */
class LegalService
{
    public const DOC_PRIVACY = 'privacy';

    public const DOC_TERMS = 'terms';

    /**
     * All setting keys owned by the Legal panel.
     */
    public const SETTING_KEYS = [
        'legal_privacy_published',
        'legal_privacy_draft',
        'legal_privacy_published_at',
        'legal_privacy_updated_by',
        'legal_terms_published',
        'legal_terms_draft',
        'legal_terms_published_at',
        'legal_terms_updated_by',
        'legal_governing_law',
    ];

    /**
     * Whitelisted tags. Everything else (script, iframe, object, embed,
     * style, form, svg, …) is removed by the sanitizer.
     */
    private const ALLOWED_TAGS = [
        'h2', 'h3', 'h4', 'p', 'ul', 'ol', 'li',
        'strong', 'em', 'b', 'i', 'u', 'small', 'br',
        'a', 'blockquote', 'span',
    ];

    public function __construct(private RestaurantSettingsService $settings)
    {
    }

    public function get(string $key, $default = null)
    {
        return $this->settings->get($key, $default);
    }

    public function governingLaw(): string
    {
        $law = trim((string) $this->get('legal_governing_law', ''));

        return $law !== '' ? $law : 'Sri Lanka';
    }

    // ------------------------------------------------------------------
    // Published content (public-facing)
    // ------------------------------------------------------------------

    public function published(string $doc): string
    {
        if (! in_array($doc, [self::DOC_PRIVACY, self::DOC_TERMS], true)) {
            return '';
        }

        return $this->sanitize((string) $this->get('legal_'.$doc.'_published', ''));
    }

    /**
     * Section headings (h2) extracted from the published document, used to
     * build the "On this page" contents list. Headings carry stable,
     * slug-based ids assigned by the sanitizer.
     *
     * @return array<int, array{id: string, text: string}>
     */
    public function headings(string $doc): array
    {
        $html = $this->published($doc);
        $headings = [];

        $dom = $this->dom($html);
        if (! $dom) {
            return [];
        }

        foreach ($dom->getElementsByTagName('h2') as $node) {
            if ($node instanceof \DOMElement) {
                $headings[] = [
                    'id' => $node->getAttribute('id') ?: Str::slug($node->textContent),
                    'text' => trim($node->textContent),
                ];
            }
        }

        return $headings;
    }

    public function publishedAt(string $doc): ?string
    {
        return $this->get('legal_'.$doc.'_published_at');
    }

    public function updatedBy(string $doc): ?string
    {
        $adminId = (int) $this->get('legal_'.$doc.'_updated_by', 0);
        if ($adminId <= 0) {
            return null;
        }

        return \App\Models\AdminUser::where('id', $adminId)->value('name');
    }

    public function draft(string $doc): string
    {
        return (string) $this->get('legal_'.$doc.'_draft', '');
    }

    // ------------------------------------------------------------------
    // Admin operations
    // ------------------------------------------------------------------

    /**
     * Save the draft document. Always sanitized; never publicly visible.
     */
    public function saveDraft(string $doc, string $content): void
    {
        RestaurantSetting::updateOrCreate(
            ['key' => 'legal_'.$doc.'_draft'],
            ['value' => $this->sanitize($content)],
        );

        $this->settings->clearCache();
    }

    /**
     * Publish the current draft (or the provided content) as the live
     * document. Records the publishing admin and the effective date.
     */
    public function publish(string $doc, string $content, string $publishedAt, ?int $adminId = null): void
    {
        $content = $this->sanitize($content);

        RestaurantSetting::updateOrCreate(['key' => 'legal_'.$doc.'_published'], ['value' => $content]);
        RestaurantSetting::updateOrCreate(['key' => 'legal_'.$doc.'_draft'], ['value' => $content]);
        RestaurantSetting::updateOrCreate(['key' => 'legal_'.$doc.'_published_at'], ['value' => $publishedAt]);
        RestaurantSetting::updateOrCreate(['key' => 'legal_'.$doc.'_updated_by'], ['value' => (string) ($adminId ?: 0)]);

        $this->settings->clearCache();
        $this->clearSitemapCache();
    }

    public function saveGoverningLaw(string $law): void
    {
        RestaurantSetting::updateOrCreate(['key' => 'legal_governing_law'], ['value' => trim($law)]);
        $this->settings->clearCache();
    }

    public function clearSitemapCache(): void
    {
        Cache::forget('seo_sitemap_xml');
    }

    // ------------------------------------------------------------------
    // Sanitization (whitelist based, never allows scripts)
    // ------------------------------------------------------------------

    /**
     * Sanitize admin-provided HTML. Uses a DOMDocument whitelist pass for
     * correct structure, then a regex guard as a second layer so hostile
     * fragments can never survive. Scripts, iframes, objects, embeds,
     * style blocks, event handlers and javascript: URLs are always removed.
     */
    public function sanitize(string $html): string
    {
        $html = $this->stripProhibitedFragments($html);

        $dom = $this->dom($html);
        if (! $dom) {
            return '';
        }

        $this->walk($dom);

        $body = $dom->getElementsByTagName('body')->item(0);
        if (! $body) {
            return '';
        }

        $inner = '';
        foreach ($body->childNodes as $child) {
            $inner .= $dom->saveHTML($child);
        }

        $inner = preg_replace('/\s+<\/(h2|h3|h4|p|li|strong|em|b|i|u|small|a|blockquote|span)>/i', '</$1>', $inner) ?? $inner;

        return $this->stripProhibitedFragments($inner);
    }

    private function dom(string $html): ?\DOMDocument
    {
        if (trim($html) === '') {
            return null;
        }

        $prev = libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $loaded = $dom->loadHTML(
            '<?xml encoding="UTF-8"><!DOCTYPE html><html><body>'.$html.'</body></html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        return $loaded ? $dom : null;
    }

    private function walk(\DOMDocument $dom): void
    {
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//body//*');

        $h2Index = 0;

        foreach ($nodes as $node) {
            if (! $node instanceof \DOMElement) {
                continue;
            }

            $tag = strtolower($node->tagName);

            // Headings get stable ids used by the contents list.
            if ($tag === 'h2' || $tag === 'h3') {
                $h2Index++;
                $node->setAttribute('id', $this->headingId($node->textContent, $tag, $h2Index));
            }

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                $node->parentNode?->removeChild($node);
                continue;
            }

            // Strip every attribute except a safe allow-list.
            $attributes = [];
            foreach ($node->attributes as $attribute) {
                $name = strtolower($attribute->nodeName);

                if ($name === 'href' && $this->isSafeUrl($attribute->nodeValue)) {
                    $attributes['href'] = $attribute->nodeValue;
                } elseif ($name === 'target' && $attribute->nodeValue === '_blank') {
                    $attributes['target'] = '_blank';
                } elseif ($name === 'rel' && $attribute->nodeValue === 'noopener') {
                    $attributes['rel'] = 'noopener';
                } elseif ($name === 'id' && ($tag === 'h2' || $tag === 'h3')) {
                    $attributes['id'] = $node->getAttribute('id');
                }
            }

            foreach ($node->attributes as $attribute) {
                $node->removeAttribute($attribute->nodeName);
            }

            foreach ($attributes as $name => $value) {
                $node->setAttribute($name, $value);
            }
        }
    }

    private function headingId(string $text, string $tag, int $index): string
    {
        $slug = Str::slug(Str::limit($text, 60, '')) ?: $tag;

        return $slug.'-'.$index;
    }

    private function isSafeUrl(string $url): bool
    {
        $url = trim($url);

        if ($url === '') {
            return false;
        }

        if (preg_match('/^(https?:|mailto:)/i', $url) !== 1) {
            return false;
        }

        return preg_match('/^[\x21-\x7E]+$/i', $url) === 1; // printable ASCII only
    }

    /**
     * Regex guard — catches anything a DOM parser would have mangled or
     * that slipped through (e.g. mixed-case, encoded, or orphaned tags).
     */
    private function stripProhibitedFragments(string $html): string
    {
        $html = preg_replace('/<\s*(script|iframe|object|embed|style|form|svg|math|link|meta|base|template)\b[^>]*>.*?<\s*\/\s*\1\s*>/is', '', $html) ?? $html;
        $html = preg_replace('/<\s*(script|iframe|object|embed|style|form|svg|math|link|meta|base|template)\b[^>]*\/?\s*>/i', '', $html) ?? $html;
        $html = preg_replace('/<\s*\/\s*(script|iframe|object|embed|style|form|svg|math|link|meta|base|template)\s*>/i', '', $html) ?? $html;
        $html = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? $html;
        $html = preg_replace('/\s*style\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? $html;
        $html = preg_replace_callback('/href\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', function ($m) {
            $url = trim($m[1], "\"'");
            if (preg_match('/^\s*(javascript|vbscript|data|file):/i', $url) === 1) {
                return '';
            }

            return $m[0];
        }, $html) ?? $html;
        $html = preg_replace('/<\?php|<\?xml|<%|<\/?[A-Za-z][^>]*\sonerror[^>]*>/i', '', $html) ?? $html;

        return $html;
    }
}