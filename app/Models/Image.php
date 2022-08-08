<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path'];

    protected $hidden = ['id'];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => asset($value)
        );
    }
}
