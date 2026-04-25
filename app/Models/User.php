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
        "bio",
        "city",
        "country"
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

    public function requests () : BelongsToMany {
        return $this->belongsToMany(
            Post::class,
            "requests",
            "user_id",
            "post_id"
        )->withPivot('status')->withTimestamps();
    }

    public function expenses () : HasMany {
        return $this->hasMany(Expense::class, "user_id");
    }

    public function expense_shares () : HasMany {
        return $this->hasMany(ExpenseShares::class, "user_id");
    }

    public function reports_sent () : HasMany {
        return $this->hasMany(Report::class, "reporter_id");
    }

    public function reports_received () : HasMany {
        return $this->hasMany(Report::class, "reported_id");
    }

    public function receivedRequests () {
        return Post::where('user_id', $this->id)
            ->whereHas('requests', function ($query) {
                $query->where('status', 'pending');
            })
            ->with(['requests' => function ($query) {
                $query->where('status', 'pending')->withPivot('status');
            }, 'requests.profile'])
            ->get();
    }
}
