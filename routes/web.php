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


Route::get('/', [Controllers\Auth\CustomLoginController::class, 'user_login'])->name('user.login');
Route::post('/custom/register', [Controllers\Auth\CustomLoginController::class, 'user_register'])->name('user.custom.register');
Route::post('/custom/login/submit', [Controllers\Auth\CustomLoginController::class, 'user_login_submit'])->name('user.custom.login.submit');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::get('/dashboard', [Controllers\User\UserController::class, 'dashboard'])->name('user.dashboard');

        //user subscription plan
        Route::get('/subscription/plan', [Controllers\User\UserSubscriptionController::class, 'plan_list'])->name('user.subscription.plan');
        Route::get('/subscription/plan/choose/{id}', [Controllers\User\UserSubscriptionController::class, 'plan_choose'])->name('user.choose.plan');

        // user credit plan
        Route::get('/credit/plan/', [Controllers\User\UserSubscriptionController::class, 'credit_plan'])->name('user.credit.plan');
        Route::post('/credit/plan/save', [Controllers\User\UserSubscriptionController::class, 'credit_plan_save'])->name('user.credit.plan.save');

        //user payment
        Route::get('/payment/stripe/{id}/{type}', [Controllers\User\UserPaymentController::class, 'pay_stripe'])->name('user.payment.stripe');
        Route::post('/payment/stripe/submit', [Controllers\User\UserPaymentController::class, 'pay_stripe_submit'])->name('user.payment.stripe.submit');

        //my plan
        Route::get('/my/plan', [Controllers\User\UserSubscriptionController::class, 'my_plan'])->name('user.my.plan');
        Route::post('/my/plan/change', [Controllers\User\UserSubscriptionController::class, 'my_plan_change'])->name('user.plan.change');

        //email download
        Route::get('/email/download', [Controllers\User\UserEmailController::class, 'email_download'])->name('user.email.download');
        Route::get('/email/download/save/{id}', [Controllers\User\UserEmailController::class, 'email_download_save'])->name('user.email.download.save');

        //billing
        Route::get('/billing', [Controllers\User\UserBillingController::class, 'billing'])->name('user.billing');
        Route::get('/add/cart/{plan_id}', [Controllers\User\UserBillingController::class, 'add_cart'])->name('user.add.cart');
        Route::get('/view/cart', [Controllers\User\UserBillingController::class, 'view_cart'])->name('user.view.cart');
        Route::get('/cart/data/remove/{id}', [Controllers\User\UserBillingController::class, 'cart_data_remove'])->name('user.cart.remove');
        Route::get('/user/checkout', [Controllers\User\UserBillingController::class, 'checkout_user'])->name('user.checkout');
        Route::post('/user/checkout/submit', [Controllers\User\UserPaymentController::class, 'checkout_submit'])->name('user.checkout.submit');

        //invoice
        Route::get('/invoice', [Controllers\User\UserBillingController::class, 'user_invoice'])->name('user.invoice');
        Route::get('/invoice/details/{id}', [Controllers\User\UserBillingController::class, 'user_invoice_details'])->name('user.invoice.details');

        //change password
        Route::get('/change/password', [Controllers\User\UserController::class, 'change_password'])->name('user.change.password');
        Route::post('/change/password/save', [Controllers\User\UserController::class, 'change_password_save'])->name('user.change.pass.save');

    });
});
Route::get('/admin/logout', [Controllers\Auth\CustomLoginController::class, 'admin_logout'])->name('admin.logout');

Route::group(['middleware' => ['auth:admin']], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');

        //subscription plan
        Route::get('/subscription/plan', [Controllers\Admin\AdminSubscriptionController::class, 'plan_list'])->name('admin.subscription.plan');
        Route::post('/subscription/plan/save', [Controllers\Admin\AdminSubscriptionController::class, 'plan_save'])->name('admin.subscription.plan.save');
        Route::post('/subscription/plan/update', [Controllers\Admin\AdminSubscriptionController::class, 'plan_update'])->name('admin.subscription.plan.update');
        Route::post('/subscription/plan/delete', [Controllers\Admin\AdminSubscriptionController::class, 'plan_delete'])->name('admin.subscription.plan.delete');

        //credit plan
        Route::get('/credit/plans', [Controllers\Admin\AdminSubscriptionController::class, 'credit_plan'])->name('admin.credit.plan');
        Route::post('/credit/plan/save', [Controllers\Admin\AdminSubscriptionController::class, 'credit_plan_save'])->name('admin.credit.plan.save');
        Route::post('/credit/plan/update', [Controllers\Admin\AdminSubscriptionController::class, 'credit_plan_update'])->name('admin.credit.plan.update');
        Route::post('/credit/plan/delete', [Controllers\Admin\AdminSubscriptionController::class, 'credit_plan_delete'])->name('admin.credit.plan.delete');
        Route::get('/credit/user/list', [Controllers\Admin\AdminSubscriptionController::class, 'credit_user_list'])->name('admin.credit.user.list');

        //user plans
        Route::get('/user/plans', [Controllers\Admin\AdminSubscriptionController::class, 'user_plans'])->name('admin.users.plan');
        Route::post('/user/plan/update', [Controllers\Admin\AdminSubscriptionController::class, 'user_plans_update'])->name('admin.user.plan.update');

        //user custom order
        Route::get('/user/custom/order', [Controllers\Admin\AdminSubscriptionController::class, 'user_custom_order'])->name('admin.custom.order');
        Route::post('/user/custom/order/save', [Controllers\Admin\AdminSubscriptionController::class, 'user_custom_order_save'])->name('admin.custom.order.save');

        //user manage
        Route::get('/user/create', [Controllers\Admin\AdminUserController::class, 'create_user'])->name('admin.create.user');
        Route::post('/user/create/save', [Controllers\Admin\AdminUserController::class, 'create_user_save'])->name('admin.user.save');

        //change password
        Route::get('/change/password', [Controllers\Admin\AdminController::class, 'change_password'])->name('admin.change.password');
        Route::post('/change/password/save', [Controllers\Admin\AdminController::class, 'change_password_save'])->name('admin.change.pass.save');
    });
});
