<?php

namespace App\Contracts;

interface HasherInterface
{
    public function hash(string $value): string;
}
