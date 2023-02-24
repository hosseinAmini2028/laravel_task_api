<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Authcontroller extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

        return $this->userLogin($user);
    }
    public function login(LoginRequest $request)
    {
        $user = User::where('mobile', $request->mobile)->first();

        if (is_null($user) || !Hash::check($request->password, $user->password)) {
            return $this->ResponseJson(null, 401, 'mobile or password is invalid');
        }

        return $this->userLogin($user);
    }

    public function userLogin($user)
    {

        return $this->ResponseJson([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'token' => $user->createToken('token-name', ['*'])->plainTextToken
        ]);
    }
    public function logout()
    {
        if (Auth::user()->currentAccessToken()->delete()) {
            return $this->ResponseJson(null, 200, 'logout');
        } else {
            return $this->ResponseJson(null, 500, 'server');
        }
    }
}
