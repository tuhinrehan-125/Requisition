@extends('layouts.admin-master')
@section('main-content')
<style>
    .written-sign img {
        max-height: 80px;
        max-width: 300px;
    }
</style>
@php
$userrole=auth()->user()->role->role;

@endphp
<div class="order-section container">
    <div class="row">
        <div class="col-xl-12">
            <div class="card order-card">
                <div class="card-header first">
                    <strong class="pptitle">

                        {{__('messages.received_product_details')}}

                    </strong>
                    <button class="btn btn-info btn-bvprint print-order-details"><i class="fa fa-print"></i> {{__('messages.print')}}</button>

                </div>
                <div class="card-body">
                    @if(($userrole=='dept-user'))
                    <form class="form mb-3 orderDetailForm" id="deliveredQtyForm">
                        @csrf
                        @endif
                        <table id="example" class="table table-striped table-bordered orderedItemTable OrderDetailsTable table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{__('messages.item_no')}}</th>
                                    <th>{{__('messages.item_name')}}</th>
                                    <th>{{__('messages.unit')}}</th>
                                    <th>{{__('messages.received_qty')}} </th>
                                    <th>{{__('messages.received_date')}} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($orders))
                                @foreach($orders as $index=>$orderr)
                                <tr>
                                    <td><b class="serial">{{$loop->iteration}}</b></td>
                                    <td class="item-name-td">{{$orderr->name}}</td>
                                    <td class="item-unit">{{$orderr->unit}}</td>
                                    <td>
                                        {{!empty($orderr->received_qty)?$orderr->received_qty:''}}
                                    </td>
                                    <td>{{$orderr->updated_at->format("d-m-Y")}}</td>
                                </tr>

                                @endforeach
                                @endif
                            </tbody>
                        </table>


                </div>
            </div>
        </div>
    </div>
</div>


<!-- print header -->
<div id="order-print-header1" class="print-header">
    <div class="title-wrap od-title">
        <div class="logo">
            <a href="{{url('/')}}"><img src="{{asset('/images/bd govt.png')}}" alt="Site Logo"></a>
        </div>
        <div class="title-center">
            <h2 class="line1">SDD/SMM/Receipt Note/Ship's Copy</h2>
            <h2 class="line2">Bangladesh Shipping Corporation</h2>
            <h2 class="line3">Ship <span></span> Repair <span></span> Department</h2>
            <h3 class="line4x"><span class="req_cat"></span> Requitition</h3>
        </div>
        <div class="title-right">
        </div>
    </div>
</div>
<div id="order-print-header3" class="print-header">
    <table class="office-use-table table-responsive">

        <body>
            <tr>
                <td colspan="2" class="office_use">Office Use</td>
                <td colspan="3" class="office_use_form">
                    <p>1 Checked by <span class="checked_by"></span> Date <span class="date"></span> Passed to SSM Dept. on <span class="passed"></span></p>
                    <p>2 Invitation to Tender sent on <span class="invitation"></span> Tenders received on <span class="tender_rdate"></span></p>
                    <p>3 Order approved on <span class="approved_date"></span> Supply order issued on <span class="soi_date"></span></p>
                    <p>4 Delivered on board on <span class="delevered_obdate"> </span> Delivery complete/incomplete <span class="dci_date"></span></p>
                    <p>5 Bill received on <span class="bil_rdate"></span> Put up for approval on <span class="pua_date"></span> passed for payment on <span class="pfp_date"></span></p>
                </td>
            </tr>
        </body>
    </table>
</div>
<!-- ./print header -->
@endsection