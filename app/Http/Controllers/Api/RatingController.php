<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RatingResource;
use App\Models\FarmProduct;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/farms/{product}/ratings",
     *      operationId="rating",
     *      tags={"Ratings"},
     *      summary="Rate a specific service",
     *      description="Rate a specific farm product",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Rating")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    /**
     * Store rating request
     */
    public function store(Request $request, FarmProduct $product)
    {
        $rating = Rating::firstOrCreate(
            [
            'user_id' => $request->user_id,
            'product_id' => $product->id,
            'comment' => $product->comment
            ],
            ['rating' => $request->rating]
        );

        return new RatingResource($rating);
    }
}
