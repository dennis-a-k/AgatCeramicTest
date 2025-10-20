<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeValue extends Model
{
    protected $fillable = [
        'product_id',
        'attribute_id',
        'string_value',
        'number_value',
        'boolean_value',
        'text_value',
    ];

    protected $casts = [
        'number_value' => 'decimal:2',
        'boolean_value' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function getValueAttribute()
    {
        return match ($this->attribute->type) {
            'number' => $this->number_value,
            'boolean' => $this->boolean_value,
            'text' => $this->text_value,
            default => $this->string_value,
        };
    }
}
