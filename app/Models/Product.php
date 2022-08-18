<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'count'];

    protected $with = ['image', 'category'];

    protected $hidden = ['category_id', 'image_id', 'created_at', 'updated_at', 'count'];

    protected $casts = [
        'price' => 'double'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function image(): HasOne
    {
        return $this->hasOne(Image::class, 'id');
    }
}
