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

                  {{__('messages.stock_report')}}
                </strong>

                <div class="filter_form">
                    <form id="stock_search_form" class="form form-inline" method="post" action="{{url('/search/stock')}}">
                        @csrf

                        <br class="filter_form_br">
                        <div class="form-group">
                            <input type="text" class="form-control date" style="max-width: 141px;" value=""  id="filter_From" name="from_date" placeholder="From Date" required>
                        </div>
                        <div class="form-group">
                            <input type="text" value="" style="max-width: 141px;" class="form-control date" id="filter_To" name="end_date" placeholder="To Date" required>
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

                    <th>{{__('messages.product')}}</th>

                    <th>{{__('messages.quantity')}}</th>

                    </thead>
                    <tbody id="report_stock">

                    @foreach($stocks as $stock)

                            <tr>
                                <td>{{$stock->item->name}}</td>
                                <td>{{$stock->in_stock}}</td>
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
