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
            <strong class="pptitle">{{__('messages.purchase_list')}}</strong>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fas fa-plus-square"></i>{{__('messages.add_new')}}
            </button>
        </div>
        <!-- card-hader -->
        <!-- card-body -->
        <div class="card-body">
            {{-- <iframe src="http://www.onlineicttutor.com/wp-content/uploads/2016/04/pdf-at-iframe.pdf" width="100%" height="300"></iframe>--}}

            <table id="example" class="table table-bordered dt-responsive table-responsive" style="width: 100%;">
                <thead>
                    <tr>
                        <th>{{__('messages.sl_no')}}</th>
                        <th>{{__('messages.product')}}</th>
                        <th>{{__('messages.category')}}</th>
                        <th>{{__('messages.quantity')}}</th>
                        <th>{{__('messages.vendor')}}</th>
                        <th>{{__('messages.date')}}</th>
                        <th>{{__('messages.created_by')}}</th>
                        <th class="action">{{__('messages.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($purchaseList))
                    @foreach ($purchaseList as $purchase)
                    <tr id="purchase-{{ $purchase->id }}">
                        <td class="sl_no"><b class="serial"> {{ $loop->iteration }}</b></td>
                        <td>{{ !empty($purchase->item_id) ? $purchase->item->name : '' }}</td>
                        <td>{{ !empty($purchase->category_id ) ? $purchase->category->name : '' }}</td>
                        <td>{{ !empty($purchase->qty) ? $purchase->qty : '' }}</td>
                        <td>{{ !empty($purchase->vendor_id) ? $purchase->vendor->name : $purchase->other_vendor }}</td>
                        <td>{{ !empty($purchase->purchase_date) ? $purchase->purchase_date : '' }}</td>
                        <td>{{ !empty($purchase->created_by) ? $purchase->item->createdBy->name : '' }}</td>
                        <td class="action">
                            <button class="btn btn-primary view-purchase" data-id="{{ $purchase->id }}" data-toggle="modal" data-target="#view_purchase_modal"><i class="fas fa-eye"></i></button>
                            {{-- <button class="btn btn-info edit-purchase" data-id="{{ $purchase->id }}"
                            data-toggle="modal"
                            data-target="#edit_template_modal"><i class="fas fa-edit"></i></button> --}}
                            <button class="btn btn-danger delete-purchase" data-id="{{ $purchase->id }}" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- item view model start here --}}
<div class="modal fade" id="view_purchase_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div id="purchase_view_form">

                <!-- Modal Header -->
                <div class="modal-header justify-content-between" style="background: #579eb9; color: #fff;">
                    <legend class="modal-title text-center"><i class="fab fa-wpforms"></i> &nbsp;{{__('messages.view_purchase')}}
                    </legend>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">

                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="Category_Name">{{__('messages.category_name')}}: </label>
                        </div>
                        <div class="col-md-7">

                            {{-- <p>{{$category}}</p>--}}
                            <p id="view_category" style="color: black"></p>
                        </div>
                    </div>

                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="measurement_unit">{{__('messages.vendor')}} </label>
                        </div>
                        <div class="col-md-7">
                            <p id="view_vendor" style="color: black"></p>
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="item_Name">{{__('messages.product')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <p id="view_product" style="color: black"></p>
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="impa_code">{{__('messages.quantity')}} </label>
                        </div>
                        <div class="col-md-7">
                            <p id="view_quantity" style="color: black"></p>
                        </div>
                    </div>


                    <div class="row justify-content-center form-group">
                        <div class="col-md-12">
                            <label for="measurement_unit" class="col-md-12 text-center">{{__('messages.purchase_doc')}} </label>
                            <iframe src="" id="view_document" width="100%" height="300"></iframe>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>


{{-- item view model end here --}}
<!-- item Add Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="purchase_add_form" class="form">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header justify-content-between" style="background: #579eb9; color: #fff;">
                    <legend class="modal-title text-center"><i class="fab fa-wpforms"></i> &nbsp; {{__('messages.add_new_form')}}
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
                            <label for="Category_Name">{{__('messages.category_name')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control Category_Name" name="category_Name">
                                <option selected="" value="" class='cat_pur' hidden>{{__('messages.choose_category')}}</option>
                                @if (!empty($categories))
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" class='cat_pur'>{{ $category->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="Category_Name">{{__('messages.sub_category')}}</label>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control" id="subCatId" name="sub_cat_name">
                                <option selected="selected" value="">{{__('messages.select_cat_first')}}
                                </option>

                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="measurement_unit">{{__('messages.vendor')}} </label>
                        </div>
                        <div class="col-md-5">
                            <select class="form-control vendor" name="vendor">
                                <option selected="" value="" hidden>{{__('messages.select_here')}}</option>
                                @if (!empty($vendors))
                                @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style="width: 100%;">
                                {{__('messages.others')}}
                            </button>
                        </div>

                    </div>
                    <div class="row justify-content-center form-group collapse" id="collapseExample">
                        <div class="col-md-3">
                            <label for="other_field">{{__('messages.others_field')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control other_field" name="other_field">
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="item_Name">{{__('messages.product')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control product_Name" name="product_Name" id="product_id">
                                <option selected="" value="" hidden>{{__('messages.select_cat_first')}}</option>

                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="impa_code">{{__('messages.quantity')}} </label>
                        </div>
                        <div class="col-md-7">
                            <input type="number" class="form-control impa_code" name="quantity">
                        </div>
                    </div>

                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="measurement_unit">{{__('messages.date')}} </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control date" value="<?= date("Y-m-d"); ?>" name="purchase_date" placeholder="Purchase Date">
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="measurement_unit">{{__('messages.purchase_doc')}} </label>
                        </div>
                        <div class="col-md-7">
                            <input type="file" accept="" class="purchase_doc form-control" id="purchase_doc" name="purchase_document">
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i> {{__('messages.cancel')}}
                    </button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i> {{__('messages.confirm_add')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit purchase Template Modal -->
<div class="modal fade" id="edit_template_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #579eb9; padding: 10px 0;">
                <legend style="color:#fff; text-align: center; margin-bottom:0;"><i class="far fa-edit"></i> &nbsp;
                    {{__('messages.update')}}
                </legend>
                <button style="color: #fff;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
                </button>
            </div>
            <form id="purchase_edit_form" class="form">
                @csrf
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
                            <label for="Category_Name">{{__('messages.category_name')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control category_Name" name="category_Name">
                                <option selected="" value="" class='cat_pur'>{{__('messages.choose_category')}}</option>
                                @if(!empty($categories))
                                @foreach($categories as $category)
                                <option value="{{$category->id}}" class='cat_pur'>{{$category->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="Category_Name">{{__('messages.sub_category')}}</label>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control" id="subCatId" name="sub_cat_name">
                                <option selected="selected" value="">{{__('messages.select_cat_first')}}
                                </option>

                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="measurement_unit">{{__('messages.vendor')}} </label>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control vendor" name="vendor">
                                <option selected="" value="" class='van_pur'>{{__('messages.select_here')}}</option>
                                @if (!empty($vendors))
                                @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}" class='van_pur'>{{ $vendor->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="item_Name">{{__('messages.product')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control product_Name" name="product_Name">
                                <option selected="" value="" class='pro_opt'>{{__('messages.select_here')}}</option>
                                @if (!empty($products))
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}" class='pro_opt'>{{ $product->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="quantity">{{__('messages.quantity')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control quantity" name="quantity">
                            <input type="hidden" class="form-control purchase_id" name="purchase_id">
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="purchase_date">{{__('messages.date')}} </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control date purchase_date" name="purchase_date" placeholder="Purchase Date">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('messages.cancel')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('messages.update')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

</script>
@endsection