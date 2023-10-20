<?php

namespace SanderCokart\LaravelApiAuth\Observers;

use App\Models\User;
use SanderCokart\LaravelApiAuth\Contracts\CanChangeEmailContract;
use SanderCokart\LaravelApiAuth\Contracts\CanChangePasswordContract;
use SanderCokart\LaravelApiAuth\Contracts\MustVerifyEmailContract;
use SanderCokart\LaravelApiAuth\Traits\ResolvesFrontendName;

class PasswordEmailChangeObserver
{
    use ResolvesFrontendName;

    public function updating(User $user): void
    {
        if (($user instanceof CanChangePasswordContract) && $user->isDirty('password')) {
            $user->sendPasswordChangedNotification($this->resolveFrontendName());
        }
        if (($user instanceof CanChangeEmailContract) && $user->isDirty('email')) {
            $user->sendEmailChangedNotification($this->resolveFrontendName());
        }
    }

    public function updated(User $user): void
    {
        if (($user instanceof MustVerifyEmailContract) && $user->isDirty('email')) {
            $user->sendEmailVerificationNotification($this->resolveFrontendName());
        }
    }
}
