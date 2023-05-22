<?php

namespace App\Observers;

use App\Models\User;
use Ramsey\Uuid\Uuid;

class UserObserver
{
    public function creating(User $user)
    {
        $user->id = Uuid::uuid4();
    }
}