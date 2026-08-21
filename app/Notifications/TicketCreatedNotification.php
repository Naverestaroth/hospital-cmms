<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketCreatedNotification extends Notification
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
        $assetName = $this->ticket->asset?->asset_name ?? 'Peralatan';

        return [
            'ticket_id'   => $this->ticket->id,
            'ticket_code' => $this->ticket->ticket_code,
            'title'       => 'Tiket Baru Membutuhkan Respon',
            'message'     => "Tiket {$this->ticket->ticket_code} ({$assetName}) dilaporkan di {$this->ticket->room}.",
            'action_url'  => route('tickets.show', $this->ticket->id),
            'icon'        => 'ticket',
            'category'    => 'new_ticket',
        ];
    }
}
