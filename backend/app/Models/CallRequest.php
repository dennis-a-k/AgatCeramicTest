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
        'searchable_phone',
        'searchable_email'
    ];

    protected static function boot()
    {
        parent::boot();

        $hasher = app(HasherInterface::class);

        static::saving(function ($callRequest) {
            $hasher = app(HasherInterface::class);
            if ($callRequest->isDirty('name') && $callRequest->name) {
                $callRequest->searchable_name = strtolower($callRequest->name);
            }
            if ($callRequest->isDirty('phone') && $callRequest->phone) {
                $phone = preg_replace('/[^0-9]/', '', $callRequest->phone);
                $callRequest->searchable_phone = $hasher->hash($phone);
            }
            if ($callRequest->isDirty('email') && $callRequest->email) {
                $callRequest->searchable_email = $hasher->hash(strtolower($callRequest->email));
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
        return $query->where('searchable_name', 'like', '%' . strtolower($name) . '%');
    }

    public function scopeSearchByEmail($query, $email)
    {
        $hasher = app(HasherInterface::class);
        $hash = $hasher->hash(strtolower($email));
        return $query->where('searchable_email', $hash);
    }
}
