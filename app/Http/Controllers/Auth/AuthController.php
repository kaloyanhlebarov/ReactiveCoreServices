<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {
    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string',
            'device_name' => 'required'
        ], [
			'email.required' 	=> 'Email address can`t be empty.',
			'email.string'	    => 'You have used unauthorized characters.',
			'email.email'		=> 'Please enter a valid email address.',
			'email.exists' 		=> 'There is no such user.',
			'password.required' => 'Password can`t be empty.',
			'password.string'   => 'You have used unauthorized characters.',
			'device_name.required'  => 'Device name can`t be empty.',
 		]);

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            throw ValidationException::withMessages([
                'email' => ['There is no such user.'],
            ]);
        }

        if(!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The password is wrong. Try agin!'],
            ]);
        }

        $token = $user->createToken($request->device_name);

        return redirect()->route('home')->with([
            'success' => 'Token successfuly generated!',
            'token' => $token->plainTextToken,
        ]);
    }
}