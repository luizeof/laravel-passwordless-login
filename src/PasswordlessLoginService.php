<?php

namespace Grosv\LaravelPasswordlessLogin;

use Grosv\LaravelPasswordlessLogin\Traits\PasswordlessLogin;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;

/**
 * Service class to keep the controller clean.
 */
class PasswordlessLoginService
{
    /**
     * @var User
     */
    public $user;

    public function __construct()
    {
        $this->user = $this->getUser();
    }
    /**
     * Converts a class slug into a full class name.
     *
     * @param string $classSlug
     *
     * @return string
     */
    public function getUserClass(string $classSlug)
    {
        $slashedName = str_replace('-', '\\', $classSlug);

        return Str::title($slashedName);
    }

    /**
     * Converts the user class into a slug to use for the route.
     *
     * @param User $user
     *
     * @return string
     */
    public function getFormattedUserClass(User $user): string
    {
        $userClassName = get_class($user);
        $formattedName = str_replace('\\', '-', $userClassName);

        return Str::slug($formattedName);
    }

    /**
     * Checks if this use class uses the PasswordlessLogable trait.
     *
     * @return bool
     */
    public function usesTrait(): bool
    {
        $traits = class_uses($this->user, true);

        return in_array(PasswordlessLogin::class, $traits);
    }

    /**
     * Get the user from the request.
     *
     * @return mixed
     */
    public function getUser()
    {
        if (\request()->has('user_type')) {
            $userModel = $this->getUserClass(request('user_type'));

            return $userModel::findOrFail(request('uid'));
        }
    }
}
