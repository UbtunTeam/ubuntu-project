<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
  * @OA\Schema(
  *     description="Farm product model",
  *     type="object",
  *     title="FarmProduct model"
  * ),
  *    @OA\Property(
  *      property="farm_id",
  *      type="integer",
  *    ),
  *    @OA\Property(
  *      property="stock_id",
  *      type="integer",
  *    ),
  *     @OA\Property(
  *      property="product_name",
  *      type="string",
  *    ),
  *     @OA\Property(
  *      property="description",
  *      type="string",
  *    ),
  *    @OA\Property(
  *      property="price",
  *      type="float",
  *    ),
  *     @OA\Property(
  *        property="plant_date",
  *        type="string",
  *     ),
  *     @OA\Property(
  *      property="harvest_date",
  *      type="string",
  *    ),
  *    @OA\Property(
  *      property="product_image",
  *      type="string",
  *    )
 */
class FarmProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'stock_id',
        'product_name',
        'description',
        'price',
        'plant_date',
        'harvest_date',
        'product_image'
    ];

    public function farm()
    {
        return $this->belongsToMany(Farm::class);
    }
}
