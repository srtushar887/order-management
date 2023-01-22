<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\credit_plan;
use App\Models\subscription_plan;
use App\Models\User;
use App\Models\user_credit_plan;
use App\Models\user_plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPaymentController extends Controller
{
    public function pay_stripe($id, $type)
    {
        if ($type == 1) {
            $plan = subscription_plan::where('id', $id)->first();
        } else {
            $plan = credit_plan::where('id', $id)->first();
        }

        $plan_type = $type;
        return view('user.payment.stripe', compact('plan', 'plan_type'));
    }

    public function pay_stripe_submit(Request $request)
    {
        $this->makePayment($request);

        if ($request->plan_type == 1) {
            $this->makeSubscriptionPlan($request);
            return redirect(route('user.my.plan'));
        } else {
            $this->makeCreditPlan($request);
            return redirect(route('user.credit.plan'))->with('success', 'Credit Successful');
        }


    }

    private function makePayment($request)
    {
        $exp = explode("/", $request->expire);
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
            'amount' => $request->plan_amount * 100,
            'currency' => 'usd',
            'source' => $charge->id,
            'description' => 'payment plan',
        ]);
    }


    private function makeSubscriptionPlan($request)
    {
        $plan_data = subscription_plan::where('id', $request->plan_id)->first();
        $check_exists = user_plan::where('user_id', Auth::user()->id)->first();
        if ($check_exists) {
            $check_exists->plan_id = $plan_data->id;
            $check_exists->status = 1;
            $check_exists->purchase_date = Carbon::now();
            $check_exists->save();
        } else {
            $new_user_plan = new user_plan();
            $new_user_plan->user_id = Auth::user()->id;
            $new_user_plan->plan_id = $plan_data->id;
            $new_user_plan->status = 1;
            $new_user_plan->purchase_date = Carbon::now();
            $new_user_plan->save();
        }

    }

    private function makeCreditPlan($request)
    {
        $credit_plan = credit_plan::where('id', $request->plan_id)->first();

        $user_credit_plan = new user_credit_plan();
        $user_credit_plan->user_id = Auth::user()->id;
        $user_credit_plan->plan_id = $credit_plan->id;
        $user_credit_plan->credit = $credit_plan->plan_credit;
        $user_credit_plan->status = 0;
        $user_credit_plan->save();


        $check_credit = Auth::user()->credit == null ? 0 : Auth::user()->credit;
        $user = User::where('id', Auth::user()->id)->first();
        $user->credit = $check_credit + $credit_plan->plan_credit;
        $user->save();

    }

}
