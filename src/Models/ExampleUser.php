<?php

namespace SanderCokart\LaravelApiAuth\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;
use SanderCokart\LaravelApiAuth\Contracts\ApiAuthContract;
use SanderCokart\LaravelApiAuth\Traits\HasApiAuth;

class ExampleUser extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    ApiAuthContract
{
    use Authenticatable,
        Authorizable,
        HasApiAuth,
        HasApiTokens,
        HasFactory,
        HasUuids,
        Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    public function newUniqueId(): string
    {
        return Uuid::uuid7()->toString();
    }
}
