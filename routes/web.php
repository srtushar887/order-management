<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [Controllers\Auth\CustomLoginController::class,'user_login'])->name('user.login');
Route::post('/custom/register', [Controllers\Auth\CustomLoginController::class,'user_register'])->name('user.custom.register');
Route::post('/custom/login/submit', [Controllers\Auth\CustomLoginController::class,'user_login_submit'])->name('user.custom.login.submit');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



Route::group(['middleware' => ['auth']], function() {
    Route::group(['prefix' => 'user'], function (){
        Route::get('/dashboard', [Controllers\User\UserController::class,'dashboard'])->name('user.dashboard');

        //user subscription plan
        Route::get('/subscription/plan', [Controllers\User\UserSubscriptionController::class,'plan_list'])->name('user.subscription.plan');
        Route::get('/subscription/plan/choose/{id}', [Controllers\User\UserSubscriptionController::class,'plan_choose'])->name('user.choose.plan');

        //user payment
        Route::get('/payment/stripe', [Controllers\User\UserPaymentController::class,'pay_stripe'])->name('user.payment.stripe');
        Route::post('/payment/stripe/submit', [Controllers\User\UserPaymentController::class,'pay_stripe_submit'])->name('user.payment.stripe.submit');
    });
});
Route::get('/admin/logout', [Controllers\Auth\CustomLoginController::class,'admin_logout'])->name('admin.logout');

Route::group(['middleware' => ['auth:admin']], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [Controllers\Admin\AdminController::class,'dashboard'])->name('admin.dashboard');

        //subscription plan
        Route::get('/subscription/plan', [Controllers\Admin\AdminSubscriptionController::class,'plan_list'])->name('admin.subscription.plan');
        Route::post('/subscription/plan/save', [Controllers\Admin\AdminSubscriptionController::class,'plan_save'])->name('admin.subscription.plan.save');
        Route::post('/subscription/plan/update', [Controllers\Admin\AdminSubscriptionController::class,'plan_update'])->name('admin.subscription.plan.update');
        Route::post('/subscription/plan/delete', [Controllers\Admin\AdminSubscriptionController::class,'plan_delete'])->name('admin.subscription.plan.delete');
    });
});
