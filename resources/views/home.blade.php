@extends('layouts.admin-master')
@section('main-content')
@php
$auth=auth()->user()->role->role;
@endphp
<div class="row" style="width: 100%">
<div class="col-lg-3 col-md-6">
    <div class="card counter">
        <div class="card-body">
            @if ( $auth=='dept-user')
            <a href="{{url('/my-stock')}}">
                @elseif($auth=='super-admin'|| $auth=='stock-officer')
                <a href="{{url('/stock')}}">
                    @else
                    @endif
                    <div class="stat-widget-five">
                        <div class="stat-icon dib flat-color-1">
                            <i class="fa fa-database"></i>
                        </div>
                        <div class="stat-content">
                            <div class="text-left dib">
                                @if($auth=='dept-user' )
                                <div class="stat-text"><span class="count positive">{{$number_of_stocks->sum('qty')}}</span></div>
                                <div class="stat-heading">{{__('messages.my_stock') }}</div>
                                @else
                                <div class="stat-text"><span class="count positive">{{$number_of_stocks->sum('in_stock')}}</span></div>
                                <div class="stat-heading">{{__('messages.stock') }}</div>
                                @endif
                               
                            </div>
                        </div>
                    </div>
                </a>
            </a>
        </div>
    </div>
</div>
@if ($auth=='super-admin' || $auth=='stock-officer')
<div class="col-lg-3 col-md-6">
    <div class="card counter">
        <div class="card-body">
            <a href="{{url('/purchase')}}">
                <div class="stat-widget-five">
                    <div class="stat-icon dib flat-color-2">
                        <i class="fa fa-cart-arrow-down"></i>
                    </div>
                    <div class="stat-content">
                        <div class="text-left dib">
                            <div class="stat-text"><span class="count negative">{{$number_of_purchase->sum('qty')}}</span></div>
                            <div class="stat-heading">{{__('messages.total_purchase') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-6">
    <div class="card counter">
        <div class="card-body">
            <a href="{{url('/purchase')}}">
                <div class="stat-widget-five">
                    <div class="stat-icon dib flat-color-6">
                        <i class="fa fa-sign-out-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="text-left dib">
                            <div class="stat-text"><span class="count negative">{{$number_of_deliver_item}}</span></div>
                            <div class="stat-heading">{{__('messages.total_delivered') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endif
@if ($auth=='dept-user'  ||  $auth=='super-admin' || $auth=='stock-officer')
<div class="col-lg-3 col-md-6">
    <div class="card counter">
        <div class="card-body">
            <a href="{{url('/category')}}">
                <div class="stat-widget-five">
                    <div class="stat-icon dib flat-color-4" style="color: #d6bd0c">
                        <i class="fa fa-list"></i>
                    </div>
                    <div class="stat-content">
                        <div class="text-left dib">
                            <div class="stat-text">
                                {{-- <span class="count pending">{{\App\Category::where('status',true)->get()->count()}}--}}
                                <span class="count pending">{{$number_of_categories}}
                                </span>
                            </div>
                            <div class="stat-heading">{{__('messages.category') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endif
@if ($auth!=='dept-user')
<div class="col-lg-3 col-md-6">
    <div class="card counter">
        <div class="card-body">
            @if ($auth=='super-admin' || $auth=='stock-officer')
            <a href="{{url('/product')}}">
            @else
            @endif
                <div class="stat-widget-five">
                    <div class="stat-icon dib flat-color-4" style="color: #1f961b">
                        <i class="fa fa-cubes"></i>
                    </div>
                    <div class="stat-content">
                        <div class="text-left dib">
                            <div class="stat-text">
                                <span class="count pending">{{$number_of_products}}
                                </span>
                            </div>
                            <div class="stat-heading">{{__('messages.product') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endif
@if ( $auth=='super-admin' || $auth=='stock-officer')
<div class="col-lg-3 col-md-6">
    <div class="card counter">
        <div class="card-body">
            <a href="{{url('/vendor')}}">
                <div class="stat-widget-five">
                    <div class="stat-icon" style="color: #d64c0c">
                        <i class="fa fa-address-book"></i>
                    </div>
                    <div class="stat-content">
                        <div class="text-left dib">
                            <div class="stat-text">
                                <span class="count pending">{{\App\Vendor::count()}}
                            </div>
                            <div class="stat-heading">{{__('messages.vendor') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endif
<div class="col-lg-3 col-md-6">
    <div class="card counter">
        <div class="card-body">
            <a href="{{url('/approved/requisition')}}">
                <div class="stat-widget-five">
                    <div class="stat-icon dib flat-color-3" style="color: #0fc75b">
                        <i class="fa fa-check-square"></i>
                    </div>
                    <div class="stat-content">
                        <div class="text-left dib">
                            <div class="stat-text">
                                <span class="count pending text-center">{{$approved_requisition->count()}}
                                </span>
                            </div>
                            {{--<div class="stat-heading">Vendor</div>--}}
                            <div class="stat-heading" style="font-size: 17px">{{__('messages.approved_requisition') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>


<div class="col-lg-3 col-md-6">
    <div class="card counter">
        <div class="card-body">
            <a href="{{url('/pending/requisition')}}">
                <div class="stat-widget-five">
                    <div class="stat-icon dib flat-color-4" style="color: #6e66bb">
                        <i class="fa fa-stopwatch"></i>
                    </div>
                    <div class="stat-content">
                        <div class="text-left dib">
                            <div class="stat-text">
                                <span class="count pending">{{ $pending_requistion->count() }}
                                </span>
                            </div>
                            <div class="stat-heading">{{__('messages.pending_requisition') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="card counter">
        <div class="card-body">
            <a href="{{url('/reject/requisition')}}">
                <div class="stat-widget-five">
                    <div class="stat-icon dib flat-color-4" style="color: #ec0b0b">
                        <i class="fa fa-times-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="text-left dib">
                            <div class="stat-text"><span class="count">
                                    {{$rejected_requisition}}
                                </span></div>
                            <div class="stat-heading">{{__('messages.rejected_requisition') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
</div>

<div class="row dashboard">
    <div class="col-12 col-md-6">
        <p class="text-center">{{__('messages.latest_stock') }}</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{__('messages.product') }}</th>
                    <th>{{__('messages.quantity') }}</th>
                </tr>
            </thead>
            <tbody>
                @if($auth=='dept-user' )
                @forelse($number_of_stocks->take(4)->get() as $stocks)
                <tr>
                    <td>{{ $stocks->product->name }}</td>
                    <td>{{ $stocks->qty }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No data available</td>
                </tr>
                @endforelse
                @else
                @forelse($number_of_stocks->take(4)->get() as $stocks)
                <tr>
                    <td>{{ $stocks->item->name }}</td>
                    <td>{{ $stocks->in_stock }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No data available</td>
                  </tr>
                @endforelse

                @endif
            </tbody>
        </table>
    </div>
    @if($auth!=='dept-user' )
    <div class="col-12 col-md-6">
        <p class="text-center">{{__('messages.latest_purchase')}}</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{__('messages.product')}}</th>
                    <th>{{__('messages.quantity')}}</th>
                    <th>{{__('messages.req_date')}}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($number_of_purchase->take(4)->get() as $purchase)
                <tr>
                    <td>{{ $purchase->item->name }}</td>
                    <td>{{ $purchase->qty }}</td>
                    <td>{{ $purchase->purchase_date }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No data available</td>
                  </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
    <div class="col-12 col-md-6">
        <p class="text-center">{{__('messages.latest_approved_requisition')}}</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{__('messages.department')}}t</th>
                    <th>{{__('messages.req_no')}}</th>
                    <th>{{__('messages.req_date')}}</th>
                    <th>{{__('messages.status')}}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($approved_requisition->take(4)->get() as $approved)
                <tr>
                    <td>{{ $approved->dept->name }}</td>
                    <td>{{ $approved->req_no}}</td>
                    <td>{{ $approved->req_date }}</td>
                    <td>{{ $approved->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No data available</td>
                  </tr>
                @endforelse

            </tbody>
        </table>
    </div>
    <div class="col-12 col-md-6">
        <p class="text-center">{{__('messages.latest_pending_requisition')}}</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{__('messages.department')}}</th>
                    <th>{{__('messages.req_no')}}</th>
                    <th>{{__('messages.req_date')}}</th>
                    <th>{{__('messages.status')}}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pending_requistion->take(4)->get() as $pending)
                <tr>
                    <td>{{ $pending->dept->name }}</td>
                    <td>{{ $pending->req_no}}</td>
                    <td>{{ $pending->req_date }}</td>
                    <td>{{ $pending->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No data available</td>
                  </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>



<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="summary-table-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <b></b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body" id="summary-table-wrapper">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('home-js')
<script type="text/javascript" src="{{ asset('assets/js/Chart.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/utils.js') }}"></script>
<script>
    (function($) {
        "use strict";

        //line
        // var ctxL = document.getElementById("lineChart").getContext('2d');
        // var myLineChart = new Chart(ctxL, {
        //     type: 'line',
        //     data: {
        //         labels: ["January", "February", "March", "April", "May", "June", "July"],
        //         datasets: [{
        //           label: "Received Requisitions",
        //           data: [65, 59, 80, 81, 56, 55, 40],
        //           backgroundColor: [
        //           'rgba(105, 0, 132, .2)',
        //           ],
        //           borderColor: [
        //           'rgba(200, 99, 132, .7)',
        //           ],
        //           borderWidth: 2
        //         },
        //         {
        //           label: "Approved Requisitions",
        //           data: [28, 48, 40, 19, 86, 27, 90],
        //           backgroundColor: [
        //           'rgba(0, 137, 132, .2)',
        //           ],
        //           borderColor: [
        //           'rgba(0, 10, 130, .7)',
        //           ],
        //           borderWidth: 2
        //         }
        //       ]
        //     },
        //     options: {
        //         responsive: true
        //     }
        // });


        $('.bsc-zoom').on('click', function() {
            var header = $(this).parents('.card-header').find('.pptitle').html();
            var content = $(this).parents('.card').find('.card-body').html();

            $('#summary-table-modal').find('.modal-title').html(header);
            $('#summary-table-modal').find('#summary-table-wrapper').html(content);
        })


    })(jQuery);
</script>
@endsection
