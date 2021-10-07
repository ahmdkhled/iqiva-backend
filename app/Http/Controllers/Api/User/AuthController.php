<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Auth;

class AuthController extends Controller
{

    use GeneralTrait;


    public function register(Request $request)
    {

        try {
            $rules = [
                "email" => "required|unique:users",
                "password" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }


            $query = [
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
            ];
            DB::beginTransaction();

            $user = User::create($query);
            $token = JWTAuth::fromUser($user);


            if (!$token)
                return $this->returnError('E001', 'something went wrong');

            $user->auth_token = $token;
            DB::commit();

            return $this->returnData('user', $user);  //return json response

        } catch (\Exception $ex) {
            DB::rollback();

            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function me()
    {
        $user = auth()->guard()->user();

        if (!$user) {
            return $this->returnError('e12', 'expired token');
        }
        return $this->returnSuccessMessage('data', $user);
    }


    public function GetMyToken()
    {

        $token = JWTAuth::fromUser(auth()->guard()->user());
        if (!$token) {
            return $this->returnError('e15', 'cant get user token ');
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard()->factory()->getTTL() * 60,

        ]);
    }

    public function login(Request $request)
    {

        try {
            $rules = [
                "email" => "required",
                "password" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            //login

            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('user-api')->attempt($credentials);  //generate token

            if (!$token)
                return $this->returnError('E001', 'Wrong Credentials');

            $user = Auth::guard('user-api')->user();

            foreach (Role::all() as $role) {
                if ($user->hasRole($role->name)) {
                    $user->role = $role->name;
                }
            }
            $user->permission = $user->AllPermissions()->pluck('name');
            $user->auth_token = $token;
            //return token

            return $this->returnData('user', $user,'Logged in Successfully');  //return json response

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $token = $request->header('auth-token');
        if ($token) {
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return $this->returnError('', 'some thing went wrongs');
            }
            return $this->returnSuccessMessage('Logged out successfully');
        } else {
            $this->returnError('', 'some thing went wrongs');
        }

    }

    public function refresh()
    {
        $user = auth()->guard()->user();

        if (!$user) {
            return $this->returnError('e12', 'expired token');
        }
        $newToken = auth()->refresh(true, true);
        return $this->returnData('Token', $newToken, 'Token Refresh Successfully');
    }
}
