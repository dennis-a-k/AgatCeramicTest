<?php

namespace App\Services;

use App\Contracts\HasherInterface;

class Sha256HasherWithPepper implements HasherInterface
{
    private string $pepper;

    public function __construct()
    {
        $this->pepper = config('app.key');
    }

    public function hash(string $value): string
    {
        return hash('sha256', $value . $this->pepper);
    }
}
