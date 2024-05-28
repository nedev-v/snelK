<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Modules\Users\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $service;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     */
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $csrfLength = env("CSRF_TOKEN_LENGTH");         // added token length
        $csrfToken = Random::generate($csrfLength);     // added token generation

        // JWTAuth
        $token = JWTAuth::claims(['X-XSRF-TOKEN' => $csrfToken])->attempt([  // added claims
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(empty($token)){

            return response()
                ->json([
                    "status" => 401,
                    "message" => "Invalid details"
                ]);
        }

        // add cookies
        $ttl = env("JWT_COOKIE_TTL");   // added token expiry
        $tokenCookie = cookie("token", $token, $ttl);  // added jwt token cookie
        $csrfCookie = cookie("X-XSRF-TOKEN", $csrfToken, $ttl); // added csrf token cookie

        return response(["message" => "User logged in succcessfully"])
            ->withCookie($tokenCookie) // added cookies
            ->withCookie($csrfCookie); // added cookies

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     */
    public function refresh()
    {
        $csrfLength = env("CSRF_TOKEN_LENGTH");         // added token length
        $csrfToken = Random::generate($csrfLength);     // added token generation

        $token = JWTAuth::claims(['X-XSRF-TOKEN' => $csrfToken])->refresh(); // added claim



        $ttl = env("JWT_COOKIE_TTL");   // added token expiry
        $tokenCookie = cookie("token", $token, $ttl);  // added jwt token cookie
        $csrfCookie = cookie("X-XSRF-TOKEN", $csrfToken, $ttl); // added csrf token cookie

        return response(["message" => "Token refresh succcessfully"])
            ->withCookie($tokenCookie) // added cookies
            ->withCookie($csrfCookie); // added cookies

    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    protected function register(Request $request){
        $user = $this->service->register($request->all());

        if($this->service->hasErrors()){
            $errors = $this->service->getErrors();
            $errors = $this->presentErrors($errors);
            return response()->json($errors, ResponseAlias::HTTP_BAD_REQUEST);
        }

        return response()->json($user, ResponseAlias::HTTP_CREATED);
    }

    private function presentErrors($errors){
        return [
            "success" => false,
            "errors" => $errors
        ];
    }

}
