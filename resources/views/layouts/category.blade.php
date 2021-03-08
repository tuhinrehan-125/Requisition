@extends('layouts.admin-master')
@section('main-content')
<div class="col-lg-6 col-xl-12">
    <div class="card">
        <div class="card-header pv-card-hader">
            <strong class="pptitle">{{__('messages.category_lists')}}</strong>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fas fa-plus-square"></i> {{__('messages.add')}}
            </button>
        </div>
        <!-- card-hader -->
        <!-- card-body -->
        <div class="card-body">
            <table id="example" class="table table-bordered dt-responsive" style="width: 100%;">
                <thead>
                    <tr>
                        <th>{{__('messages.sl_no')}}</th>
                        <th>{{__('messages.category')}}</th>
                        <th>{{__('messages.sub_category')}}</th>
                        <th class="action">{{__('messages.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($categories))
                    @foreach($categories as $category)
                    <tr id="category-{{$category->id}}">
                        <td class="sl_no"><b class="serial"> {{$loop->iteration}}</b></td>
                        <td>{{$category->parentcat?$category->parentcat->name:$category->name}}</td>
                        <td>{{$category->parentcat?$category->name:'N/A'}}</td>
                        <td class="action">
                            <button class="btn btn-info edit-category" data-id="{{$category->id}}" data-name="{{$category->name}}" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger delete-category" data-id="{{$category->id}}" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Category Add Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="category_add_form" class="form">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header justify-content-between" style="background: #579eb9; color: #fff;">
                    <legend class="modal-title text-center"><i class="fab fa-wpforms"></i> &nbsp;{{__('messages.add_new_form')}}
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
                    <div id="category_div_id" class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="Category_Name">{{__('messages.category_name')}} </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control Category_Name" name="name">
                        </div>
                    </div>

                    <div id="" class="row form-group">
                        <div class="col-md-6 d-flex justify-content-center" style="margin-left: -38px">
                            <label class="checkbox-inline"><input type="checkbox" id="select_sub_category_id" name="sub_category_checkbox_name" value="1"> {{__('messages.add_as_subcategory')}} </label>

                        </div>
                    </div>

                    <div id="subcategory_div_id" class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="Category_Name">{{__('messages.select_parent_cat')}} </label>
                        </div>
                        <div class="col-md-7">

                            <select class="form-control" name="sub_category_name" id="sub_category_id">
                                <option selected="selected" value="">{{__('messages.select_here')}} </option>
                                @foreach($sub_categories as $sub_category)

                                <option value="{{$sub_category->id}}">{{$sub_category->name}}</option>

                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>{{__('messages.cancel')}}
                    </button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i>{{__('messages.confirm_add')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Category Template Modal -->
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
            <form id="category_edit_form" class="form">
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
                    <div id="category_div_id_2" class="row justify-content-center form-group">
                        <div class="col-md-3">
                            <label for="Category_Name">{{__('messages.name')}} </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control Category_Name" name="name">
                        </div>
                    </div>

                    {{-- <div id="" class="row form-group">--}}
                    {{-- <div class="col-md-6 d-flex justify-content-center" style="margin-left: -38px">--}}
                    {{-- <label class="checkbox-inline"><input type="checkbox" id="select_sub_category_id_2"--}}
                    {{-- name="sub_category_checkbox_name_2" value="1"> Add as Subcategory</label>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}

                    {{-- <div id="subcategory_div_id_2" class="row justify-content-center form-group">--}}
                    {{-- <div class="col-md-3">--}}
                    {{-- <label for="Category_Name">Select Parent Category</label>--}}
                    {{-- </div>--}}
                    {{-- <div class="col-md-7">--}}

                    {{-- <select class="form-control" name="sub_category_name_2" id="sub_category_id_2">--}}
                    {{-- <option selected="selected" value="">Select here</option>--}}
                    {{-- @foreach($categories as $category)--}}


                    {{-- <option value="{{$category->id}}">{{$category->name}}</option>--}}

                    {{-- @endforeach--}}
                    {{-- </select>--}}
                    {{-- </div>--}}
                    {{-- --}}
                    {{-- </div>--}}
                    <input type="hidden" id="id" name="id" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('messages.cancel')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('messages.submit')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection