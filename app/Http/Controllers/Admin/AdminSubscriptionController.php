<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\all_plan;
use App\Models\credit_plan;
use App\Models\subscription_plan;
use App\Models\User;
use App\Models\user_credit_plan;
use App\Models\user_plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSubscriptionController extends Controller
{
    public function plan_list()
    {
        $plans = all_plan::where('plan_type', 1)->orderBy('id', 'desc')->paginate(20);
        return view('admin.subscription.planList', compact('plans'));
    }

    public function plan_save(Request $request)
    {
        $plan = new all_plan();
        $plan->plan_name = $request->plan_name;
        $plan->plan_amount = $request->plan_amount;
        $plan->plan_credit = 0;
        $plan->plan_description = $request->plan_description;
        $plan->plan_status = $request->plan_status;
        $plan->plan_type = 1;
        $plan->save();

        return back()->with('success', 'Plan Successfully Created');
    }

    public function plan_update(Request $request)
    {
        $plan = all_plan::where('id', $request->plan_edit_id)->first();
        $plan->plan_name = $request->plan_name;
        $plan->plan_amount = $request->plan_amount;
        $plan->plan_credit = 0;
        $plan->plan_description = $request->plan_description;
        $plan->plan_status = $request->plan_status;
        $plan->plan_type = 1;
        $plan->save();

        return back()->with('success', 'Plan Successfully Updated');
    }

    public function plan_delete(Request $request)
    {
        $plan = all_plan::where('id', $request->plan_delete_id)->first();
        $plan->delete();
        return back()->with('success', 'Plan Successfully Deleted');
    }

    public function user_plans()
    {
        $user_plans = user_plan::subscriptionPlan()->with(['plan', 'user'])->paginate(30);
        return view('admin.subscription.userPlan', compact('user_plans'));
    }

    public function user_plans_update(Request $request)
    {
        $user_plan = user_plan::where('id', $request->user_plan_id)->first();
        $user_plan->status = $request->plan_status;
        $user_plan->save();
        return back()->with('success', 'Plan Successfully Updated');
    }

    public function user_custom_order()
    {
        $plans = all_plan::subscriptionPlan()->get();
        $users = User::select('id', 'name')->get();
        return view('admin.subscription.customOrder', compact('plans', 'users'));
    }


    public function user_custom_order_save(Request $request)
    {
        $check_user_plan = user_plan::where('user_id', $request->user_id)->first();

        $plan = all_plan::where('id', $request->plan_id)->first();

        if ($check_user_plan) {
            $check_user_plan->plan_id = $plan->id;
            $check_user_plan->status = $request->status;
            $check_user_plan->purchase_date = Carbon::now();
            $check_user_plan->plan_type = $plan->plan_type;
            $check_user_plan->save();
        } else {
            $new_user_plan = new user_plan();
            $new_user_plan->user_id = $request->user_id;
            $new_user_plan->plan_id = $plan->id;
            $new_user_plan->status = $request->status;
            $new_user_plan->purchase_date = Carbon::now();
            $new_user_plan->plan_type = $plan->plan_type;
            $new_user_plan->save();
        }


        return back()->with('success', 'Plan Successfully Assigned');
    }


    public function credit_plan()
    {
        $all_plans = all_plan::where('plan_type', 2)->orderBy('id', 'desc')->paginate(20);
        return view('admin.credit.creditPlan', compact('all_plans'));
    }

    public function credit_plan_save(Request $request)
    {
        $plan = new all_plan();
        $plan->plan_name = $request->plan_name;
        $plan->plan_amount = $request->plan_amount;
        $plan->plan_credit = $request->plan_credit;
        $plan->plan_status = $request->plan_status;
        $plan->plan_type = 2;
        $plan->save();

        return back()->with('success', 'Credit Plan Successfully Created');
    }

    public function credit_plan_update(Request $request)
    {
        $plan = all_plan::where('id', $request->plan_edit_id)->first();
        $plan->plan_name = $request->plan_name;
        $plan->plan_amount = $request->plan_amount;
        $plan->plan_credit = $request->plan_credit;
        $plan->plan_status = $request->plan_status;
        $plan->plan_type = 2;
        $plan->save();

        return back()->with('success', 'Credit Plan Successfully Updated');
    }

    public function credit_plan_delete(Request $request)
    {
        $plan = all_plan::where('id', $request->plan_delete_id)->first();
        $plan->delete();
        return back()->with('success', 'Credit Plan Successfully Deleted');

    }


    public function credit_user_list()
    {
        $user_credit = user_plan::creditPlan()->with(['user', 'plan'])->orderBy('id', 'desc')->paginate(20);
        return view('admin.credit.creditUserList', compact('user_credit'));
    }


}
