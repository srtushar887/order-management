<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\user_plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $plan = user_plan::where('user_id', Auth::user()->id)->count();
        return view('user.index', compact('plan'));
    }
}
