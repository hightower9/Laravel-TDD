<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'token',
    ];

    protected $casts = [
        'token' => 'array',
    ];
}
