<?php

namespace App\Models;

use App\Contracts\EncryptorInterface;
use App\Contracts\HasherInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'searchable_name',
    ];

    protected static function boot()
    {
        parent::boot();

        $hasher = app(HasherInterface::class);

        static::saving(function ($order) use ($hasher) {
            if ($order->isDirty('name')) {
                $order->searchable_name = $hasher->hash(strtolower($order->getOriginal('name')));
            }
            if ($order->isDirty('email')) {
                $order->searchable_email = $hasher->hash(strtolower($order->getOriginal('email')));
            }
            if ($order->isDirty('phone')) {
                $phone = preg_replace('/[^0-9]/', '', $order->getOriginal('phone'));
                $order->searchable_phone = $hasher->hash($phone);
            }
            if ($order->isDirty('customer_name')) {
                $order->searchable_customer_name = $hasher->hash(strtolower($order->getOriginal('customer_name')));
            }
        });
    }


    protected function name(): Attribute
    {
        $encryptor = app(EncryptorInterface::class);
        return Attribute::make(
            get: fn($value) => $value ? $encryptor->decrypt($value) : null,
            set: fn($value) => $value ? $encryptor->encrypt($value) : null
        );
    }

    protected function email(): Attribute
    {
        $encryptor = app(EncryptorInterface::class);
        return Attribute::make(
            get: fn($value) => $value ? $encryptor->decrypt($value) : null,
            set: fn($value) => $value ? $encryptor->encrypt($value) : null
        );
    }

    protected function phone(): Attribute
    {
        $encryptor = app(EncryptorInterface::class);
        return Attribute::make(
            get: fn($value) => $value ? $encryptor->decrypt($value) : null,
            set: fn($value) => $value ? $encryptor->encrypt($value) : null
        );
    }

    protected function address(): Attribute
    {
        $encryptor = app(EncryptorInterface::class);
        return Attribute::make(
            get: fn($value) => $value ? $encryptor->decrypt($value) : null,
            set: fn($value) => $value ? $encryptor->encrypt($value) : null
        );
    }

    protected function customerName(): Attribute
    {
        $encryptor = app(EncryptorInterface::class);
        return Attribute::make(
            get: fn($value) => $value ? $encryptor->decrypt($value) : null,
            set: fn($value) => $value ? $encryptor->encrypt($value) : null
        );
    }


    public function scopeSearchByEmail($query, $email)
    {
        $hasher = app(HasherInterface::class);
        $hash = $hasher->hash(strtolower($email));
        return $query->where('searchable_email', $hash);
    }

    public function scopeSearchByPhone($query, $phone)
    {
        $hasher = app(HasherInterface::class);
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $hash = $hasher->hash($phone);
        return $query->where('searchable_phone', $hash);
    }

    public function scopeSearchByName($query, $name)
    {
        $hasher = app(HasherInterface::class);
        $hash = $hasher->hash(strtolower($name));
        return $query->where('searchable_name', $hash);
    }

    public function scopeSearchByCustomerName($query, $customerName)
    {
        $hasher = app(HasherInterface::class);
        $hash = $hasher->hash(strtolower($customerName));
        return $query->where('searchable_customer_name', $hash);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
