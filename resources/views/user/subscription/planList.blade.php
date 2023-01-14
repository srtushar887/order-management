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
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fixed Header Table</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                        <tr>
                            <th>Plan Name</th>
                            <th>Plan Amount</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($plans as $plan)
                            <tr>
                                <td>{{$plan->plan_name}}</td>
                                <td>${{number_format($plan->plan_amount,2)}}</td>
                                <td>
                                    @if($plan->plan_status == 0)
                                        Active
                                    @elseif($plan->plan_status == 1)
                                        InActive
                                    @else
                                        Not Set
                                    @endif
                                </td>
                                <td>{{\Carbon\Carbon::parse($plan->created_date)->format('Y-m-d')}}</td>
                                <td>
                                    <a href="{{route('user.choose.plan',$plan->id)}}">
                                        <button class="btn btn-primary btn-sm" ><i class="fas fa-shopping-cart"></i> </button>
                                    </a>
                                </td>
                            </tr>





                        @endforeach

                        </tbody>
                    </table>
                </div>
                {{$plans->links()}}
            </div>

        </div>
    </div>



@endsection
