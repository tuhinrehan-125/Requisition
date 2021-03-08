@extends('layouts.admin-master')
@section('main-content')
    <div class="col-lg-6 col-xl-12">
        <div class="card">
            <div class="card-header pv-card-hader">
                <strong class="pptitle">{{__('messages.user_lists')}}</strong>

                <div class="right-buttons users-btns">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i
                            class="fas fa-plus-square"></i> {{__('messages.add_new')}}
                    </button>
                    <button class="btn btn-info btn-bvprint" onClick="print_this();"><i class="fa fa-print"></i> {{__('messages.print')}}
                    </button>
                </div>
            </div>

            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive" style="width: 100%;">
                    <thead>
                    <th>#</th>
                    <th>{{__('messages.name')}}</th>
                    <th>{{__('messages.username')}}</th>
                    <th>{{__('messages.email')}}</th>
                    <th>{{__('messages.user_role')}}</th>
                    <th>{{__('messages.department')}}</th>
                    <th>{{__('messages.designation')}}</th>
                    <th class="action">{{__('messages.action')}}</th>
                    </thead>
                    <tbody>
                    @if(!empty($data['users']))
                        @foreach($data['users'] as $user)
                            <tr id="user-{{!empty($user->id)?$user->id:''}}">
                                <td class="sl_no"><b class="serial"> {{$loop->iteration}}</b></td>
                                <td>{{!empty($user->name)?$user->name:''}}</td>
                                <td>{{!empty($user->username)?$user->username:''}}</td>
                                <td>{{!empty($user->email)?$user->email:''}}</td>
                                <td>{{!empty($user->role)?$user->role->role:''}}</td>
                                <td>{{!empty($user->dept)?$user->dept->name:''}}</td>
                                <td>{{!empty($user->designation)?$user->designation:''}}</td>
                                <td class="action">
                                    @if ($user->role->role !== 'super-admin')
                                    <button class="btn btn-info edit-user" data-id="{{!empty($user->id)?$user->id:''}}"
                                            data-toggle="modal" data-target="#edit_template_modal"><i
                                            class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger delete-user"
                                            data-id="{{!empty($user->id)?$user->id:''}}" data-toggle="modal"
                                            data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Certificate Add Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="user_add_form" class="form">
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
                            <div class="col-md-11 alert alert-danger alert-dismissible fade show form_error"
                                 style="display:none" role="alert">
                                <strong>Error Submission!!</strong> Please correct following info and resubmit.
                                <label> </label>
                                <button type="button" class="close close_error_alert">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group">
                            <div class="col-md-3">
                                <label for="User_Name"> {{__('messages.name')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control User_Name" name="User_Name">
                            </div>
                        </div>

                        <div class="row justify-content-center all_user form-group">
                            <div class="col-md-3">
                                <label for="Email"> {{__('messages.email')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="email" class="form-control Email" name="email">
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group">
                            <div class="col-md-3">
                                <label for="Email"> {{__('messages.username')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control username" name="username">
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group">
                            <div class="col-md-3">
                                <label for="User_Role">{{__('messages.user_role')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <select class="form-control User_Role" name="User_Role">
                                    <option selected="" value="" hidden>{{__('messages.choose_role')}}</option>
                                    @foreach($roles as $role)
                                    @if ($role->role !== 'super-admin')
                                    <option value="{{$role->id}}">{{strtoupper($role->role)}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group">
                            <div class="col-md-3">
                                <label for="department">{{__('messages.department')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <select class="form-control department_class" name="department_name" id="department_id">
                                    <option selected="" value="">{{__('messages.select_here')}}</option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group" >
                            <div class="col-md-3">
                                <label for="designation">{{__('messages.designation')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control  designation" name="designation">
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group" >
                            <div class="col-md-3">
                                <label for="limit">{{__('messages.limit')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control  limit" name="limit">
                            </div>
                        </div>

                        <div class="row justify-content-center all_user form-group" >
                            <div class="col-md-3">
                                <label for="Password">{{__('messages.password')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="password" class="form-control  Password" name="password">
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group" >
                            <div class="col-md-3">
                                <label for="Conirm_Password">{{__('messages.confirm_password')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="password" class="form-control  Conirm_Password"
                                       name="password_confirmation">
                                <input type="hidden" class="form-control vessel_not_for_admin" value="0"
                                       name="Vessel_Name" disabled>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                class="far fa-window-close"></i> {{__('messages.cancel')}}
                        </button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i> {{__('messages.confirm_add')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit User Template Modal -->
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
                <form id="user_edit_form" class="form">
                @csrf
                <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row justify-content-center form-group">
                            <div class="col-md-11 alert alert-danger alert-dismissible fade show form_error"
                                 style="display:none" role="alert">
                                <strong>Error Submission!!</strong> Please correct following info and resubmit.
                                <label> </label>
                                <button type="button" class="close close_error_alert">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>

                        <div class="row justify-content-center all_user form-group">
                            <div class="col-md-3">
                                <label for="User_Name"> {{__('messages.name')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control User_Name" name="User_Name" id="edit_name_id">
                            </div>
                        </div>

                        <div class="row justify-content-center all_user form-group">
                            <div class="col-md-3">
                                <label for="username"> {{__('messages.username')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control username" name="username" id="edit_username">
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group">
                            <div class="col-md-3">
                                <label for="Email"> {{__('messages.email')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="email" class="form-control Email" name="email" id="edit_email_id">
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group">
                            <div class="col-md-3">
                                <label for="User_Role">{{__('messages.user_role')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <select class="form-control User_Role" name="User_Role" id="edit_user_role_id">
                                    <option selected="" value="">{{__('messages.choose_role')}}</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->role}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group">
                            <div class="col-md-3">
                                <label for="department">{{__('messages.department')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <select class="form-control department_class" name="department_name" id="edit_department_id">
                                    <option selected="" value="">{{__('messages.select_here')}}</option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group" >
                            <div class="col-md-3">
                                <label for="designation">{{__('messages.designation')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control  designation" id="edit_designation" name="designation">
                            </div>
                        </div>
                        <div class="row justify-content-center all_user form-group" >
                            <div class="col-md-3">
                                <label for="limit">{{__('messages.limit')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control  limit" id="edit_limit" name="limit">
                            </div>
                        </div>

                        <div class="row justify-content-center all_user form-group" >
                            <div class="col-md-3">
                                <label for="Password">{{__('messages.password')}}: </label>
                            </div>
                            <div class="col-md-7">
                                <input type="password" class="form-control  Password" name="password">
                            </div>
                        </div>
{{--                        <div class="row justify-content-center all_user form-group" >--}}
{{--                            <div class="col-md-3">--}}
{{--                                <label for="Conirm_Password">Confirm Password: </label>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-7">--}}
{{--                                <input type="password" class="form-control  Conirm_Password"--}}
{{--                                       name="password_confirmation">--}}

{{--                        </div>--}}
                        <input type="hidden" id="edituserid" name="id" class="form-control" value="">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('messages.cancel')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('messages.update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- logo-base64 for pdf page -->
    @include('pdf.logo-base64')
    <!-- logo-base64 for pdf page -->
@endsection
