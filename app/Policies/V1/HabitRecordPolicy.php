<?php

namespace App\Policies\V1;

use App\Models\HabitRecord;
use App\Models\User;

class HabitRecordPolicy
{
/**
     * Determine whether the user can update the model.
     */
    public function show(User $user, HabitRecord $habitRecord): bool
    {
        return $this->habitRecordBelongsToUser($user, $habitRecord);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, HabitRecord $habitRecord): bool
    {
        return $this->habitRecordBelongsToUser($user, $habitRecord);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HabitRecord $habitRecord): bool
    {
        return $this->habitRecordBelongsToUser($user, $habitRecord);
    }

    private function habitRecordBelongsToUser(User $user, HabitRecord $habitRecord): bool
    {
        return $habitRecord->user_id == $user->id;
    }

}
