<?php
namespace App\Http\Controllers\SharedTraits;

use App\Mail\{
    WelcomeEmail,
    TicketEmail,
    AnonymousTicketEmail
};
use Illuminate\Support\Facades\Mail;

trait EmailTrait {

    public static function sendEmail() {
        $to_name = "Kareem";
        $to_email = "";
        $data = [
            "name"=>"Ogbonna Vitalis(sender_name)",
            "body" => "A test mail"
        ];

        // Working Line
        Mail::to($to_email)->send(new WelcomeEmail());
    }

    public static function sendTicket($t, $template = TicketEmail::class, $when = "") {
        if ($when === "")
            $when = now();
        $to_email = $t->user->email ?? $t->email;
        // Working Line
        Mail::to($to_email)->later($when, new $template($t));
    }

}
