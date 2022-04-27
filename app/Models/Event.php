<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'description',
        'logo',
        'start_at',
        'end_at',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
    ];


    public function ticket() {
        return $this->hasMany(Ticket::class);
    }

    public function organizer() {
        return $this->belongsToMany(Organizer::class);
    }

    public function announcement() {
        return $this->belongsToMany(Announcement::class);
    }

    public function feedback() {
        return $this->hasMany(Feedback::class);
    }
}
