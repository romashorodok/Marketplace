<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'count', 'vendor_id', 'image_id', 'category_id', 'description'];

    protected $with = ['image', 'category'];

    protected $hidden = ['category_id', 'image_id', 'created_at', 'updated_at', 'count'];

    protected $casts = [
        'price' => 'double'
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function image(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }
}
