<?php

namespace App\Http\Controllers\Ticket\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\SharedTraits\EmailTrait;
use App\Mail\AnonymousTicketEmail;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\AnonymousTicket as AnTicket;

class TicketsController extends Controller
{
    public function show(Event $event) {

        return view('event.registration')->with('event', $event);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'event_id' => 'required|numeric|exists:events,id',
            'email' => 'required|email',
            'name' => 'required|string|max:30',
        ]);

        $validated['token'] = Str::random(10);

        $ticket = AnTicket::create($validated);

        $ticket->url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={$ticket->token}&choe=UTF-8";

        EmailTrait::sendTicket($ticket, AnonymousTicketEmail::class);

        return view('event.ticket')->with("ticket", $ticket);
    }
}
