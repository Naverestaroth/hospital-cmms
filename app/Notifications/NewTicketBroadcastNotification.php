<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTicketBroadcastNotification extends Notification
{
    use Queueable;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $assetName = $this->ticket->asset ? $this->ticket->asset->asset_name : 'Peralatan';

        return [
            'ticket_id'   => $this->ticket->id,
            'ticket_code' => $this->ticket->ticket_code,
            'title'       => 'Tiket Baru (On Duty)',
            'message'     => "Tiket baru {$this->ticket->ticket_code} ({$assetName}) dilaporkan di {$this->ticket->room}.",
            'action_url'  => route('tickets.show', $this->ticket->id),
            'icon'        => 'wrench',
            'category'    => 'new_ticket_broadcast',
        ];
    }
}
