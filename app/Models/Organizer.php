<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;

    public $fillable = ['user_id', 'event_id'];

    public function announcement() {
        return $this->belongsToMany(Announcement::class);
    }
}
