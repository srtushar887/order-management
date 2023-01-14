<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserPaymentController extends Controller
{
    public function pay_stripe()
    {
        return view('user.payment.stripe');
    }

    public function pay_stripe_submit(Request $request)
    {

    }
}
