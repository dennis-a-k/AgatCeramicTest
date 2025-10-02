<?php

namespace App\Services;

use App\Contracts\EncryptorInterface;

class AesEncryptor implements EncryptorInterface
{
    private string $key;

    public function __construct()
    {
        $this->key = substr(config('app.key'), 7);
    }

    public function encrypt(string $data): string
    {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $this->key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decrypt(?string $data): ?string
    {
        if ($data === null) {
            return null;
        }
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $this->key, 0, $iv);
    }
}
