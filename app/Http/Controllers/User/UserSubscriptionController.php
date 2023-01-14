<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\subscription_plan;
use Illuminate\Http\Request;

class UserSubscriptionController extends Controller
{
    public function plan_list()
    {
        $plans = subscription_plan::active()->orderBy('id','desc')->paginate(20);
        return view('user.subscription.planList',compact('plans'));
    }

    public function plan_choose($id)
    {
        $plan = subscription_plan::where('id',$id)->first();
        return view('user.subscription.planChoose',compact('plan'));
    }
}
