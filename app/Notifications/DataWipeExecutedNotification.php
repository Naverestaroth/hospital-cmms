<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DataWipeExecutedNotification extends Notification
{
    use Queueable;

    public array $cleared;

    public function __construct(array $cleared = [])
    {
        $this->cleared = $cleared;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $categories = !empty($this->cleared) ? implode(', ', $this->cleared) : 'Data Input';

        return [
            'title'       => 'Pembersihan Data Input (Data Wipe)',
            'message'     => "Data input ({$categories}) berhasil dibersihkan dari sistem.",
            'action_url'  => route('settings') . '?tab=admin_tools',
            'icon'        => 'server-cog',
            'category'    => 'data_wipe',
        ];
    }
}
