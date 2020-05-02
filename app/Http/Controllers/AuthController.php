<?php

namespace App\Http\Controllers;

use App\Member;
use App\Admin;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginMember(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $email = $request->email;
        $password = $request->password;

        $user = Member::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'email not found'
            ], 401);
        }

        if (Hash::check($password, $user->password)) {
            return response()->json($user);
        } else {
            return response()->json([
                'message' => 'invalid email or password'
            ], 401);
        }
        return response()->json([
                'message' => 'login failed'
        ], 400);
    }

    public function loginAdmin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $email = $request->email;
        $password = $request->password;

        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            return response()->json([
                'message' => 'email not found'
            ], 401);
        }

        if ($password == $admin->password) {
            return response()->json($admin);
        } else {
            return response()->json([
                'message' => 'invalid email or password'
            ], 401);
        }

        // if(Hash::check($password, $user->password)){
        //     return response()->json($user);
        // }else {
        //     return response()->json([
        //         'message' => 'invalid email or password'
        //     ], 401);
        // }
        return response()->json([
                'message' => 'login failed'
        ], 400);
    }
}
