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
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function ticket() {
        return $this->hasMany(Ticket::class);
    }

    public function organizers() {
        return $this->belongsToMany(User::class, 'organizers');
    }

    public function announcement() {
        return $this->belongsToMany(User::class, 'announcements');
    }

    public function feedback() {
        return $this->hasMany(Feedback::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator');
    }
}
