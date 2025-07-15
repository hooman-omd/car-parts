<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Auth\Access\Response;

class AddressPolicy
{

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserAddress $userAddress): bool
    {
        if ($user->role == "admin") {
            return true;
        }
        return $userAddress->user->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserAddress $userAddress): bool
    {
        if ($user->role == "admin") {
            return true;
        }
        return $userAddress->user->is($user);
    }
}
