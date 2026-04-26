<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "title",
        "content",
        "address",
        "user_id"
    ];

    protected $casts = [
        "expire_at" => "date"
    ];

    public function user () : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function comments () : HasMany {
        return $this->hasMany(Comment::class);
    }

    public function images () : MorphMany {
        return $this->morphMany(Image::class, "imageable");
    }

    public function likes () : BelongsToMany {
        return $this->belongsToMany(
            User::class,
            "likes",
            "post_id",
            "user_id"
        );
    }

    public function group () : BelongsTo {
        return $this->belongsTo(Group::class, "group_id", "id");
    }

    public function requests () : BelongsToMany {
        return $this->belongsToMany(
            User::class,
            "requests",
            "post_id",
            "user_id"
        )->withPivot('status')->withTimestamps();
    }
}
