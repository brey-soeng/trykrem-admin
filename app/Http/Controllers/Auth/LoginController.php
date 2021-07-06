<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\SysAdmin;
use App\Services\ApiCodeService;
use App\Utils\Routes;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
        $this->middleware('auth:admin', ['except' => ['login','refresh']]);
    }

    /**
     * @return string
     */
    public function username() : string
    {
        return 'username';
    }

    /**
     * @param LoginRequest $request
     * @return Response
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ArrayWithMixedKeysException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ConfigurationNotFoundException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\IncompatibleTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\InvalidTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\MissingConfigurationKeyException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\NotIntegerException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request) : Response
    {
        $credentials = $this->credentials($request);

        $validator = Validator::make($request->all(),[
            'captcha' => 'required|captcha_api:'.request('key').',math',
        ],
        [
            'captcha.required' => 'Verification is required!',
            'captcha.captcha_api' => 'Verification is not match, please try again!'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all(),422);
        }

        if ($token = $this->guard()->attempt($credentials)) {
            return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
                ->withHttpCode(ApiCodeService::HTTP_OK,)
                ->withData($this->respondWithToken($token))
                ->build();
        }
        return ResponseBuilder::asError(ApiCodeService::HTTP_UNPROCESSABLE_ENTITY)
            ->withHttpCode(ApiCodeService::HTTP_UNPROCESSABLE_ENTITY)
            ->withMessage(__('auth.failed'))
            ->withData([
                $this->username() => [__('auth.failed')],
            ])
            ->build();
    }

    /**
     * @return Response
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ArrayWithMixedKeysException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ConfigurationNotFoundException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\IncompatibleTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\InvalidTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\MissingConfigurationKeyException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\NotIntegerException
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
         $accessedRoutes = (new Routes($user))->routes();
         $roles = $user->roles->mapWithKeys(function ($role, $key){
                return [$key => $role->id];
         })->prepend('App\models\SysAdmin\\'. $user->id);

         unset($user->roles);

         $user['accessedRoutes'] =  $accessedRoutes;

         $user['roles'] = $roles;

        return response()->json([
            'code' => 200,
            'message' => "OK",
            'data' => $user,
            'success' => true,
            'locale'=> "en"
        ]);

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
     * @return Guard
     */
    protected function guard() : Guard
    {
        return Auth::guard('admin');
    }
    /**
     * @param string $token
     * @return array
     */
    protected function respondWithToken(string $token) : array
    {
        return[
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
    }

}
