<?php

namespace SanderCokart\LaravelApiAuth\Contracts;

interface ApiAuthContract extends CanChangeEmailContract, CanChangePasswordContract, CanResetPasswordContract, MustVerifyEmailContract
{

}
