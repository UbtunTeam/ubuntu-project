<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FarmResource;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FarmController extends Controller
{
    /**
     * @OA\Post(
     *      path="/v1/api/add-farm",
     *      operationId="storeFarm",
     *      tags={"Farms"},
     *      summary="Store new farm",
     *      description="Returns farm data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Farm")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/FarmResource")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'farmer_id' => 'required|integer',
            'farm_location' => 'required|string|max:255',
            'service_desc' => 'required|string|max:255',
            'service_address' => 'required'
        ]);

        $farm = Farm::create([
            'farmer_id' => $request->farmer_id,
            'farm_location' => $request->farm_location,
            'farm_description' => $request->farm_description,
            'landscape' => $request->landscape,
            'farm_image' => $request->farm_image,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,

        ]);

        return new FarmResource($farm);
    }

    /**
     * @OA\Get(
     *      path="/v1/api/show-all-farms",
     *      operationId="getFarmsList",
     *      tags={"Farms"},
     *      summary="Get list of farms",
     *      description="Returns list of farms",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation"
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
    public function show_all()
    {
        return FarmResource::collection(Farm::with('ratings')->paginate(25));
    }

    /**
     * @OA\Get(
     *      path="/v1/api/show-single-farm",
     *      operationId="currentFarmer",
     *      tags={"Farms"},
     *      summary="Get list of farms",
     *      description="Returns list of farms",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation"
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
     * Show single farm details
     */
    public function show()
    {
        $user = Auth::user();

        if(!is_null($user))
        {
            $farm = Farm::where("farmer_id", $user->id)->first();

            if(!is_null($farm))
            {
                return response()->json(["status" => "success", "data" => $farm], 200);
            }
            else
            {
                return response()->json(["status" => "failed", "message" => "Failed! no artisan found"], 200);
            }
        }
        else
        {
            return response()->json(["status" => "failed", "message" => "Un-authorized user"], 403);
        }
    }


    /**
     * Artisan method to update
     * profile details
     */
    // public function update(Request $request)
    // {
    //     $request->validate([
    //         "phone_number" => "required|string|max:11",
    //         "home_address" => "required",
    //         //"profile_image" => "image|mimes:jpeg,jpg,png,gif|max:1024"
    //     ]);


    //     $artisan = Farm::find($request->id);
    //     $artisan->phone_number = $request->phone_number;
    //     $artisan->office_address = $request->office_address;
    //     $artisan->optional_phone_number = $request->optional_phone_number;
    //     $artisan->profile_image = $request->file('profile_image')->store('artisan/avatars');
    //     $artisan->home_address = $request->home_address;
    //     $artisan->state_id = $request->state_id;
    //     $artisan->city_id = $request->city_id;
    //     $artisan->nearest_bus_stop = $request->nearest_bus_stop;

    //     $artisan->save();

    //     $data[] = [
    //         'id'=>$artisan->id,
    //         'phone_number'=>$artisan->phone_number,
    //         'office_address' => $artisan->office_address,
    //         'optional_phone_number' => $artisan->optional_phone_number,
    //         'home_address'=>$artisan->home_address,
    //         'state_id'=>$artisan->state_id,
    //         'city_id' => $artisan->city_id,
    //         'nearest_bus_stop' => $artisan->nearest_bus_stop,
    //         'profile_image'=>Storage::url($artisan->profile_image),
    //         'status'=>200,
    //     ];
    //     return response()->json($data);

    // }
}
