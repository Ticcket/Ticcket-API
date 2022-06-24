<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    use HasFactory;

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

    public function getRatingAttribute() {
        return ((array) DB::select("CALL get_event_rating(?)", [$this->id])[0])['rating'];
    }

    public function ticket() {
        return $this->hasMany(Ticket::class);
    }

    public function organizers() {
        return $this->belongsToMany(User::class, 'organizers');
    }

    public function announcements() {
        return $this->belongsToMany(User::class, 'announcements')->withTimestamps()->withPivot('message');
    }

    public function feedback() {
        return $this->hasMany(Feedback::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator');
    }
}
