<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected function guard()
    {
        return Auth::guard('alt');
    }

    public function username()
    {
    return 'user_name';
    }

    public function login(Request $request)
    {
         $requestUser = User::where('user_name', $request->input('user_name'))->first();

    if ($requestUser) {
        if (Hash::check($request->input('user_pass'), $requestUser->user_pass)) {
            // The passwords match...
            // Manually log in the user
            User::setCurrent($requestUser);
            return redirect()->route('routeHomepage');
        }
    }

    // The credentials do not match...
    return redirect()->back()->withErrors(['credentials' => 'The provided credentials do not match our records.']);
    }

    public function logout()
    {
        User::logout();
        return redirect()->route('routeLogin');
    }
}

