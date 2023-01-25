<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\credit_plan;
use App\Models\subscription_plan;
use App\Models\User;
use App\Models\user_credit_plan;
use App\Models\user_order;
use App\Models\user_order_detail;
use App\Models\user_plan;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
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


    public function checkout_submit(Request $request)
    {
        $order = $this->create_order($request);
        $order_details = $this->create_order_details($request, $order);
        $payment = $this->makePayment($request);

        return back()->with('success', 'Payment Successful');
    }

    private function create_order($request)
    {
        $new_order = new user_order();
        $new_order->user_id = Auth::user()->id;
        $new_order->order_id = time() . Auth::user()->id . rand(0000, 9999);
        $new_order->total_amount = number_format($request->plan_amount, 2);
        $new_order->name = $request->name;
        $new_order->email = $request->email;
        $new_order->phone = $request->phone;
        $new_order->address = $request->address;
        $new_order->save();

        return $new_order;
    }

    private function create_order_details($request, $order)
    {
        $cards = Cart::content();

        foreach ($cards as $card) {
            $order_detais = new user_order_detail();
            $order_detais->user_id = Auth::user()->id;
            $order_detais->order_id = $order->id;
            $order_detais->plan_id = $card->id;
            $order_detais->amount = $card->price;
            $order_detais->plan_type = $card->options->type;
            $order_detais->save();
        }

        return 'done';
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
