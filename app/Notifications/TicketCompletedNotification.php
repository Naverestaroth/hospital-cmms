<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketCompletedNotification extends Notification
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
        return [
            'ticket_id'   => $this->ticket->id,
            'ticket_code' => $this->ticket->ticket_code,
            'title'       => 'Pekerjaan Selesai - Menunggu Verifikasi',
            'message'     => "Teknisi telah menyelesaikan perbaikan tiket {$this->ticket->ticket_code}. Silakan verifikasi.",
            'action_url'  => route('tickets.show', $this->ticket->id),
            'icon'        => 'check-circle',
            'category'    => 'verification_required',
        ];
    }
}
