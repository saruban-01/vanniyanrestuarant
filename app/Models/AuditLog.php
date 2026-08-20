<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->entity_type)) {
                $model->entity_type = $model->record_type ?? 'SYSTEM';
            }
        });
    }

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'before_data' => 'array',
        'after_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($userOrAction, $actionOrDesc = null, $description = null, array $context = []): self
    {
        $userId = null;
        $action = null;
        $desc = null;
        $module = 'GENERAL';
        $recordType = null;
        $recordId = null;

        if ($userOrAction instanceof \Illuminate\Contracts\Auth\Authenticatable || is_object($userOrAction)) {
            $userId = $userOrAction->id ?? null;
            $action = (string) $actionOrDesc;
            $desc = (string) $description;
        } else {
            $action = (string) $userOrAction;
            $desc = (string) $actionOrDesc;
            if (is_array($description)) {
                $context = $description;
            }
        }

        if (isset($context['module'])) {
            $module = $context['module'];
        } elseif ($action) {
            $parts = explode('_', $action);
            $module = strtoupper($parts[0] ?? 'GENERAL');
        }

        if (isset($context['order_id'])) {
            $recordType = 'takeaway_order';
            $recordId = $context['order_id'];
        } elseif (isset($context['reservation_id'])) {
            $recordType = 'reservation';
            $recordId = $context['reservation_id'];
        } elseif (isset($context['event_booking_id'])) {
            $recordType = 'event_booking';
            $recordId = $context['event_booking_id'];
        } elseif (isset($context['table_id'])) {
            $recordType = 'restaurant_table';
            $recordId = $context['table_id'];
        } elseif (isset($context['contact_id'])) {
            $recordType = 'contact_message';
            $recordId = $context['contact_id'];
        }

        return self::create([
            'user_id' => $userId,
            'action' => $action ?? 'ACTION',
            'module' => $module,
            'description' => $desc,
            'record_type' => $recordType,
            'record_id' => $recordId,
            'after_data' => $context,
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'System',
        ]);
    }
}
