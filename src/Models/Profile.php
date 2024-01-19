<?php

namespace MakeIT\DiscreteApi\Base\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MakeIT\DiscreteApi\Base\Traits\BelongsToUser;

// uncomment only in "\App\" namespace!
// use MakeIT\DiscreteApi\Organizations\Traits\HasOrganization;
// use MakeIT\DiscreteApi\Organizations\Traits\HasWorkspace;

class Profile extends Model
{
    use BelongsToUser;
    // uncomment only in "\App\" namespace!
    // use HasOrganization;
    // use HasWorkspace;

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'profiles';
    protected $fillable = ['user_id', 'locale', 'firstname', 'lastname', 'avatar_path'];
    protected $hidden = ['id', 'user_id'];
    protected $casts = [];
    protected $appends = ['avatar_url'];
    protected $touches = ['user'];

    public function getIncrementing(): bool
    {
        return true;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function avatarUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            return $this->avatar_path
                ? Storage::disk($this->avatarDisk())->url($this->avatar_path)
                : $this->defaultAvatarUrl();
        });
    }

    /**
     * Get the disk that profile avatars should be stored on.
     */
    public function avatarDisk(): string
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public';
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     */
    public function defaultAvatarUrl(): string
    {
        $name = trim(
            collect(explode(' ', $this->firstname . ' ' . $this->lastname))->map(function ($segment) {
                return mb_substr($segment, 0, 1);
            })->join(' ')
        );
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Update the user's profile avatar.
     */
    public function updateProfileAvatar(UploadedFile $photo, string $storagePath = 'profile-avatars'): void
    {
        tap($this->avatar_path, function ($previous) use ($photo, $storagePath) {
            $this->forceFill([
                'avatar_path' => $photo->storePublicly(
                    $storagePath,
                    ['disk' => $this->avatarDisk()]
                ),
            ])->save();
            if ($previous) {
                Storage::disk($this->avatarDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the user's profile avatar.
     */
    public function deleteAvatar(): void
    {
        if (is_null($this->avatar_path)) {
            return;
        }
        Storage::disk($this->avatarDisk())->delete($this->avatar_path);
        $this->forceFill([
            'avatar_path' => null,
        ])->save();
    }

}
