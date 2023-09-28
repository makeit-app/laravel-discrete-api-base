<?php

namespace MakeIT\DiscreteApi\Base\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $User
     * @return bool
     */
    public function viewAny(User $User): bool
    {
        return $User->hasRole(['super', 'admin', 'support']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $User
     * @param User $Model
     * @return bool
     */
    public function view(User $User, User $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']) || $Model->id == $User->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return bool
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $User
     * @param User $Model
     * @return bool
     */
    public function update(User $User, User $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']) || $Model->id == $User->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $User
     * @param User $Model
     * @return bool
     */
    public function delete(User $User, User $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']) || $Model->id == $User->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $User
     * @param User $Model
     * @return bool
     */
    public function restore(User $User, User $Model): bool
    {
        return $User->hasRole(['super', 'admin', 'support']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $User
     * @param User $Model
     * @return bool
     */
    public function forceDelete(User $User, User $Model): bool
    {
        return $User->hasRole(['super']);
    }
}
