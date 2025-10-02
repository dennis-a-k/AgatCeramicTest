<?php

namespace App\Models;

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
        'searchable_name'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($order) {
            if ($order->isDirty('name')) {
                $order->searchable_name = self::hashWithPepper(strtolower($order->getOriginal('name')));
            }
            if ($order->isDirty('email')) {
                $order->searchable_email = self::hashWithPepper(strtolower($order->getOriginal('email')));
            }
            if ($order->isDirty('phone')) {
                $phone = preg_replace('/[^0-9]/', '', $order->getOriginal('phone'));
                $order->searchable_phone = self::hashWithPepper($phone);
            }
        });
    }

    private static function hashWithPepper(string $value): string
    {
        return hash('sha256', $value . config('app.key'));
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->decryptData($value),
            set: fn($value) => $this->encryptData($value)
        );
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->decryptData($value),
            set: fn($value) => $this->encryptData($value)
        );
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->decryptData($value),
            set: fn($value) => $this->encryptData($value)
        );
    }

    protected function address(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->decryptData($value),
            set: fn($value) => $this->encryptData($value)
        );
    }

    private function encryptData($data)
    {
        $key = config('app.key');
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', substr($key, 7), 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    private function decryptData($data)
    {
        $key = config('app.key');
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', substr($key, 7), 0, $iv);
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
