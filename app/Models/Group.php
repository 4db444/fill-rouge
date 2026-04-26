<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model
{
    protected $fillable = [
        "user_id",
        "name"
    ];

    public function admin () : BelongsTo {
        return $this->belongsTo(User::class, "user_id");
    }

    public function members () : BelongsToMany {
        return $this->belongsToMany(
            User::class,
            "group_members",
            "group_id",
            "user_id"
        );
    }

    public function expenses () : HasMany {
        return $this->hasMany(Expense::class);
    }

    public function settlements () : HasMany {
        return $this->hasMany(Settlement::class);
    }

    public function expense_shares () : HasMany {
        return $this->hasMany(ExpenseShares::class);
    }

    public function post () : HasOne {
        return $this->hasOne(Post::class, "group_id", "id");
    }
}
