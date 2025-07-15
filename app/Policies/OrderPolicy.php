<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
   
    public function delete(User $user, Order $order): bool
    {
        if ($user->role == "admin") {
            return true;
        }
        return $order->user->is($user);
    }
}
