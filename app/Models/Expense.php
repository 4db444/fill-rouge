<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    protected $fillable = [
        "title",
        "description",
        "amount",
        "user_id",
        "group_id"
    ];

    public function user () : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function group () : BelongsTo {
        return $this->belongsTo(Group::class);
    }

    public function expense_shares () : HasMany {
        return $this->hasMany(ExpenseShares::class);
    }
}
