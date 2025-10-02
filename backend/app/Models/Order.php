<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'name',
        'email',
        'phone',
        'address',
        'comment',
        'total_amount',
        'status',
        'searchable_email',
        'searchable_phone',
        'searchable_name'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($order) {
            if ($order->isDirty('name')) {
                $order->searchable_name = self::hashWithPepper(strtolower($order->name));
            }
            if ($order->isDirty('email')) {
                $order->searchable_email = self::hashWithPepper(strtolower($order->email));
            }
            if ($order->isDirty('phone')) {
                $phone = preg_replace('/[^0-9]/', '', $order->phone);
                $order->searchable_phone = self::hashWithPepper($phone);
            }
        });
    }

    private static function hashWithPepper(string $value): string
    {
        return hash('sha256', $value . config('app.key'));
    }

    protected function customerName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value),
            set: fn($value) => Crypt::encryptString($value)
        );
    }

    protected function customerEmail(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value),
            set: fn($value) => Crypt::encryptString($value)
        );
    }

    protected function customerPhone(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value),
            set: fn($value) => Crypt::encryptString($value)
        );
    }

    protected function shippingAddress(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value),
            set: fn($value) => Crypt::encryptString($value)
        );
    }

    public function scopeSearchByEmail($query, $email)
    {
        $hash = self::hashWithPepper(strtolower($email));
        return $query->where('searchable_email', $hash);
    }

    public function scopeSearchByPhone($query, $phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $hash = self::hashWithPepper($phone);
        return $query->where('searchable_phone', $hash);
    }

    public function scopeSearchByName($query, $name)
    {
        $hash = self::hashWithPepper(strtolower($name));
        return $query->where('searchable_name', $hash);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
