<?php

namespace MakeIT\DiscreteApiBase\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use MakeIT\DiscreteApiBase\Models\Profile;

/**
 * @method hasOne(string $class, string $string)
 */
trait HasProfile
{
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
}
