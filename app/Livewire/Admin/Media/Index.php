<?php

namespace App\Livewire\Admin\Media;

use App\Models\Media;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Services\MediaService;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithFileUploads, WithPagination;

    public $upload;
    public $alt_text;
    public $search = '';

    public function updatedUpload()
    {
        $this->validate([
            'upload' => 'required|mimes:jpeg,png,webp,gif|max:5120', // 5MB Max, raster only (no SVG)
        ]);
    }

    public function saveMedia(MediaService $service)
    {
        $this->validate([
            'upload' => 'required|mimes:jpeg,png,webp,gif|max:5120',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $service->upload($this->upload, $this->alt_text);

        $this->reset(['upload', 'alt_text']);
        session()->flash('message', 'Media successfully uploaded.');
    }

    public function render()
    {
        $media = Media::where('filename', 'like', "%{$this->search}%")
                    ->orWhere('alt_text', 'like', "%{$this->search}%")
                    ->latest()
                    ->paginate(24);

        return view('livewire.admin.media.index', [
            'media' => $media
        ])->title('Media Library - Vanniyan Admin');
    }
}
