<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'email',
        'telegram',
        'whatsapp',
        'organization',
        'adress',
        'inn',
        'ogrn',
        'okato',
        'okpo',
        'bank',
        'bik',
        'ks',
        'rs',
    ];
}
