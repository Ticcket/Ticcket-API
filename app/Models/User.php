<?php

namespace App\Models;

use Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_group',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ticket() {
        return $this->hasMany(Ticket::class);
    }

    public function organizers() {
        return $this->belongsToMany(Event::class, 'organizers');
    }

    public function feedback() {
        return $this->hasMany(Feedback::class);
    }

    public function event() {
        return $this->hasMany(Event::class, 'creator');
    }

    public function announcements() {
        return $this->belongsToMany(Event::class, 'announcements')->withTimestamps()->withPivot('message');
    }

    public static function getUserByToken($token) {
        [$id, $user_token] = explode('|', $token, 2);

        $token_data = PersonalAccessToken::where('token', hash('sha256', $user_token))->first();

        return $token_data->tokenable;
    }
}
