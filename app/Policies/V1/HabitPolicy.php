<?php

namespace App\Policies\V1;

use App\Models\Habit;
use App\Models\User;

class HabitPolicy
{

    /**
     * Determine whether the user can update the model.
     */
    public function show(User $user, Habit $habit): bool
    {
        return $this->habitBelongsToUser($user, $habit);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Habit $habit): bool
    {
        return $this->habitBelongsToUser($user, $habit);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Habit $habit): bool
    {
        return $this->habitBelongsToUser($user, $habit);
    }

    private function habitBelongsToUser(User $user, Habit $habit): bool
    {
        return $habit->user_id == $user->id;
    }

}
