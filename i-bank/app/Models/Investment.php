<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends Model
{
    use HasFactory;

    protected $table = 'investments';

    protected $fillable = [
        'user_id',
        'name',
        'amount',
        'price',
        'user_price'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
