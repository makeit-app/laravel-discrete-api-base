<?php

namespace MakeIT\DiscreteApi\Base\Observers;

use Illuminate\Support\Str;
use MakeIT\DiscreteApi\Base\Models\Profile as Model;

class ProfileObserver
{
    public function creating(Model $model): void
    {
        if (empty($model->{$model->getKeyName()})) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        }
    }
}
