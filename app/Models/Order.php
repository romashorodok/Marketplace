<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'firstName',
        'lastName',
        'address',
        'total_price',
        'charge_token',
        'order_id'
    ];

    public function billingItems(): HasMany
    {
        return $this->hasMany(BillingItem::class);
    }
}
