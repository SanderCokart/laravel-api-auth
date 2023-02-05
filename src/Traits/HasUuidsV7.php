<?php

namespace SanderCokart\LaravelApiAuth\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

trait HasUuidsV7
{
    use HasUuids;

    public function newUniqueId(): string
    {
        return Uuid::uuid7()->toString();
    }
}
