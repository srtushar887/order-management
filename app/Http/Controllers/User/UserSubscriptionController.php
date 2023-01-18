<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\subscription_plan;
use App\Models\user_plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionController extends Controller
{
    public function plan_list()
    {
        $user_plan_count = user_plan::where('user_id', Auth::user()->id)->count();
        $plans = subscription_plan::active()->orderBy('id', 'desc')->paginate(20);
        return view('user.subscription.planList', compact('plans', 'user_plan_count'));
    }

    public function plan_choose($id)
    {
        $plan = subscription_plan::where('id', $id)->first();
        return view('user.subscription.planChoose', compact('plan'));
    }

    public function my_plan()
    {
        $plan = user_plan::where('user_id', Auth::user()->id)->with('plan')->first();

        if ($plan) {
            $all_plans = subscription_plan::where('id', '!=', $plan->plan_id)->get();
        } else {
            $all_plans = subscription_plan::all();
        }

        return view('user.subscription.myPlan', compact('plan', 'all_plans'));
    }

    public function my_plan_change(Request $request)
    {
        return redirect(route('user.payment.stripe', $request->user_plan_id));
    }
}
