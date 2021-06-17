@extends('layouts.admin-master')
@section('main-content')
<div class="col-lg-6 col-xl-12">
	<div class="card">
		<div class="card-header pv-card-hader">
			{{-- <strong class="pptitle">Item List</strong>--}}
			<strong class="pptitle">{{__('messages.product_list')}}</strong>

			<div class="right-buttons">
				@if(auth()->user()->role->role=='stock-officer')
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"> <i class="fas fa-plus-square"></i> {{__('messages.add')}} </button>
				@endif
				<button class="btn btn-info btn-bvprint" onClick="print_this();"><i class="fa fa-print"></i> {{__('messages.print')}}</button>
			</div>
		</div>
		<!-- card-hader -->
		<!-- card-body -->
		<div class="card-body">
			<table id="example" class="table table-bordered dt-responsive table-responsive" style="width: 100%;">
				<thead>
					<th>{{__('messages.sl_no')}}</th>
					<th>{{__('messages.name')}}</th>
					<th>{{__('messages.product_code')}}</th>
					<th>{{__('messages.unit')}}</th>
					<th>{{__('messages.category')}}</th>
					<th>{{__('messages.created_by')}}</th>
					<th class="action">{{__('messages.action')}}</th>
				</thead>
				<tbody>
					@if(!empty($items))
					@foreach($items as $item)
					<tr id="item-{{$item->id}}">
						<td class="sl_no"> <b class="serial"> {{$loop->iteration}}</b> </td>
						<td>{{!empty($item->name)?$item->name:''}}</td>
						<td>{{!empty($item->impa_code)?$item->impa_code:''}}</td>
						<td>{{!empty($item->unit)?$item->unit:''}}</td>
						{{-- <td>{{!empty($item->unit_table->name)?$item->unit_table->name:''}}</td>--}}
						<td>{{!empty($item->category->name)?$item->category->name:''}}</td>
						<td>{{!empty($item->created_by)?$item->createdBy->name :''}}</td>
						<td class="action">
							<button class="btn btn-info edit-item" data-id="{{$item->id}}" data-name="{{$item->name}}" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>
							<button class="btn btn-danger delete-item" data-id="{{$item->id}}" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>
						</td>
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
			<form id="item_add_form" class="form">
				@csrf
				<!-- Modal Header -->
				<div class="modal-header justify-content-between" style="background: #579eb9; color: #fff;">
					<legend class="modal-title text-center"><i class="fab fa-wpforms"></i> &nbsp;{{__('messages.add_new_form')}}</legend>
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
							<select class="form-control Category_Name" name="Category_Name">
								<option selected="" value="">{{__('messages.choose_category')}}</option>
								@if(!empty($categories))
								@foreach($categories as $category)
								<option value="{{$category->id}}">{{$category->name}}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>

					<div class="row justify-content-center form-group">
						<div class="col-md-3">
							<label for="Category_Name">{{__('messages.sub_category')}}: </label>
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
							<label for="item_Name">{{__('messages.product')}}: </label>
						</div>
						<div class="col-md-7">
							<input type="text" class="form-control item_Name" name="Item_Name">
						</div>
					</div>
					<div class="row justify-content-center form-group">
						<div class="col-md-3">
							<label for="code">{{__('messages.product_code')}}: </label>
						</div>
						<div class="col-md-7">
							<input type="text" class="form-control code" name="code">
						</div>
					</div>
					<div class="row justify-content-center form-group">
						<div class="col-md-3">
							<label for="measurement_unit">{{__('messages.unit')}}: </label>
						</div>
						<div class="col-md-7">
							<input type="text" class="form-control measurement_unit" name="Measurement_Unit">
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
<!-- Edit item Template Modal -->
<div class="modal fade" id="edit_template_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #579eb9; padding: 10px 0;">
				<legend style="color:#fff; text-align: center; margin-bottom:0;"><i class="far fa-edit"></i> &nbsp {{__('messages.update')}} </legend>
				<button style="color: #fff;" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
				</button>
			</div>
			<form id="item_edit_form" class="form">
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
							<select class="form-control Category_Name" name="Category_Name" id="edit_category_id">
								<option selected="" value="">{{__('messages.choose_category')}}</option>
								@if(!empty($categories))
								@foreach($categories as $category)
								<option value="{{$category->id}}">{{$category->name}}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>

					<div class="row justify-content-center form-group">
						<div class="col-md-3">
							<label for="Category_Name">{{__('messages.sub_category')}}: </label>
						</div>
						<div class="col-md-7">
							<select class="form-control" id="edit_sub_cat_id" name="sub_cat_name">
								<option selected="selected" value="" disabled>{{__('messages.select_cat_first')}}
								</option>

							</select>
						</div>
					</div>

					<div class="row justify-content-center form-group">
						<div class="col-md-3">
							<label for="item_Name">{{__('messages.product')}}: </label>
						</div>
						<div class="col-md-7">
							<input type="text" class="form-control item_Name" name="Item_Name" id="product_name_id">
						</div>
					</div>
					<div class="row justify-content-center form-group">
						<div class="col-md-3">
							<label for="item_Name">{{__('messages.product_code')}}: </label>
						</div>
						<div class="col-md-7">
							<input type="text" class="form-control impa_code" name="impa_code" id="impa_code">
						</div>
					</div>
					<div class="row justify-content-center form-group">
						<div class="col-md-3">
							<label for="measurement_unit">{{__('messages.unit')}}: </label>
						</div>
						<div class="col-md-7">
							<input type="text" class="form-control measurement_unit" name="Measurement_Unit" id="edit_unit_id">
						</div>
					</div>

					<input type="hidden" id="id" name="id" class="form-control">

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