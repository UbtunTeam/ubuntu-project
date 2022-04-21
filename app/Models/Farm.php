<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="Farm model",
 *     type="object",
 *     title="Farm model"
 * ),
 *    @OA\Property(
  *      property="farmer_id",
  *      type="integer",
  *    ),
  *     @OA\Property(
  *      property="farm_location",
  *      type="string",
  *    ),
  *     @OA\Property(
  *      property="landscape",
  *      type="string",
  *    ),
  *    @OA\Property(
  *      property="longitude",
  *      type="string",
  *    ),
  *     @OA\Property(
  *        property="latitude",
  *        type="string",
  *     ),
  *     @OA\Property(
  *      property="farm_image",
  *      type="string",
  *    ),
  *    @OA\Property(
  *      property="farm_description",
  *      type="string",
  *    )
 */
class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'farm_location',
        'farm_description',
        'landscape',
        'farm_image',
        'longitude',
        'latitude'
    ];

    public function users()
    {
        return $this->hasOne(User::class);
    }
}
