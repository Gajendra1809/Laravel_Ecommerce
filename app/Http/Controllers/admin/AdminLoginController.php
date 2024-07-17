<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view("admin.login");
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email"=> "required|email",
            "password"=> "required",
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                if($user->role == 1){
                    return redirect()->back()->with('success','you are not admin');
                }
                return redirect()->route("admin.dashboard");
            } else {
                return redirect()->back()->with('error', 'Invalid email or password');
            }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
    
}
