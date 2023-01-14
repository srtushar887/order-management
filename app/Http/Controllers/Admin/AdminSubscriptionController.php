<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\subscription_plan;
use Illuminate\Http\Request;

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
}
