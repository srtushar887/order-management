<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\credit_plan;
use App\Models\subscription_plan;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class UserBillingController extends Controller
{
    public function billing()
    {
        $plan = subscription_plan::all();
        $credit = credit_plan::all();
        return view('user.billing.billing', compact('plan', 'credit'));
    }


    public function add_cart($plan_id, $type)
    {

        if ($type == 1) {
            $plan = subscription_plan::where('id', $plan_id)->first();
        } else {
            $plan = credit_plan::where('id', $plan_id)->first();
        }

        $data['qty'] = 1;
        $data['id'] = $plan->id;
        $data['name'] = $plan->plan_name;
        $data['price'] = $plan->plan_amount;
        $data['weight'] = 0;
        $data['options']['type'] = $type;

        Cart::add($data);
        return back()->with('success', 'Cart Added');
    }

    public function view_cart()
    {

        $cart_data = Cart::content();

        return view('user.billing.viewCart', compact('cart_data'));
    }


    public function cart_data_remove($id)
    {
        Cart::remove($id);
        return back()->with('success', 'Cart Removed');
    }


    public function checkout_user()
    {
        return view('user.billing.checkout');
    }
}
