<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\user_plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        $plan = user_plan::where('user_id', Auth::user()->id)->count();
        return view('user.index', compact('plan'));
    }


    public function change_password()
    {
        return view('user.page.changePassword');
    }

    public function change_password_save(Request $request)
    {
        if ($request->password != $request->confirm_password) {
            return back()->with('alert', 'Password Not Match');
            exit();
        }

        $user = User::where('id', Auth::user()->id)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('success', 'Password Successfully Changed');
    }
}
