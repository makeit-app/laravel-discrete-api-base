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
        if (User::all()->count() == 1) {
            $model->assignRole(config('discreteapibase.super_role'));
            $model->assignRole(config('discreteapibase.admin_role'));
            $model->assignRole(config('discreteapibase.support_role'));
            $model->assignRole(config('discreteapibase.user_role'));
        } else {
            $model->assignRole(config('discreteapibase.default_role'));
        }
        $model->profile()->create([]);
    }
}
