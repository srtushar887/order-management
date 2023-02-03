<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\all_plan;
use App\Models\subscription_plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {


        $users = User::select('id')->count();
        $active_plan = all_plan::where('plan_status', 0)->count();
        return view('admin.index', compact('users', 'active_plan'));
    }

    public function change_password()
    {
        return view('admin.pages.changePassword');
    }

    public function change_password_save(Request $request)
    {

        if ($request->password != $request->confirm_password) {
            return back()->with('alert', 'Password Not Match');
            exit();
        }

        $user = Admin::where('id', Auth::user()->id)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('success', 'Password Successfully Changed');
    }
}
