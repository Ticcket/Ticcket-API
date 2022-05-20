<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $t)
    {
        $this->ticket = $t;
        $this->url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={$t->token}&choe=UTF-8";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view("mail.ticket-email")->with("info", [
            "ticket_id"   => $this->ticket->token,
            "ticket"            => $this->url,
            "attendee_name"     => $this->ticket->user->name,
            "attendee_email"    => $this->ticket->user->email,
            "event"             => $this->ticket->event->title,
            "date"              => $this->ticket->event->start_at,
        ])->subject('Successfully Registered To '. ucfirst($this->ticket->event->title));
    }
}
