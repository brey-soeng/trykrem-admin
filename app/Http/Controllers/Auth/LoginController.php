<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\SysAdmin;
use App\Services\ApiCodeService;
use Facade\FlareClient\Api;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * LoginController constructor.
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','refresh']]);
    }

    /**
     * @return string
     */
    public function username() : string
    {
        return 'username';
    }

    /**
     * @return Guard
     */
    protected function guard() : Guard
    {
        return Auth::guard('api');
    }

    /**
     * @param LoginRequest $request
     * @return Response
     */
    public function login(LoginRequest $request) : Response
    {

        $credentials = $this->credentials($request);

        if($token = $this->guard()->attempt($credentials)){
            return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
                ->withHttpCode(ApiCodeService::HTTP_OK)
                ->withData($this->respondWithToken($token))
                ->build();
        }

        return ResponseBuilder::asError(ApiCodeService::HTTP_UNPROCESSABLE_ENTITY)
            ->withHttpCode(ApiCodeService::HTTP_UNPROCESSABLE_ENTITY)
            ->withMessage(__('auth.failed'))
            ->withData([
                $this->username() => [__('auth.failed')]
            ])
            ->build();
    }

    /**
     * @return Response
     */

    public function refresh() : Response
    {
        try {
            $token = $this->guard()->refresh();
            return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
                ->withHttpCode(ApiCodeService::HTTP_OK)
                ->withData($this->respondWithToken($token))
                ->build();
        }catch (JWTException $exception) {
            return ResponseBuilder::asError(ApiCodeService::HTTP_TOKEN_EXPIRED)
                ->withHttpCode(ApiCodeService::HTTP_TOKEN_EXPIRED)
                ->build();
        }
    }


    public function me() : Response
    {
        /**
         * @var SysAdmin $user
         */
         $user = $this->guard()->user();
         return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
             ->withHttpCode(ApiCodeService::HTTP_OK)
             ->withData($user)
             ->build();

    }

    /**
     * @return Response
     */
    public function logout() : Response
    {
        $this->guard()->logout();
        return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
            ->withHttpCode(ApiCodeService::HTTP_OK)
            ->withData()
            ->build();
    }

    /**
     * @param string $token
     * @return array
     */
    protected function respondWithToken(string $token) : array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
    }

}
