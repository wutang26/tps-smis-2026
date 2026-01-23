<?php

namespace App\Policies;

use App\Models\TimeSheet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimeSheetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return  $user->hasRole('Super Administrator') ||
                    $user->hasRole('Chief Instructor') || 
                    $user->hasRole('Academic Coordinator');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TimeSheet $timeSheet): bool
    {
        //Check if user if has a permission to view any timesheet
        if($user->hasRole('Super Administrator') ||
        $user->hasRole('Chief Instructor') || 
        $user->hasRole('Academic Coordinator')) return true;
        return $user->id == $timeSheet->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TimeSheet $timeSheet): bool
    {
        if($user->hasRole('Super Administrator') ||
        $user->hasRole('Chief Instructor') || 
        $user->hasRole('Academic Coordinator')) return true;
        return $user->id == $timeSheet->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TimeSheet $timeSheet): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TimeSheet $timeSheet): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TimeSheet $timeSheet): bool
    {
        return false;
    }
}
