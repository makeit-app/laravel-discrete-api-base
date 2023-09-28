<?php

namespace MakeIT\DiscreteApiBase\Models;

use Illuminate\Database\Eloquent\Model;
use MakeIT\DiscreteApiBase\Traits\BelongsToUser;

/**
 * {@inheritDoc}
 */
class Profile extends Model
{
    use BelongsToUser;

    public $timestamps = false;

    protected $table = 'profiles';

    protected $fillable = ['user_id', 'locale', 'avatar_path', 'firstname', 'lastname'];

    protected $hidden = ['id', 'user_id'];

    protected $casts = [];

    protected $appends = [];

    protected $touches = ['user'];
}
