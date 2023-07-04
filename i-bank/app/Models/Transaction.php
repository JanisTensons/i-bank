<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'from',
        'to',
        'amount',
        'description'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currencyRates(): HasMany
    {
        return $this->hasMany(CurrencyRate::class);
    }
}
