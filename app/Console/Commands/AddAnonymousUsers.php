<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\AnonymousTicket as AnTicket;
use App\Models\Event;

class AddAnonymousUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:anonymous {event_id} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "
        Add Anonymous Tickets From An Excel Sheet (CSV Data)
        Must Have Columns (name, email) with the same order.
        Takes:
            - event_id: Which event you want to add users to.
            - {--path}: CSV File Path.
    ";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $event_id = $this->argument('event_id');
        $path = $this->option('path');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if(empty($path) && file_exists($path)){
            $this->error("Please Specify A Valid Path");
            return 0;
        }
        if($ext != 'csv') {
            $this->error("Wrong Format Looking For CSV");
            return 0;
        }

        $not_exists = empty(Event::find($event_id));
        if($not_exists) {
            $this->error("Can't Find An Event With That `id`");
            return 0;
        }

        if (($open = fopen($path, "r")) !== FALSE)
        {
            $i = 0;
            while (($data = fgetcsv($open, 1000, ",")) !== FALSE)
            {
                $array[$i]['name'] = $data[0];
                $array[$i]['email'] = $data[1];
                $i++;
            }

            fclose($open);
        }

        $bar = $this->output->createProgressBar(count($array));

        DB::beginTransaction();
        try {
            $bar->start();
            foreach($array as $user) {
                if($user['name'] == 'name')
                    continue;
                AnTicket::create([
                    "name" => (string) $user['name'],
                    "email" => (string) $user['email'],
                    "token" => Str::random(10),
                    "event_id" => $event_id,
                ]);

                $bar->advance();
            }
            DB::commit();

            $bar->finish();
        }catch(\Exception){
            DB::rollBack();
            $this->error("\nSomething Went Wrong When Adding To The Database");
        }

        $this->info("\nTickets Where Created :)");

        return 0;
    }
}
