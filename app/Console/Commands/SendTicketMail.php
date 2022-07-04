<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SharedTraits\EmailTrait;
use App\Mail\Itds6TicketEmail;
use App\Models\AnonymousTicket as AnTicket;
use App\Models\Event;

class SendTicketMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:Anonymous {event_id} {--template=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Tickets To All Anonymous Users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $event_id = $this->argument('event_id');

        $template = $this->option("template");

        if(!empty($template)){
            if(!class_exists($template . '::class')) { // There Is A Bug Here
                $this->error("There Isn't Email with this template");
                return 0;
            }
        }else {
            $template = Itds6TicketEmail::class;
        }

        $not_exists = empty(Event::find($event_id));
        if($not_exists) {
            $this->error("Can't Find An Event With That id");
            return 0;
        }

        $tickets = AnTicket::where('event_id', $event_id)->get();
        $bar = $this->output->createProgressBar($tickets->count());

        $bar->start();
        foreach($tickets as $t) {
            $t->update([
                'sent' => 1
            ]);
            $t->url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={$t->token}&choe=UTF-8";
            if($t->sent == 0)
                EmailTrait::sendTicket($t, $template);

            $bar->advance();
        }
        $bar->finish();

        $this->info("\nEmails Was Sent Successfully");

        return 0;
    }
}
