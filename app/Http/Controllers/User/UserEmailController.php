<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserEmailController extends Controller
{
    public function email_download()
    {
        return view('user.email.downloadEmail');
    }

    public function email_download_save($id)
    {
        if (Auth::user()->credit <= 0 || Auth::user()->credit == null) {
            return back()->with('alert', 'Insufficient Credit');
            exit();
        }

        $user = User::where('id', Auth::user()->id)->first();
        $user->credit = $user->credit - 1;
        $user->save();

        return back()->with('success', 'Email Download Successful');

    }
}
