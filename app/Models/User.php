<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


/**
 * @OA\Schema(
 *     description="User model",
 *     type="object",
 *     title="User model"
 * ),
 *    @OA\Property(
  *      property="role_id",
  *      type="integer",
  *    ),
  *     @OA\Property(
  *      property="full_name",
  *      type="string",
  *    ),
  *     @OA\Property(
  *      property="email",
  *      type="string",
  *    ),
  *    @OA\Property(
  *      property="password",
  *      type="string",
  *    )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
