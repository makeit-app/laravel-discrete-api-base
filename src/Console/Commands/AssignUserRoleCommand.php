<?php

namespace MakeIT\DiscreteApi\Base\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignUserRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makeit:discreteapi:base:assign-user-role {user : User <id>}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will assigns a role to the user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $user_id = $this->argument('user');
        $User    = User::with([ 'roles' ])->findOrFail($user_id);
        $Roles   = Role::get()->pluck('name');
        $roles   = $this->choice('Select roles for the user', $Roles->toArray(), $Roles->keys()->last(), 1, true);
        foreach ($roles as $role) {
            if (!$User->hasRole([ $role ])) {
                $User->assignRole($role);
            }
        }
        $User->load('roles');
        return 0;
    }
}
