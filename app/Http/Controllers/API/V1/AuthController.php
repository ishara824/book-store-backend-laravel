<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function authenticateUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

		try {

			$user = User::where('email', $request->email)->first();
        
			if(!$user || !Hash::check($request->password,$user->password)){
				return response()->json([
					'message' => 'Invalid Credentials'
				], 401);
			}
			
			if (!$user->is_active) {
				return response()->json([
					'message' => 'User is Deactivated.'
				], 401);
			}

			$token = $user->createToken($user->name.'-AuthToken')->accessToken;
			return response()->json([
				'user' => $user,
				'access_token' => $token,
			], 200);
		} catch (Exception $exception) {
			return response()->json($exception->getMessage());
		}
        
        
    }

	public function registerAuthor(Request $request)
	{
		$request->validate([
			'name' => 'required',
			'email' => 'required|email',
			'password' => 'required|confirmed|min:6',
		]);

		try {
			$user = new User();
			$user->name = $request->name;
			$user->email = $request->email;
			$user->password = bcrypt($request->password);
			$user->role = "AUTHOR";
			$user->save();
	
			return response()->json([
				'message' => 'Registered Successfully.'
			], 200);
		} catch (Exception $exception) {
			return response()->json([
				'message' => $exception->getMessage()
			], 500);
		}
		
	}
}
