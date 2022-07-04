<?php

namespace App\Mail;

use App\Models\AnonymousTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Itds6TicketEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($t)
    {
        $this->url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={$t->token}&choe=UTF-8";
        $this->ticket = $t;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view("mail.itds6-email")->with("info", [
            "ticket_id"   => $this->ticket->token,
            "ticket"            => $this->url,
            "attendee_name"     => $this->ticket->name,
            "attendee_email"    => $this->ticket->email,
            "event"             => $this->ticket->event->title,
            "date"              => $this->ticket->event->start_at,
        ])->subject("Your Ticket for ITDS-6 🎉");
    }
}
