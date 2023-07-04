<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $table = 'user_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'number',
        'currency',
        'balance'
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
