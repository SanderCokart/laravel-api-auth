<?php

namespace SanderCokart\LaravelApiAuth\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;

trait PrunesExpired
{
    use MassPrunable;

    /**
     * Get the prunable model query.
     *
     * @return Builder
     */
    public function prunable(): Builder
    {
        return static::query()->where('expires_at', '<=', now());
    }

}
