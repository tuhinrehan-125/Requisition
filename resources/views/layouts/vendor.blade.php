@extends('layouts.admin-master')
@section('main-content')
<div class="col-lg-6 col-xl-12">
    <div class="card">
        <div class="card-header pv-card-hader">
            <strong class="pptitle">{{__('messages.vendor_list')}}</strong>

            <div class="right-buttons">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"> <i class="fas fa-plus-square"></i> {{__('messages.add_new')}} </button>
                <button class="btn btn-info btn-bvprint" onClick="print_this();"><i class="fa fa-print"></i> {{__('messages.print')}}</button>
            </div>
        </div>
        <!-- card-hader -->
        <!-- card-body -->
        <div class="card-body">
            <table id="example" class="table table-bordered dt-responsive" style="width: 100%;">
                <thead>
                    <th>{{__('messages.sl_no')}}</th>
                    <th>{{__('messages.vendor_name')}}</th>
                    <th>{{__('messages.phone_number')}}</th>
                    <th>{{__('messages.email')}}</th>
                    <th>{{__('messages.address')}}</th>
                    <th>{{__('messages.contact_person')}}</th>
                    <th>{{__('messages.created_by')}}</th>
                    <th class="action">{{__('messages.action')}}</th>
                </thead>

                <tbody>
                    @if(!empty($vendors))
                    @foreach($vendors as $vendor)
                    <tr id="vendor-{{$vendor->id}}">
                        <td class="sl_no"> <b class="serial"> {{$loop->iteration}}</b> </td>
                        <td>{{!empty($vendor->name)?$vendor->name:''}}</td>
                        <td>{{!empty($vendor->phone_no)?$vendor->phone_no:''}}</td>
                        <td>{{!empty($vendor->email)?$vendor->email:''}}</td>
                        <td>{{!empty($vendor->address)?$vendor->address:''}}</td>
                        <td>{{!empty($vendor->contact_person)?$vendor->contact_person:''}}</td>

                        <td>{{!empty($vendor->created_by)?$vendor->user->name:''}}</td>
                        <td class="action">
                            <button class="btn btn-info edit-vendor" data-id="{{$vendor->id}}" data-name="{{$vendor->name}}" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger delete-vendor" data-id="{{$vendor->id}}" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Vendor Add Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="vendor_add_form" class="form">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header justify-content-between" style="background: #579eb9; color: #fff;">
                    <legend class="modal-title text-center"><i class="fab fa-wpforms"></i> &nbsp; {{__('messages.add_new_vendor_form')}}</legend>
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
                            <label for="name">{{__('messages.vendor_name')}}:</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control name" name="name">
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="phone_no">{{__('messages.phone_number')}}:</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control phone_no" name="phone_no">
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="email">{{__('messages.email')}}:</label>
                        </div>
                        <div class="col-md-7">
                            <input type="email" class="form-control email" name="email">
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="address">{{__('messages.address')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control address" name="address">
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="contact_person">{{__('messages.contact_person')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control contact_person" name="contact_person">
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="far fa-window-close"></i> {{__('messages.cancel')}}</button>
                    <button type="submit" class="btn btn-primary"> <i class="fas fa-check-square"></i> {{__('messages.confirm_add')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Vendor Template Modal -->
<div class="modal fade" id="edit_template_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #579eb9; padding: 10px 0;">
                <legend style="color:#fff; text-align: center; margin-bottom:0;"><i class="far fa-edit"></i> &nbsp; {{__('messages.update')}} </legend>
                <button style="color: #fff;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
                </button>
            </div>
            <form id="vendor_edit_form" class="form">
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
                            <label for="name">{{__('messages.vendor_name')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control name" name="name">
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="phone_no">{{__('messages.phone_number')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control phone_no" name="phone_no">
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="email">{{__('messages.email_address')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <input type="email" class="form-control email" name="email">
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="address">{{__('messages.address')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control address" name="address">
                        </div>
                    </div>
                    <div class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="contact_person">{{__('messages.contact_person')}}: </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control contact_person" name="contact_person">
                        </div>
                    </div>

                </div>
                <input type="hidden" name="vendor_id" class="vendor_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('messages.cancel')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('messages.confirm_add')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- logo-base64 for pdf page -->
@include('pdf.logo-base64')
<!-- logo-base64 for pdf page -->
@endsection