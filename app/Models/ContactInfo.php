<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactInfo extends Model
{
    protected $fillable = [
        "email", "phone"
    ];

    public function user () : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
