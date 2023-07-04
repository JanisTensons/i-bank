<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $table = 'currency_rates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'currency',
        'rate',
    ];

    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class);
    }
}
