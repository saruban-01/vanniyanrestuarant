<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.notifications.index', [
            'notifications' => \App\Models\AdminNotification::latest()->paginate(20)
        ])->title('Notifications - Vanniyan Admin');
    }
}
