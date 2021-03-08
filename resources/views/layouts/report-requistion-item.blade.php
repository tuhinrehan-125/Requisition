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
    <div class="col-lg-6 col-xl-12">
        <div class="card">
            <div class="card-header pv-card-hader">

                <div class="filter_form">
                    <form id="order_search_form" class="form form-inline" method="post" action="{{url('/search/requisition')}}">
                        @csrf
                        <div class="form-group">
                            <select name="status_name" class="form-control"  id="status_id">
                                <option value="" selected="">Select Status</option>
                                <option value="1" >Active Requisition</option>
                                <option value="2" >Pending Requisition</option>
                                {{--                                @if(!empty($categories))--}}
                                {{--                                @foreach($categories as $cat)--}}
                                {{--                                <option value="{{$cat->id}}" {{(!empty($cat_id)&&$cat->id==$cat_id)?'selected':''}}>{{$cat->name}}</option>--}}
                                {{--                                @endforeach--}}
                                {{--                                @endif--}}
                            </select>
                        </div>

                        <br class="filter_form_br">
                        <div class="form-group">
                            <input type="text" class="form-control date" value="{{!empty($from_date)?$from_date:''}}" name="from_date" placeholder="From Date">
                        </div>
                        <div class="form-group">
                            <input type="text" value="{{!empty($end_date)?$end_date:''}}" class="form-control date" name="end_date" placeholder="To Date">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-info bsc-search">Search</button>
                        </div>
                    </form>
                </div>


                <div class="right-buttons">
                    <button class="btn btn-info btn-bvprint" onClick="order_list();">
                        <i class="fa fa-print"></i>  Print
                    </button>
                </div>

            </div>
            <!-- card-hader -->
            <!-- card-body -->
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive" style="width: 100%;">
                    <thead>
                    <th>#</th>
                    <th>Req. No</th>
                    <th>Category</th>
                    <th>Department</th>
                    <th>Req. Date</th>
                    <th>status</th>
                    <th>Created By</th>
                    <!-- <th class="action">Action</th> -->
                    </thead>
                    <tbody>
                    @if(!empty($orders))
                        @foreach($orders as $order)
                            <tr id="order-{{$order->id}}">
                                <td class="sl_no"> <b class="serial"> {{$loop->iteration}}</b> </td>
                                <td>
                                    <a href="{{url('/order/detail/'.$order->id)}}" class="req_no_link">
                                        {{!empty($order->req_no)?$order->req_no:''}}
                                    </a>
                                </td>
                                <td>{{!empty($order->category->name)?$order->category->name:''}}</td>
                                <td>{{!empty($order->dept->name)?$order->dept->name:''}}</td>
                                <td>{{!empty($order->req_date)?$order->req_date:''}}</td>
                                <td>{{!empty($order->status)?$order->status:''}}</td>
                                <td>{{!empty($order->created_by)?$order->created_by:''}}</td>
                            <!-- <td class="action">
							<button class="btn btn-info edit-order" data-id="{{$order->id}}" data-name="{{$order->name}}" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>
							<button class="btn btn-danger delete-order" data-id="{{$order->id}}" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>
						</td> -->
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit order Template Modal -->

    <!-- logo-base64 for pdf page -->
    @include('pdf.logo-base64')
    <!-- logo-base64 for pdf page -->
@endsection
