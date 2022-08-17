<?php

namespace App\Models;

use App\Exceptions\CartItemException;
use App\Exceptions\ProductException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingItem extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = ['cart_id', 'product_id', 'price'];

    /**
     * @var string[]
     */
    protected $appends = ['quantity_price'];

    /**
     * @var string[]
     */
    protected $hidden = [
        'cart_id',
        'product_id'
    ];

    /**
     * @var string[]
     */
    protected $with = ['product', 'product.image'];

    /**
     * @return BelongsTo
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return Attribute
     */
    public function quantityPrice(): Attribute
    {
        $calcPrice = fn($quantity, $price) => $quantity === 0
            ? $price
            : number_format($quantity * $price, 2, '.', '');

        return Attribute::make(
            get: fn () => $calcPrice(
                $this->quantity,
                $this->price
            )
        );
    }
}
