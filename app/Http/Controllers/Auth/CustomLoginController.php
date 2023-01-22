<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomLoginController extends Controller
{
    public function user_login()
    {
        return view('auth.login');
    }


    public function user_register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = new  User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->credit = 0;
        $user->save();

        return redirect(route('login'))->with('success', 'Account Successfully Created');


    }

    public function user_login_submit(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:8',
        ]);


        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect(route('admin.dashboard'));
        } elseif (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect(route('user.dashboard'));
        } else {
            return back()->with('login_error', 'Invalid Credentials');
        }
    }


    public function admin_logout()
    {
        Auth::guard('admin')->logout();
        return redirect(route('user.login'));
    }
}
