<?php

namespace App\Policies;

use App\Models\Lpj;
use App\Models\User;

class LpjPolicy
{
    public function view(User $user, Lpj $lpj): bool
    {
        return $user->id === $lpj->user_id;
    }

    public function update(User $user, Lpj $lpj): bool
    {
        return $user->id === $lpj->user_id;
    }

    public function delete(User $user, Lpj $lpj): bool
    {
        return $user->id === $lpj->user_id;
    }
}
