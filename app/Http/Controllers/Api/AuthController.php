<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Administrator;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser;

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login",
     *     tags={"Auth"},
     *     description="Login by email and password [As Admin] .",
     *     operationId="login",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass admin credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="admin@test.com"),
     *       @OA\Property(property="password", type="string", format="password", example="admin"),
     *    ),
     * ),
     *     @OA\Response( response=200, description="successful operation", @OA\JsonContent() ),
     * )
     */
    public function login(LoginRequest $request)
    {
        $admin = Administrator::where('email', $request->email)->first();

        if (!$admin or !Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->errorResponse(__('auth.failed'), 401);
        }

        $token = $admin->createToken('Personal Access Token');

        return $this->successResponse([
            'access_token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at,
        ], __('auth.login_success'));
    }

}
