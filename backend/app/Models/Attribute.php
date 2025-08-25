<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    public function productValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_attribute_values')
            ->withPivot('order', 'is_filterable')
            ->withTimestamps();
    }
}
