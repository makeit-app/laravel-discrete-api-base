<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use MakeIT\UserRoles\HasRoles;

class User extends Authenticatable // implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;

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
        'pivot'
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
        return Attribute::get(fn() => (bool)$this->email_verified_at);
    }

}
