<?php

namespace SanderCokart\LaravelApiAuth\Models;

use SanderCokart\LaravelApiAuth\Traits\CanExpire;
use SanderCokart\LaravelApiAuth\Traits\HasUuidsV7;
use SanderCokart\LaravelApiAuth\Traits\PrunesExpired;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory, CanExpire, HasUuidsV7, PrunesExpired;

    public $timestamps = false;

    protected $fillable = ['id', 'token', 'expires_at', 'user_id'];

    protected $casts = ['expires_at' => 'datetime'];
}
