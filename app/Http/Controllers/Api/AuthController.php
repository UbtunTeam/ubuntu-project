<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
        * @OA\Post(
        * path="/api/auth/register",
        * operationId="register",
        * tags={"Authentication"},
        * summary="User Register",
        * description="User Register here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(
        *            allOf={
        *
        *              },
        *              example = {
        *                  "full_name": "Jack Mau", "email": "testme@test.com", "role_id": "1","password": "Test@123","password_confirmation": "Test@123"
        *              }
        *         ),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"full_name","email", "password", "password_confirmation"},
        *               @OA\Property(property="full_name", type="text"),
        *               @OA\Property(property="email", type="text"),
        *               @OA\Property(property="role_id", type="integer"),
        *               @OA\Property(property="password", type="password"),
        *               @OA\Property(property="password_confirmation", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Register Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
    */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:5',
            'role_id' => 'required|integer'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)],
        ));

        if($user)
        {
            return response()->json([
                'message' => 'User registered successfully',
                'data' => $user
            ], 201);

        }
    }


    /**
        * @OA\Post(
        * path="/api/auth/login",
        * operationId="login",
        * tags={"Authentication"},
        * summary="User Login",
        * description="Login User Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
    */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = Auth::attempt($validator->validated()))
        {
            return response()->json(['status' => 'failed', 'message' => 'Invalid email and password.', 'error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($request);
    }

    /**
     * Get the token array structure.
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken(Request $request){
        //$user = User::find(2);
        $user = User::where('email', $request->email)->first();

        return response()->json([
            'access_token' => $user->createToken('BlueCollarApp')->accessToken,
            'token_type' => 'bearer',
            'message' => 'User logged in successfuly',
            'status' => 'success',
            'statusCode' => 200,
            'login' => true,
            'data' => auth()->user()
        ]);
    }


    /**
     * Logout user (Invalidate the token).
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // $request->user()->token()->revoke();
        // return response()->json(['message' => 'Successfully logged out']);

        $request->user()->token()->delete();
        $response = ['data' => 'Logout successful.'];
        return response()->json($response, 201);
    }


    /**
        * @OA\Post(
        * path="/api/forgot-password",
        * operationId="forgetPassword",
        * tags={"Authentication"},
        * summary="Forget Password",
        * description="forget password",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email"},
        *               @OA\Property(property="email", type="email")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Resend link send successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
    */
    /*
    /* Consumer of this API will request for password reset link by providing email id registered.
    /* This reset link will be sent to the provided email id, if it exists.
    /* After clicking the password reset link in the email, user is redirected to web interface.
    /* And there user is able to reset the password using Laravel's default auth views.
    */
    public function forgotPassword(Request $request)
    {
        $rules = ['email' => "required|email",];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else
        {
            try
            {
                $mail = Password::sendResetLink($request->only('email'));
                switch ($mail)
                {
                    case Password::RESET_LINK_SENT:
                        return response()->json(['errors' => 'Reset password link sent to your email box.'], 201);
                    case Password::INVALID_USER:
                        return response()->json(['errors' => 'We can\'t find a user with that email address.'], 404);
                }
            }
            catch (\Swift_TransportException $ex)
            {
                return response()->json(['errors' => $ex->getMessage(), 500]);
            }
            catch (Exception $ex)
            {
                return response()->json(['errors' => $ex->getMessage(), 500]);
            }
        }
    }


    /**
        * @OA\Put(
        * path="/v1/api/update-password",
        * operationId="updatePassword",
        * tags={"Authentication"},
        * summary="Update Password",
        * description="update password",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"current_password", "password"},
        *               @OA\Property(property="current_password", type="current_password"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
    */
    /**
     * Update password for a user
     */
    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => ['required', 'min:6'],
            'password' => ['required', 'min:6', 'confirmed'], //need to pass password_confirmation also in request
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Hash::check($request->get('current_password'), $request->user()->password)) {
            return response()->json(['errors' => 'The provided password does not match your current password.'], 404);
        }

        $request->user()->forceFill([
            'password' => Hash::make($request->get('password')),
        ])->save();

        return response(['data' => 'Password set successfully.'], 201);
    }

    /**
     * change password
     */
    public function changePassword()
    {
        $cnewPassword = request('password');
        $user = User::find(auth()->user()->id);
        $user->password = bcrypt($cnewPassword);
        $user->save();

        return response()->json(["message"=>"Password was changes successfully"], 200);

    }



}
