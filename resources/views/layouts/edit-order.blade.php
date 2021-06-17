@extends('layouts.admin-master')
@section('main-content')
<div class="order-section container">
	<div class="row">
		<div class="col-xl-12">
			<div class="card order-card">
				<div class="card-header first">
					{{-- <strong class="pptitle">New requisition form for --}}
					<strong class="pptitle">{{__('messages.new_requisition_form')}}
						<span style="color:red;">{{$order->dept->name}}</span>
					</strong>

					<div class="right-button">
						<!-- <button class="btn btn-info btn-bvprint print-order"><i class="fa fa-print"></i>  Print</button> -->
					</div>
				</div>
				<div class="card-body">
					<form id="req-list" class="order">
						@csrf
						<div class="row justify-content-center form-group">
							<div class="col-md-11 alert alert-danger alert-dismissible fade show form_error" style="display:none" role="alert">
								<strong>Error Submission!!</strong> Please correct following info and resubmit.
								<label> </label>
								<button type="button" class="close close_error_alert">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						</div>
						<div class="form-group row justify-content-between">
							<div class="col-md-5">
								<label for="category">{{__('messages.category')}}: </label>
								<select class="form-control Category_Name" id="cate_name" name="Category_Name">
									<option selected>{{__('messages.choose_category')}}</option>
									@if(!empty($categories))
									@foreach($categories as $category)
									<option value="{{$category->id}}">{{$category->name}}</option>
									@endforeach
									@endif
								</select>
							</div>
						</div>

						<div id="accordion" class="mt-4">
							<div class="card">
								<div class="card-header" id="headingOne">
									<h5 class="mb-0 text-center">
										<span id='add_item_button_wrapper'>
											<button class="btn btn-link btn-addnew" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" id='add_item_button' disabled>
												{{__('messages.open_add_items_form')}}
											</button>
										</span>
									</h5>
								</div>
								<input type="hidden" name="edit_order_id" id="edit_order_id" value="{{$orderid}}">
								<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
									<div class="item-selection container mt-4">
										<div class="form-group row justify-content-between">
											<div class="col-md-3">
												<label for="Item_Name">{{__('messages.item')}}</label>
												<select type="text" class="form-control date" id="Item_Name" placeholder="">
													<option value="" selected class="item_opt_default">{{__('messages.choose_item')}}</option>
												</select>
											</div>
											<div class="col-md-2">
												<label for="item_qty">{{__('messages.quantity')}}</label>
												<input type="number" class="form-control" id="item_qty" placeholder="" value="1">
											</div>
											<div class="col-md-3 avl-stock">
												<label for="item_qty">Available Stock:</label>
												<span class="available_stock pl-2">N/A</span>
											</div>
											<div class="col-md-2 avl-stock">
												<label for="item_qty">Limit:</label>
												<span class="pl-2">{{ $order->createdBy->req_limit }}</span>
											</div>
											<div class="col-md-2">
												<label for="">&nbsp;</label> <br>
												<button class="btn btn-info btn-add" id="order_add">
													<i class="fa fa-plus"></i> {{__('messages.add')}}
												</button>
											</div>
										</div>
										<hr>
										<div class="form-group row">
											<div class="col-md-12 item-list-shown">
												<table id="example1" class="table table-striped table-bordered orderedItemTable table-responsive" style="width:100%">
													<thead>
														<tr>
															<th>{{__('messages.sl_no')}}</th>
															<th>{{__('messages.item_name')}}</th>
															<th>{{__('messages.req_qnty')}}</th>
															<th>{{__('messages.unit')}}</th>
															<th>{{__('messages.category')}}</th>
															<th class="action">{{__('messages.action')}}</th>
														</tr>
													</thead>
													<tbody>

													</tbody>
												</table>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-md-12 text-right">
												<label for="sub"></label>
												<button class="btn btn-primary view">{{__('messages.view_requisition')}}</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="reqdetail">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="update_order_form" class="order">
				@csrf
				<input type="hidden" name="edit_order_id" id="edit_order_id" value="{{$orderid}}">
				<!-- Modal Header -->
				<div class="modal-header justify-content-between" style="background: #579eb9; color: #fff;">
					<legend class="modal-title text-center"><i class="fab fa-wpforms"></i> &nbsp;
						চাহিদা পত্র
					</legend>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<!-- Modal body -->
				<div class="modal-body">
					<div class="row mb-3 justify-content-center">
						<div class="col-5 irdheader">
							<img src="{{url('/images/bd govt.png')}}" width="50px" height="50px">
							<p>অভ্যন্তরীণ সম্পদ বিভাগ</p>
							<p>অর্থ মন্ত্রণালয়</p>
						</div>
					</div>

					{{-- <div class="row form-group">
						<div class="col-md-12">
						<label>{{__('messages.submit_requisition')}}</label>
				</div>
		</div> --}}
		<div class="row mb-3 justify-content-between req-view">
			<div class="col">
				<strong>{{__('messages.name')}}:</strong>
				<p>{{$order->createdBy->name}}</p>
			</div>
			<div class="col">
				<strong>{{__('messages.designation')}}:</strong>
				<p> {{$order->createdBy->designation}}</p>
			</div>
			{{-- <div class="col">
							<strong>{{__('messages.status')}}:</strong> {{$order->status}}
		</div> --}}
		<div class="col">
			<strong>{{__('messages.department')}}:</strong>
			<p> {{$order->createdBy->dept->name}}</p>
		</div>
		<div class="col">
			<strong>{{__('messages.date')}}:</strong>
			<p> {{$order->req_date}}</p>
		</div>

	</div>

	<div class="form-group row">
		<div class="col-md-12 item-list-shown">
			<table class="table table-striped table-bordered table-responsive" style="width:100%">
				<thead>
					<tr>
						<th>{{__('messages.sl_no')}}</th>
						<th>{{__('messages.item_name')}}</th>
						<th>{{__('messages.req_qnty')}}</th>
						<th>{{__('messages.unit')}}</th>
						<th>{{__('messages.category')}}</th>
					</tr>
				</thead>
				<tbody id="req-list-body">

				</tbody>
			</table>
		</div>
	</div>
	<div id="order-print-footer1" class="print-header">
		<div class="footer-notes">

		</div>

		<div class="signs-master-chief">
			<div class="master-chief">
				<span class="written-sign sign">
					<img src="{{url('/'.$order->createdBy->sign)}}" alt="">
				</span>
				<span>_____________________</span>
				<span>({{__('messages.signature')}})</span>
				<span>{{$order->dept->name}}</span>
				<span class="signer-name">
					{{ $order->createdBy->name}}
				</span>
			</div>
		</div>
	</div>
	<br>
	<div class="form-group row">
		<div class="col-md-12 text-right">
			<label for="sub"></label>
			<button type="submit" class="btn btn-success btn-sub">{{__('messages.submit')}}</button>
		</div>
	</div>
</div>
</form>
</div>
</div>
</div>
@endsection
@section('create-order-js')
<script>
	$(document).ready(function() {
		var orderTable = $("#example1").DataTable();
		var orderid = $('#edit_order_id').val();
		url = "/get-order-items";
		$.ajax({
			type: "get",
			url: url,
			data: {
				orderid: orderid,
			},
			dataType: 'json',
			success: function(data) {
				$.each(data, function(index, item) {
					var rowNode = (orderTable.row
						.add([
							'<b class="serial">' + parseInt(index + 1) + "</b>",
							item.name +
							'<input type="hidden" name="item_id[]" value="' +
							item.id +
							'">',
							'<span class="added_qty">' +
							item.qty +
							"</span>" +
							'<input type="hidden" class="form-control qty-edit" name="item_qty[]" value="' +
							item.qty +
							'">',
							item.unit,
							item.cat_name,
							'<button class="btn btn-info mr-1 edit-order-item" data-id="' +
							item.id +
							'" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>' +
							'<button class="btn btn-danger delete-order-item" data-id="' +
							item.id +
							'" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>',
						])
						.order([0, "asc"])
						.draw()
						.node().id = "row_ordered_item-" + item.id);
					var rows = $("#example1 tbody").html();
				});
			}
		});



		$(".orderedItemTable .serial").each(function(index) {
			$(this).text((index + 1));
		});
		var orderInfo = JSON.parse(localStorage.getItem('orderInfo'));
		if (orderInfo != null) {
			$('select.Vessel_Name option').each(function() {
				if ($(this).val() == orderInfo[1]) {
					$(this).attr('selected', true);
				}
			});
			$('select#cate_name option').each(function() {
				if ($(this).val() == orderInfo[4]) {
					$(this).attr('selected', true);
				}
			});
			$('input[name="Port_Name"]').val(orderInfo[2]);
		}
		if ($('select#cate_name').val() != '' && $('input#Port_Name').val() != '') {
			$('button.btn-addnew').attr('disabled', false);
		} else {
			// swal('Alert','Please fill-up the above form fields. Then Press Add Items Button','warning');
			$('button.btn-addnew').attr('disabled', true);
		}
	});
</script>
@endsection