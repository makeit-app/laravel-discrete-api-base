<?php

namespace MakeIT\DiscreteApi\Base\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use MakeIT\DiscreteApi\Base\Traits\BelongsToUser;
use MakeIT\DiscreteApi\Base\Traits\HasProfileAvatar;

/**
 * @property mixed $avatar_path{@inheritDoc}
 * @property mixed $lastname
 * @property mixed $firstname
 */
class Profile extends Model
{
    use BelongsToUser;
    use HasProfileAvatar;

    public $timestamps = false;

    protected $table = 'profiles';

    protected $fillable = ['user_id', 'locale', 'avatar_path', 'firstname', 'lastname'];

    protected $hidden = ['id', 'user_id'];

    protected $casts = [];

    protected $appends = ['avatar_url'];

    protected $touches = ['user'];

    /**
     * Get the URL to the user's profile avatar.
     */
    public function avatarUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (!config('discreteapibase.features.avatars')) {
                return null;
            }
            return $this->avatar_path
                ? Storage::disk($this->avatarDisk())->url($this->avatar_path)
                : $this->defaultAvatarUrl();
        });
    }

}
