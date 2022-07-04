<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnonymousTicket extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'email',
        'token',
        'event_id',
        "scanned",
        'sent',
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }

}
