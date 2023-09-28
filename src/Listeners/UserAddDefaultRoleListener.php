<?php

namespace MakeIT\DiscreteApi\Base\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Registered;

class UserAddDefaultRoleListener
{
    protected User $user;

    /**
     * Create the event listener.
     *
     * User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        $default_role = config('discreteapibase.default_role');
        if ($event->user instanceof User && !$event->user->hasRole([$default_role])) {
            $event->user->assignRole($default_role);
        }
    }
}
