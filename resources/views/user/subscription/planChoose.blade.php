@extends('layouts.user')
@section('user')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Subscription Plan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="row">
        <div class="card-body table-responsive p-0" style="height: 300px;">
            <table class="table table-head-fixed text-nowrap">
                <thead>

                </thead>
                <tbody>
                    <tr>
                        <td><strong>Plan Name</strong> : {{$plan->plan_name}}</td>
                    </tr>
                    <tr>
                        <td><strong>Plan Amount</strong> : ${{number_format($plan->plan_amount)}}</td>
                    </tr>
                    <tr>
                        <td><strong>Plan Description</strong> : {{$plan->plan_description}}</td>
                    </tr>

                    <tr>
                        <td>
                            <a href="{{route('user.payment.stripe')}}"><button class="btn btn-primary btn-sm">Pay By Stripe</button></a>
                            <button class="btn btn-primary btn-sm">Pay By Paypal</button>
                        </td>

                    </tr>

                </tbody>
            </table>
        </div>



    </div>
@endsection
