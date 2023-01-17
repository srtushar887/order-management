<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\subscription_plan;
use App\Models\user_plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPaymentController extends Controller
{
    public function pay_stripe($id)
    {
        $plan = subscription_plan::where('id',$id)->first();
        return view('user.payment.stripe',compact('plan'));
    }

    public function pay_stripe_submit(Request $request)
    {

        $plan_data = subscription_plan::where('id',$request->plan_id)->first();

        $exp  = explode("/", $request->expire);
        $emo = trim($exp[0]);
        $eyr = trim($exp[1]);

        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET_KEY')
        );

        $charge = $stripe->tokens->create([
            'card' => [
                'number' => $request->card,
                'exp_month' => $emo,
                'exp_year' => $eyr,
                'cvc' => $request->cvc,
            ],
        ]);

        $stripe->charges->create([
            'amount' => $plan_data->plan_amount * 100,
            'currency' => 'usd',
            'source' => $charge->id,
            'description' => 'payment plan',
        ]);


        $check_exists = user_plan::where('user_id',Auth::user()->id)->first();
        if ($check_exists){
            $check_exists->plan_id = $plan_data->id;
            $check_exists->status = 1;
            $check_exists->purchase_date = Carbon::now();
            $check_exists->save();
        }else{
            $new_user_plan = new user_plan();
            $new_user_plan->user_id = Auth::user()->id;
            $new_user_plan->plan_id = $plan_data->id;
            $new_user_plan->status = 1;
            $new_user_plan->purchase_date = Carbon::now();
            $new_user_plan->save();
        }



        return redirect(route('user.my.plan'));

    }

}
