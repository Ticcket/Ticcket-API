<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    public $table = 'feedbacks';

    public $fillable = [
        'rating',
        'comment',
        'user_id',
        'event_id',
    ];

    public $hiddin = [
        'updated_at',
        'created_at',
    ];

}
