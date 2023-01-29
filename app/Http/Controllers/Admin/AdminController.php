<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\all_plan;
use App\Models\subscription_plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {


        $users = User::select('id')->count();
        $active_plan = all_plan::where('plan_status', 0)->count();
        return view('admin.index', compact('users', 'active_plan'));
    }
}
