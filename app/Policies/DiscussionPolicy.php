<?php

namespace App\Policies;

use App\Models\Discussion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DiscussionPolicy
{
    public function update(User $user, Discussion $discussion): bool
    {
        return $user->is_admin || $discussion->user_id === $user->id;
    }

    public function delete(User $user, Discussion $discussion): bool
    {
        return $user->is_admin || $discussion->user_id === $user->id;
    }
}
