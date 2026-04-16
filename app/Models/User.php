<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "password",
        "role",
        "is_banned",
        "bio"
    ];

    protected $hidden = [
        "password"
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function groups () : BelongsToMany {
        return $this->belongsToMany(
            Group::class,
            "group_members",
            "user_id",
            "group_id"
        );
    }

    public function sent_settlements () : HasMany {
        return $this->hasMany(Settlement::class, "sender_id");
    }

    public function received_settlements () : HasMany {
        return $this->hasMany(Settlement::class, "receiver_id");
    }

    public function posts () : HasMany {
        return $this->hasMany(Post::class, "user_id");
    }

    public function liked_posts () : BelongsToMany {
        return $this->belongsToMany(
            Post::class,
            "likes",
            "user_id",
            "post_id"
        );
    }

    public function profile () : MorphOne {
        return $this->morphOne(Image::class, "imageable");
    }
}
