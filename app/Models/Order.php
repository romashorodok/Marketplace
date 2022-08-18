<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected $hidden = ['updated_at', 'id', 'user_id'];

    protected $casts = [
        'created_at' => 'date:Y-m-d'
    ];

    protected $with = ['billingItems'];

    public function billingItems(): HasMany
    {
        return $this->hasMany(BillingItem::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
