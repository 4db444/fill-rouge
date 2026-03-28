<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseShares extends Model
{
    protected $fillable = [
        "expense_id",
        "group_id",
        "user_id",
        "amount"
    ];

    public function group () : BelongsTo {
        return $this->belongsTo(Group::class);
    }

    public function user () : BelongsTo {
        return $this->belongsTo(user::class);
    }

    public function expense () : BelongsTo {
        return $this->belongsTo(Expense::class);
    }
}
