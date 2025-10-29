<?php

namespace App\Models;

use App\Contracts\EncryptorInterface;
use App\Contracts\HasherInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CallRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'status',
        'source',
        'comment',
        'searchable_name',
        'searchable_phone'
    ];

    protected static function boot()
    {
        parent::boot();

        $hasher = app(HasherInterface::class);

        static::saving(function ($callRequest) use ($hasher) {
            if ($callRequest->isDirty('name')) {
                $callRequest->searchable_name = $hasher->hash(strtolower($callRequest->getOriginal('name')));
            }
            if ($callRequest->isDirty('phone')) {
                $phone = preg_replace('/[^0-9]/', '', $callRequest->getOriginal('phone'));
                $callRequest->searchable_phone = $hasher->hash($phone);
            }
            if ($callRequest->isDirty('email')) {
                $callRequest->searchable_email = $hasher->hash(strtolower($callRequest->getOriginal('email')));
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

    protected function phone(): Attribute
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

    public function scopeSearchByEmail($query, $email)
    {
        $hasher = app(HasherInterface::class);
        $hash = $hasher->hash(strtolower($email));
        return $query->where('searchable_email', $hash);
    }
}
