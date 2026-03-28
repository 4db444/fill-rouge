<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        "user_id",
        "name"
    ];

    public function admin () : BelongsTo {
        return $this->belongsTo(User::class);
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
}
