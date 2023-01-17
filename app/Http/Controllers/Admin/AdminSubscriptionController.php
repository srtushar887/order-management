<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\subscription_plan;
use App\Models\User;
use App\Models\user_plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSubscriptionController extends Controller
{
    public function plan_list()
    {
        $plans = subscription_plan::orderBy('id','desc')->paginate(20);
        return view('admin.subscription.planList',compact('plans'));
    }

    public function plan_save(Request $request)
    {
        $plan = new subscription_plan();
        $plan->plan_name = $request->plan_name;
        $plan->plan_amount = $request->plan_amount;
        $plan->plan_description = $request->plan_description;
        $plan->plan_status = $request->plan_status;
        $plan->save();

        return back()->with('success','Plan Successfully Created');
    }

    public function plan_update(Request $request)
    {
        $plan = subscription_plan::where('id',$request->plan_edit_id)->first();
        $plan->plan_name = $request->plan_name;
        $plan->plan_amount = $request->plan_amount;
        $plan->plan_description = $request->plan_description;
        $plan->plan_status = $request->plan_status;
        $plan->save();

        return back()->with('success','Plan Successfully Updated');
    }

    public function plan_delete(Request $request)
    {
        $plan = subscription_plan::where('id',$request->plan_delete_id)->first();
        $plan->delete();
        return back()->with('success','Plan Successfully Deleted');
    }

    public function user_plans()
    {
        $user_plans = user_plan::with(['plan','user'])->paginate(30);
        return view('admin.subscription.userPlan',compact('user_plans'));
    }

    public function user_plans_update(Request $request)
    {
        $user_plan = user_plan::where('id',$request->user_plan_id)->first();
        $user_plan->status = $request->plan_status;
        $user_plan->save();
        return back()->with('success','Plan Successfully Updated');
    }

    public function user_custom_order()
    {
        $plans = subscription_plan::all();
        $users = User::select('id','name')->get();
        return view('admin.subscription.customOrder',compact('plans','users'));
    }


    public function user_custom_order_save(Request $request)
    {
        $check_user_plan = user_plan::where('user_id',$request->user_id)->first();
        if ($check_user_plan){
            return back()->with('alert','User already have plan');
            exit();
        }

        $new_user_plan = new user_plan();
        $new_user_plan->user_id = $request->user_id;
        $new_user_plan->plan_id = $request->plan_id;
        $new_user_plan->status = $request->status;
        $new_user_plan->purchase_date = Carbon::now();
        $new_user_plan->save();

        return back()->with('success','Plan Successfully Assigned');
    }



}
