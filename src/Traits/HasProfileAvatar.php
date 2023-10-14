<?php

namespace MakeIT\DiscreteApi\Base\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @method forceFill(null[] $array)
 */
trait HasProfileAvatar
{
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
        if (!config('discreteapibase.features.avatars')) {
            return;
        }
        if (is_null($this->avatar_path)) {
            return;
        }
        Storage::disk($this->avatarDisk())->delete($this->avatar_path);
        $this->forceFill([
            'avatar_path' => null,
        ])->save();
    }

    /**
     * Get the disk that profile avatars should be stored on.
     */
    public function avatarDisk(): string
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public';
    }

    /**
     * Get the URL to the user's profile avatar.
     */
    public function avatarUrl(): Attribute
    {
        return Attribute::get(function () {
            return $this->avatar_path
                ? Storage::disk($this->avatarDisk())->url($this->avatar_path)
                : $this->defaultAvatarUrl();
        });
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
}
