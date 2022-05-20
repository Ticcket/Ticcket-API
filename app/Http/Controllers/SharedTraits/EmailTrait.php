<?php
namespace App\Http\Controllers\SharedTraits;

use App\Mail\{
    WelcomeEmail,
    TicketEmail
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

    public static function sendTicket($t) {
        $to_email = $t->user->email;
        // Working Line
        Mail::to($to_email)->send(new TicketEmail($t));
    }

}
