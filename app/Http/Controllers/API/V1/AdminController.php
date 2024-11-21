<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAuthors()
    {
        return response()->json(['data' => User::where('role', 'AUTHOR')->get()]);
    }

    public function activateAuthor(Request $request)
    {
        User::find($request->author_id)->update(['is_active' => 1]);
        return response()->json(['message' => 'Author activated succesfully.'], 200);
    }

    public function deactivateAuthor(Request $request)
    {
        User::find($request->author_id)->update(['is_active' => 0]);
        return response()->json(['message' => 'Author deactivated succesfully.'], 200);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return response()->json(['message'=>'Logged out successfully.', 200]);
    }
}
