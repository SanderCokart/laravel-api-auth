<?php

namespace SanderCokart\LaravelApiAuth\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     *
     * @param User $user
     *
     * @return void
     */
    public function creating(User $user): void
    {

    }

    /**
     * Handle the User "updating" event.
     *
     * @param User $user
     *
     * @return void
     */
    public function updating(User $user): void
    {
        if ($user->isDirty('password')) {
            $user->sendPasswordChangedNotification();
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     *
     * @return void
     */
    public function updated(User $user): void
    {
        if ($user->isDirty('email')) {
            $user->sendEmailVerificationNotification();
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     *
     * @return void
     */
    public function deleted(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param User $user
     *
     * @return void
     */
    public function forceDeleted(User $user): void
    {
        $user->tokens()->delete();
    }
}
