<?php

namespace MakeIT\DiscreteApi\Base\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    public function creating(User $model): void
    {
        if (empty($model->{$model->getKeyName()})) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        }
    }

    public function created(User $model): void
    {
        $model->assignRole(config('user_roles.default_role'));
    }
}
