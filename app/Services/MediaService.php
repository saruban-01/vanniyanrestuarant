<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    /**
     * Upload a new media file.
     */
    public function upload(UploadedFile $file, ?string $altText = null, ?string $caption = null)
    {
        // Basic validation in caller, but ensure here too
        $path = $file->store('media', 'public');

        $media = Media::create([
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'alt_text' => $altText,
            'caption' => $caption,
            // Width and height could be extracted using image processing (e.g. Intervention Image) later
        ]);

        \App\Models\AuditLog::create([
            'action' => 'UPLOAD',
            'module' => 'MEDIA',
            'record_type' => 'Media',
            'record_id' => $media->id,
            'description' => "Uploaded media file {$media->filename}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return $media;
    }

    /**
     * Delete a media file.
     */
    public function delete(Media $media)
    {
        // Warn: Should check if used by CmsPage, Event, etc before deleting
        // Storage::disk('public')->delete($media->path);
        // For now, we will prefer archiving or safe deletion logic in Livewire
        $media->delete();
    }
}
