<?php

namespace App\Policies;

use App\Models\Pen;
use App\Models\User;

class PenPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pen $pen): bool
    {
        return $this->ownsPen($user, $pen);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pen $pen): bool
    {
        return $this->ownsPen($user, $pen);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pen $pen): bool
    {
        return $this->ownsPen($user, $pen);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pen $pen): bool
    {
        return $this->ownsPen($user, $pen);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pen $pen): bool
    {
        return $this->ownsPen($user, $pen);
    }

    private function ownsPen(User $user, Pen $pen): bool
    {
        return (int) $user->id === (int) $pen->user_id;
    }
}
