<?php

namespace MakeIT\DiscreteApi\Base\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super', 'admin', 'support']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasRole(['super', 'admin', 'support']) || $role->users()->where('user_id', $user->id)->count() > 0;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['super', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasRole(['super', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function delete(User $user, Role $role): bool
    {
        return (!$role->users->count() > 0 && $user->hasRole(['super']))
            || ($role->is_protected == false && $user->hasRole(['admin']));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->hasRole(['super'])
            || ($role->is_protected == false && $user->hasRole(['admin']));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return !$role->users->count() > 0 && $user->hasRole(['super']);
    }

    /**
     * Determine whether the user can attach a User to a Role.
     *
     * @param User $user
     * @param Role $role
     * @param User $User
     * @return bool
     */
    public function attachUser(User $user, Role $role, User $User): bool
    {
        return $user->hasRole(['super', 'admin', 'support']) || $role->name == 'user';
    }

    /**
     * Determine whether the user can detach a User from a Role.
     *
     * @param User $user
     * @param Role $role
     * @param User $User
     * @return bool
     */
    public function detachUser(User $user, Role $role, User $User): bool
    {
        return ($user->hasRole(['super', 'admin', 'support']) && !in_array($role->name, ['user', 'super']));
    }

    /**
     * Determine whether the user can attach any User to the Role.
     *
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function attachAnyUser(User $user, Role $role): bool
    {
        return $user->hasRole(['super', 'admin', 'support']) || $role->name == 'user';
    }

}
