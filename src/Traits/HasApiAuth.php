<?php

namespace SanderCokart\LaravelApiAuth\Traits;

trait HasApiAuth
{
    use CanChangeEmail,
        CanChangePassword,
        CanResetPassword,
        MustVerifyEmail;
}
