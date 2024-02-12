<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use \App\Enum\TokenAbilityEnum;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Login function with Sanctum tokens
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request) {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('username', 'password'))) {
            $errors = [];
            $errors['credentials'][] = 'Invalid Credentials';
            $response = [
                'message' => 'The credentials you provided are not valid',
                'errors' => $errors
            ];
            return response()->json($response, 401);
        }

        $user = User::where('username', $request['username'])->first();

        $accessToken = $user->createToken('access_token', [TokenAbilityEnum::ACCESS_API], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
        $refreshToken = $user->createToken('refresh_token', [TokenAbilityEnum::ISSUE_ACCESS_TOKEN], Carbon::now()->addMinutes(config('sanctum.rt_expiration')));

        $response = [
            'user' => $user,
            'token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
        ];

        return response()->json($response);

    }

    /**
     * Refresh Token: not yet implemented in Frontend
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        // since we are refreshing tokens, we delete all tokens and regenerate them
        // this approach will logout a user from all devices
        // better approach would use Laravel Passport to manage tokens
        Auth::user()->tokens()->delete();

        $accessToken = Auth::user()->createToken('access_token', [TokenAbilityEnum::ACCESS_API], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
        $refreshToken = Auth::user()->createToken('refresh_token', [TokenAbilityEnum::ISSUE_ACCESS_TOKEN], Carbon::now()->addMinutes(config('sanctum.rt_expiration')));
        $response = [
            'user' => $request->user(),
            'token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
        ];

        return response()->json($response);

    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // since we are logging out, we delete all tokens and regenerate them
        // this approach will logout a user from all devices
        // better approach would use Laravel Passport to manage tokens
        Auth::user()->tokens()->delete();
        $response = [
            'message' => 'User logged out'
        ];

        return response()->json($response);

    }
}
