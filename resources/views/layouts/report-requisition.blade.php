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

    @media print {
        .pptitle {
            display: none !important;
            visibility: hidden !important;
        }
    }
</style>
<div class="col-lg-12 col-xl-12">
    <div class="card">
        <div class="card-header pv-card-hader">
            <strong class="pptitle">

                {{__('messages.report_requisition')}}

            </strong>

            <div class="filter_form">
                <form id="requisition_search_form" class="form form-inline" method="post" action="{{url('/search/requisition')}}">
                    @csrf

                    <div class="form-group">
                        <select name="status_name" class="form-control" id="status_id" style="max-width: 141px;" required>
                            <option value="" selected="">{{__('messages.select_here')}}</option>
                            <option value="1">{{__('messages.approved_requisition')}}</option>
                            <option value="2">{{__('messages.pending_requisition')}}</option>
                            <option value="3">{{__('messages.rejected_requisition')}}</option>
                        </select>
                    </div>
                    <br class="filter_form_br">
                    <div class="form-group">
                        <input type="text" class="form-control date" style="max-width: 141px;" value="" name="from_date" placeholder="From Date" required>
                    </div>
                    <div class="form-group">
                        <input type="text" value="" style="max-width: 141px;" class="form-control date" name="end_date" placeholder="To Date" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info bsc-search">{{__('messages.search')}}</button>
                    </div>
                </form>
            </div>


            <div class="right-buttons">
                <button class="btn btn-info btn-bvprint" onClick="order_list();">
                    <i class="fa fa-print"></i> {{__('messages.print')}}
                </button>
            </div>

        </div>

        <!-- card-hader -->
        <!-- card-body -->
        <div class="card-body">
            <table id="example" class="table table-bordered dt-responsive table-responsive" style="width: 100%;">
                <thead>

                    <th>{{__('messages.req_no')}}</th>
                    <th>{{__('messages.department')}}</th>
                    <th>{{__('messages.req_date')}}</th>
                    <th>{{__('messages.status')}}</th>
                    <th>{{__('messages.created_by')}}</th>
                </thead>
                <tbody id="report_requisition">
                    @if(!empty($lists))
                    @foreach($lists as $list)
                    <tr id="{{$list->id}}">
                        <td>{{$list->req_no}}</td>
                        <td>{{$list->dept->name}}</td>
                        <td>{{$list->req_date}}</td>
                        <td>{{$list->status}}</td>
                        <td>{{$list->createdBy->name}}</td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit order Template Modal -->
<!-- print header -->
<div id="order-print-header1" class="print-header">
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