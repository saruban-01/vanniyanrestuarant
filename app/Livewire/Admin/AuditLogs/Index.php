<?php

namespace App\Livewire\Admin\AuditLogs;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.audit-logs.index', [
            'logs' => \App\Models\AuditLog::latest()->paginate(20)
        ])->title('Audit Logs - Vanniyan Admin');
    }
}
