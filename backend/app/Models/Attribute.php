<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'type'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->withPivot('order', 'is_filterable')
            ->withTimestamps();
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }
}
