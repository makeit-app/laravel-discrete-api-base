<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use MakeIT\DiscreteApi\Base\Models\Profile;

// uncomment if using the DiscreteApi\Organizations package
// use MakeIT\DiscreteApi\Organizations\Models\UserOrganizationSlot;

/**
 * @property Collection organizations
 * @property Profile profile
 * @property UserOrganizationSlot organization_slots
 * @method organizations()
 * @method profile()
 * @method organization_slots()
 * @method static where(string $string, mixed $email)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    // PACKAGE 'BASE' TRAITS START
    //   uncomment if package is installed in app/
    //     use \App\Traits\DiscreteApi\Base\HasRoles;
    //     use \App\Traits\DiscreteApi\Base\HasProfile;
    //   remove if package is installed in app/
    use \MakeIT\DiscreteApi\Base\Traits\HasRoles;
    use \MakeIT\DiscreteApi\Base\Traits\HasProfile;
    // PACKAGE 'BASE' TRAITS END

    // PACKAGE 'ORGANIZATIONS' TRAITS START
    //   uncomment if use organizations package and it is installed into app/
    //     use \App\Traits\DiscreteApi\Organizations\HasUserOrganizationSlots;
    //     use \App\Traits\DiscreteApi\Organizations\HasOrganizations;
    //   uncomment if use organizations package
    //   or remove if organizations package is installed into app/
    //      use \MakeIT\DiscreteApi\Organizations\Traits\HasUserOrganizationSlots;
    //      use \MakeIT\DiscreteApi\Organizations\Traits\HasOrganizations;
    // PACKAGE 'ORGANIZATIONS' TRAITS END

    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'email',
        'password',
        'is_banned'
    ];
    protected $hidden = [
        'email',
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
        'roles',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'deleted_at' => 'datetime'
    ];
    protected $appends = [
        'is_confirmed'
    ];

    public function getIncrementing(): bool
    {
        return true;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function createToken(string $name, array $abilities = ['*'], string $clientType = null): NewAccessToken
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'client_type' => $clientType,
            'token' => hash('sha256', $plainTextToken = Str::random(41)),
            'abilities' => $abilities,
        ]);
        return new NewAccessToken($token, $token->getKey() . '|' . $plainTextToken);
    }

    public function isConfirmed(): Attribute
    {
        return Attribute::get(fn () => (bool)$this->email_verified_at);
    }

}
