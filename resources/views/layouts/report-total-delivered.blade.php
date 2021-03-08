@extends('layouts.admin-master')
@section('main-content')
    <style>
        .item_name_wrapper {
            position: relative;
        }

        .item_name_wrapper img.field-loader {
            position: absolute;
            width: 25px;
            height: auto;
            right: 3px;
            display: none;
            top: 50%;
            margin-top: -12.5px;
            padding: 0;
        }
    </style>

    <div class="col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header pv-card-hader">
                <strong class="pptitle">

                  {{__('messages.deliver_report')}}
                </strong>

                 <div class="filter_form">
                    <form id="total_delivered_search_form" class="form form-inline" method="post" action="{{url('/search/total-delivered')}}">
                        @csrf

                        <br class="filter_form_br">
                        <div class="form-group">
                            <input type="text" value="" class="form-control date" style="max-width: 141px;"  name="from_date" placeholder="From Date" required />
                        </div>
                        <div class="form-group">
                            <input type="text" value="" style="max-width: 141px;" class="form-control date" name="end_date" placeholder="To Date" required />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-info bsc-search">{{__('messages.search')}}</button>

                        </div>
                    </form>
                </div>


                <div class="right-buttons">
                    <button class="btn btn-info btn-bvprint" onClick="order_list();">
                        <i class="fa fa-print"></i>  {{__('messages.print')}}
                    </button>
                </div>

            </div>

            <!-- card-hader -->
            <!-- card-body -->
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive" style="width: 100%;">
                    <thead>
                    <th>{{__('messages.username')}}</th>
                    <th>{{__('messages.user')}}</th>
                    <th>{{__('messages.department')}}</th>
                    <th>{{__('messages.product')}}</th>
                    <th>{{__('messages.report_delivered_qty')}}</th>

                    </thead>
                    <tbody id="report_total_delivered_id">

                    @foreach($delivered_lists as $list)
                            <tr id="report_stock-{{$list->id}}">
                                <td>{{$list->order->createdBy->username}}</td>
                                <td>{{$list->order->createdBy->name}}</td>
                                <td>{{$list->order->dept->name}}</td>
                                <td>{{$list->item->name}}</td>
                                <td>{{$list->delivered_qty}}</td>
                            </tr>
                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- print header -->
    <div id="order-print-header1" class="print-header" >
        <div class="title-wrap od-title">
            <div class="row mb-3 justify-content-center">
                <div class="col-3 irdheader">
                    <img src="{{url('/images/bd govt.png')}}" width="50px" height="50px">
                    <p>অভ্যন্তরীণ সম্পদ বিভাগ</p>
                    <p>অর্থ মন্ত্রণালয়</p>
                </div>
            </div>
        </div>
  </div>
  <!-- ./print header -->
@endsection
