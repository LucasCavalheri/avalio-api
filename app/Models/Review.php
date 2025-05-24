<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'business_id',
        'status',
        'name',
        'rating',
        'comment',
        'response',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
