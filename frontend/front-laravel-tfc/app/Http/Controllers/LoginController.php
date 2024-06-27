<?php

namespace App\Http\Controllers;

use App\Models\Perm;
use App\Models\User;
use App\Api\LoginApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if ($request->input('user_name') == 'root') {
            $user = User::where('user_name', 'root')->first();
            if ($user && Hash::check($request->input('user_pass'), $user->user_pass)) {
                User::setCurrent($user);
                return redirect()->route('routeHomepage');
            }
        }

        $api = new LoginApi();
        $response = $api->login($request->input('user_name'), $request->input('user_pass'));

        // check if login in response is true
        if (!$response['login']) {
            return redirect()->back()->withErrors(['credentials' => 'Credenciais inválidas']);
        }

        // check if user exists in database
        $user = User::where('user_name', $request->input('user_name'))->first();

        if (!$user) {

            $perm = Perm::where('perm_name', 'autorized')->first();

            // create user
            $user = new User();
            $user->user_guid = $response['id'];
            $user->user_name = $request->input('user_name');
            $user->user_pass = Hash::make($request->input('user_pass'));
            $user->user_super = 0;
            $user->perm_id = $perm->perm_id;
            $user->save();
        }

        User::setCurrent($user);
        return redirect()->route('routeHomepage');
    }

    public function logout()
    {
        User::logout();
        return redirect()->route('route.login');
    }
}

