<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'article',
        'name',
        'slug',
        'price',
        'unit',
        'product_code',
        'description',
        'category_id',
        'color_id',
        'brand_id',
        'is_published',
        'is_sale',
        'surface',
        'pattern',
        'country_of_origin',
        'collection'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function getProductAttributeValue(string $attributeSlug)
    {
        return $this->attributeValues
            ->where('attribute.slug', $attributeSlug)
            ->first()?->value;
    }
}
