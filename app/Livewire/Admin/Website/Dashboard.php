<?php

namespace App\Livewire\Admin\Website;

use App\Models\CmsPage;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        $pages = CmsPage::with('versions')->get();
        
        $publishedCount = $pages->where('is_published', true)->count();
        $draftCount = $pages->filter(function($page) {
            return $page->draftVersion()->exists();
        })->count();

        return view('livewire.admin.website.dashboard', [
            'pages' => $pages,
            'publishedCount' => $publishedCount,
            'draftCount' => $draftCount,
        ])->title('Website CMS - Vanniyan Admin');
    }
}
