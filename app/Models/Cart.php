<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = ['product_id'];

    /**
     * @var string[]
     */
    protected $with = ['cartItems'];

    /**
     * @var string[]
     */
    protected $hidden = ['id', 'user_id'];

    /**
     * @var string[]
     */
    protected $appends = ['total_price', 'count'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return Attribute
     */
    public function totalPrice(): Attribute
    {
        $getAllPrices = fn (): array => array_map(fn($item) => $item['price'], $this->cartItems->toArray());

        return Attribute::make(
            get: fn() => number_format(array_sum($getAllPrices()), 2, '.', '')
        );
    }

    /**
     * @return Attribute
     */
    public function count(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cartItems->count()
        );
    }
}
