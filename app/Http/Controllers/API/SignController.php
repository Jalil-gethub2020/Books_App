<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;


class SignController extends BaseController
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('jalil161278younes17072013')->accessToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User Successfully Registred');
    }


    public function login(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('jalil161278younes17072013')->accessToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User Successfully Logged in');
        } else {

            return $this->sendError('Please Check your Auth and Try again', ['error' => 'Unauthorized']);
        }
    }
}
