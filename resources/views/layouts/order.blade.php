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
			{{-- @if(auth()->user()->role=='master'||
			auth()->user()->role=='chief-officer'||
			auth()->user()->role=='chief-engineer'||
			auth()->user()->role=='second-engineer')
						<strong class="pptitle">
				Requisition List of <span style="color:red;display: inline-block;padding-left: 5px;"> {{auth()->user()->role->vessel->name}} </span>
			</strong>
			@else

			@endif --}}
			<div class="filter_form">
				{{-- <form id="order_search_form" class="form form-inline" method="post" action="{{url('/search/order')}}">
				@csrf
				<div class="form-group">
					<select name="cat_id" class="form-control" id="cate_name">
						<option value="" selected="">--Select Category--</option>
						@if(!empty($categories))
						@foreach($categories as $cat)
						<option value="{{$cat->id}}" {{(!empty($cat_id)&&$cat->id==$cat_id)?'selected':''}}>{{$cat->name}}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group item_name_wrapper">
					<select name="item_id" class="form-control" id="item_name">
						<option class="item_opt_default" value="" selected="">--Select Item--</option>
					</select>
					<img class="field-loader" src="{{asset('/assets/image/f2.gif')}}">
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
				</form> --}}
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
			<table id="example" class="table table-bordered dt-responsive" style="width: 100%;">
				<thead>
					<th>{{__('messages.sl_no')}}</th>
					<th>{{__('messages.req_no')}}</th>
					<th>{{__('messages.department')}}</th>
					<th>{{__('messages.req_date')}}</th>
					<th>{{__('messages.status')}}</th>
					<th>{{__('messages.created_by')}}</th>
					<th class="action">{{__('messages.action')}}</th>
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
						<td>{{!empty($order->dept->name)?$order->dept->name:''}}</td>
						<td>{{!empty($order->req_date)?$order->req_date:''}}</td>
						<td>{{!empty($order->status)?$order->status:''}}</td>
						<td>{{!empty($order->created_by)?$order->createdBy->name:''}}</td>

						<td class="action">
							@if(auth()->user()->role->role=='dept-user'||auth()->user()->role->role=='stock-officer'||auth()->user()->role->role=='super-admin' && $order->status=='Pending for approval')
							<a class="btn btn-info" href="{{url('/requisition/edit/'.$order->id)}}"><i class="fas fa-edit"></i></a>
							{{-- <button class="btn btn-danger delete-order" data-id="{{$order->id}}" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button> --}}
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

<!-- Edit order Template Modal -->
<div class="modal fade" id="edit_template_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #579eb9; padding: 10px 0;">
				<legend style="color:#fff; text-align: center; margin-bottom:0;"><i class="far fa-edit"></i> &nbsp; Update order </legend>
				<button style="color: #fff;" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
				</button>
			</div>
			<form id="order_edit_form" class="form">
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
							<label for="Category_Name">Category Name: </label>
						</div>
						<div class="col-md-7">
							<select class="form-control Category_Name" name="Category_Name">
								<option selected="" value="" class='cat_opt'>-- Choose Category --</option>
								@if(!empty($categories))
								@foreach($categories as $category)
								<option value="{{$category->id}}" class='cat_opt'>{{$category->name}}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>

					<div class="row justify-content-center form-group">
						<div class="col-md-3">
							<label for="order_Name">order Name: </label>
						</div>
						<div class="col-md-7">
							<input type="text" class="form-control order_Name" name="order_Name">
						</div>
					</div>
					<div class="row justify-content-center form-group">
						<div class="col-md-3">
							<label for="impa_code">Impa Code No: </label>
						</div>
						<div class="col-md-7">
							<input type="number" class="form-control impa_code" name="Impa_Code_No">
						</div>
					</div>
					<div class="row justify-content-center form-group">
						<div class="col-md-3">
							<label for="measurement_unit">Measurement Unit: </label>
						</div>
						<div class="col-md-7">
							<input type="text" class="form-control measurement_unit" name="Measurement_Unit">
							<input type="hidden" class="form-control order_id" name="order_id">
						</div>
					</div>
				</div>
				<input type="hidden" id="id" name="id" class="form-control">
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Update order</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- logo-base64 for pdf page -->
@include('pdf.logo-base64')
<!-- logo-base64 for pdf page -->
@endsection