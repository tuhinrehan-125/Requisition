@extends('layouts.admin-master')
@section('main-content')
<div class="col-lg-6 col-xl-12">
    <div class="card">
        <div class="card-header pv-card-hader">
            <strong class="pptitle">{{__('messages.current_stock_lists')}}</strong>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"> <i class="fas fa-plus-square"></i>{{__('messages.add_opening_stock')}}</button>
        </div>
        <!-- card-hader -->
        <!-- card-body -->
        <div class="card-body">
            <table id="example" class="table table-bordered dt-responsive table-responsive" style="width: 100%;">
                <thead>
                    <tr>
                        <th>{{__('messages.sl_no')}}</th>
                        <th>{{__('messages.product')}}</th>
                        <th>{{__('messages.opening_stock')}}</th>
                        <th>{{__('messages.stock_available')}}</th>
                        {{-- <th class="action">Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($stocks))
                    @foreach ($stocks as $stock)
                    <tr id="purchase-{{ $stock->id }}">
                        <td class="sl_no"> <b class="serial"> {{ $loop->iteration }}</b> </td>
                        <td>{{ !empty($stock->product_id) ? $stock->item->name : '' }}</td>
                        <td>{{ $stock->opening_stock }}</td>
                        <td>{{ $stock->in_stock  }}</td>

                        {{-- <td class="action">
                                    <button class="btn btn-info edit-purchase" data-id="{{ $purchase->id }}"
                        data-toggle="modal"
                        data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger delete-purchase" data-id="{{ $purchase->id }}" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>
                        </td> --}}
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- item Add Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="openingstock_add_form" class="form">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header justify-content-between" style="background: #579eb9; color: #fff;">
                    <legend class="modal-title text-center"><i class="fab fa-wpforms"></i> &nbsp; Add Opening Stock
                    </legend>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row justify-content-center form-group">
                        <div class="col-md-11 alert alert-danger alert-dismissible fade show form_error" style="display:none" role="alert">
                            <strong>Error Submission!!</strong> Please correct following info and resubmit.
                            <label> </label>
                            <button type="button" class="close close_error_alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="item_Name">{{__('messages.product_name')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control product" name="product">
                                <option selected="" value="" hidden>{{__('messages.choose_item')}}</option>
                                @if (!empty($products))
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="impa_code">{{__('messages.quantity')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <input type="number" class="form-control impa_code" name="quantity">
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="far fa-window-close"></i> {{__('messages.cancel')}}</button>
                        <button type="submit" class="btn btn-primary"> <i class="fas fa-check-square"></i> {{__('messages.confirm_add')}}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection