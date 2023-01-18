<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function create_user()
    {
        $all_users = User::paginate(20);
        return view('admin.user.createUser', compact('all_users'));
    }

    public function create_user_save(Request $request)
    {
        $new_user = new User();
        $new_user->name = $request->name;
        $new_user->email = $request->email;
        $new_user->password = Hash::make($request->password);
        $new_user->save();

        return redirect()->back()->with('success', 'User Successfully Created');
    }
}
