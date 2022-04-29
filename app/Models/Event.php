<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    public $dates = ['deleted_at'];

    public $fillable = [
        'title',
        'description',
        'logo',
        'creator',
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

    public function creator() {
        return $this->hasOne(User::class, 'creator');
    }
}
