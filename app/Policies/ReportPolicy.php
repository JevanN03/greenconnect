<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{
    public function view(User $user, Report $report): bool
    {
        return $user->is_admin || $report->user_id === $user->id;
    }

    public function update(User $user, Report $report): bool
    {
        return $user->is_admin || $report->user_id === $user->id;
    }

    public function delete(User $user, Report $report): bool
    {
        return $user->is_admin || $report->user_id === $user->id;
    }
}
