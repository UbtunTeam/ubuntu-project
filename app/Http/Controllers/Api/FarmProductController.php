<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FarmProductResource;
use App\Models\Farm;
use App\Models\FarmProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FarmProductController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/product/get-farmer-product",
     *      operationId="farmProduct",
     *      tags={"Authentication"},
     *      summary="Get login farmer products",
     *      description="Returns login farmer products",
     *      security={{ "Bearer":{} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    /**
     * Show all farm product
     * for the logged in farmer
     */
    public function index()
    {
        // check logged user
        $user = Auth::user();

        if(!is_null($user))
        {

            $product = DB::table('artisans')
            ->select('services.id', 'artisans.user_id', 'services.artisan_id', 'services.service_name', 'services.category_id',
            'services.sub_category_id','services.service_address','services.state_id','services.city_id','services.service_desc',
            'services.service_image')
            ->where('artisans.user_id', '=', $user->id)
            ->join('services', 'services.artisan_id', '=', 'artisans.id')
            ->get();

            if(count($product) > 0)
            {
                return response()->json(["status" => "success", "count" => count($product), "data" => $product], 200);
            }
            else
            {
                return response()->json(["status" => "failed", "count" => count($product), "message" => "Failed! no service found"], 405);
            }
        }
        return response()->json(["status" => "failed", "message" => "Internal Server Error"], 500);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/farm/show-farm-product/{id}",
     *      operationId="farmProductById",
     *      tags={"Farm Products"},
     *      summary="Get single farm product",
     *      description="Returns single farm product",
     *      security={{ "Bearer":{} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/FarmProduct")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    /**
     * Show single farm
     * for product
     */
    public function show(FarmProduct $product)
    {

        return new FarmProductResource($product);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/farm/show-all-products",
     *      operationId="allFarmProducts",
     *      tags={"FarmProducts"},
     *      summary="Get farm product",
     *      description="Returns all farm product",
     *      security={{ "Bearer":{} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    /**
     * Get all farm product
     * to be displayed to all
     */
    public function showAll()
    {
        return FarmProductResource::collection(FarmProduct::with('ratings')->paginate(25));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/farm/product-search",
     *      operationId="searchFarmProduct",
     *      tags={"FarmProducts"},
     *      summary="Search for farm product",
     *      description="Returns search farm product",
     *      security={{ "Bearer":{} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    /**
     *  Search for specific service
     */
    public function search(Request $request)
    {

        $name = $request->input('product_name');

        return FarmProduct::where('product_name','like','%'.$name.'%')->get();
    }

    /**
     * @OA\Post(
     *      path="/api/v1/farm/add-new-farm-product",
     *      operationId="new-farm-product",
     *      tags={"FarmProducts"},
     *      summary="Create new farm product",
     *      description="Create new farm product",
     *      security={{ "Bearer":{} }},
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/FarmProduct)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *        )
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'farm_id' => 'required|integer',
            'product_name' => 'required|string|max:255',
            'stock_id' => 'required|integer',
            'price' => 'required|integer',
            'description' => 'required|string|max:255',
            'service_address' => 'required'
        ]);

        $product = FarmProduct::create([
            'farm_id' => $request->farm_id,
            'stock_id' => $request->stock_id,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'plant_date' => $request->plant_date,
            'harvest_date' => $request->harvest_date,
            'description' => $request->description,
            'product_image' => $request->product_image,
        ]);

        return new FarmProductResource($product);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/farm/update-farm-product/id",
     *      operationId="update-farm-product",
     *      tags={"FarmProducts"},
     *      summary="Update farm product",
     *      description="update farm product",
     *      security={{ "Bearer":{} }},
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'farm_id' => 'required|integer',
            'product_name' => 'required|string|max:255',
            'stock_id' => 'required|integer',
            'price' => 'required|integer',
            'description' => 'required|string|max:255',
            'service_address' => 'required'
        ]);

        //$user = Auth::user();

        if($request)
        {
            $product = FarmProduct::find($id)->update([
                'farm_id' => $request->farm_id,
                'product_name' => $request->product_name,
                'stock_id' => $request->stock_id,
                'price' => $request->price,
                'plant_date' => $request->plant_date,
                'harvest_date' => $request->harvest_date,
                'description' => $request->description,
                'product_image' => $request->product_image,
            ]);

            return response()->json([
                "status" => "success",
                "message" => "Success! farm product updated",
                "data" => $product,
                "statusCode" => 200
            ]);
        }
        else
        {
            return response()->json(["status" => "failed", "message" => "Un-authorized user"], 403);
        }
    }

    /**
     * Remove service from stack
     */
    public function destroy(FarmProduct $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
