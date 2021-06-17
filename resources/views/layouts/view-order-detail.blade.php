@extends('layouts.admin-master')
@section('main-content')
<style>
	.written-sign img {
		max-height: 80px;
		max-width: 300px;
	}
</style>
@php
$userrole=auth()->user()->role->role;
$order_approval=$order->orderApproval;
@endphp
<div class="order-section container">
	<div class="row">
		<div class="col-xl-12">
			<div class="row mb-3 justify-content-center">
				<div class="col-3 irdheader">
					<img src="{{url('/images/bd govt.png')}}" width="50px" height="50px">
					<p>অভ্যন্তরীণ সম্পদ বিভাগ</p>
					<p>অর্থ মন্ত্রণালয়</p>
				</div>
			</div>
			<div class="card order-card">
				<div class="card-header first">

					<strong class="requisition-letter" style="margin: 0 auto;">
						চাহিদা পত্র ({{$order->req_no}})
					</strong>
					<div class="right-button">
						@if($userrole=='admin-officer' && $order_approval->rejection_by_admin==null && $order_approval->admin_officer==null && $order_approval->forward_to_officer==null )
						<button type="button" class="btn btn-primary" id="approve_order" data-id="{{$order->id}}">
							<i class="fas fa-check-circle"></i> {{__('messages.approve')}}
						</button>
						@endif
						@if($userrole=='stock-officer' && $order_approval->stock_officer==null && ($order_approval->admin_officer!==null || $order_approval->sr_officer!==null) && $order_approval->rejection_by_admin==null && $order_approval->rejection_by_officer==null)
						<button type="button" class="btn btn-success" id="delivered_order" data-id="{{$order->id}}">
							<i class="fas fa-check-circle"></i> {{__('messages.deliver')}}
						</button>
						@endif
						@if($userrole=='dept-user' && $order_approval->user_received==null && $order_approval->stock_officer!==null && ($order_approval->admin_officer!==null || $order_approval->sr_officer!==null) && $order_approval->rejection_by_admin==null && $order_approval->rejection_by_officer==null)
						<button type="button" class="btn btn-success" id="received_order" data-id="{{$order->id}}">
							<i class="fas fa-check-circle"></i> {{__('messages.received')}}
						</button>
						@endif
						@if( $userrole=='sr-officer'&& $order_approval->forward_to_officer==1 && $order_approval->sr_officer==null)
						<button type="button" class="btn btn-primary" id="approve_order" data-id="{{$order->id}}">
							<i class="fas fa-check-circle"></i> {{__('messages.approve')}}
						</button>
						@endif

						@if($userrole=='admin-officer' && $order_approval->admin_officer==null && $order_approval->rejection_by_admin==null && $order_approval->forward_to_officer==null)
						<button type="button" class="btn btn-danger" id="reject_order" data-id="{{$order->id}}">
							<i class="fas fa-times-circle"></i> {{__('messages.reject')}}
						</button>
						@endif

						@if($userrole=='sr-officer' && $order_approval->sr_officer==null && $order_approval->forward_to_officer==1 && $order_approval->rejection_by_officer==null)
						<button type="button" class="btn btn-danger" id="reject_order" data-id="{{$order->id}}">
							<i class="fas fa-times-circle"></i> {{__('messages.reject')}}
						</button>
						@endif
						@if($userrole == 'admin-officer' && $order_approval->rejection_by_admin==null && $order_approval->admin_officer==null && $order_approval->forward_to_officer==null)
						<button type="button" class="btn btn-info" id="forward_to" data-id="{{$order->id}}">
							<i class="fas fa-angle-double-right"></i> {{__('messages.forward_to_sr_officer')}}
						</button>
						@endif
						<button class="btn btn-info btn-bvprint print-order-details"><i class="fa fa-print"></i> {{__('messages.print')}}</button>
					</div>
				</div>
				<div class="card-body">
					@if(($userrole=='dept-user'))
					<form class="form mb-3 orderDetailForm" id="deliveredQtyForm">
						@csrf
						@endif
						<table id="reqdetailtable" class="table table-striped table-bordered orderedItemTable OrderDetailsTable table-responsive" style="width:100%">

							<div class="row mb-3 justify-content-between" id="order-print-header2">

								<div class="col">
									<strong>{{__('messages.name')}}:</strong> {{$order->createdBy->name}}
								</div>
								<div class="col">
									<strong>{{__('messages.designation')}}:</strong> {{$order->createdBy->designation}}
								</div>
								{{-- <div class="col">
									<strong>{{__('messages.status')}}:</strong> {{$order->status}}
							</div> --}}
							<div class="col">
								<strong>{{__('messages.department')}}:</strong> {{$order->createdBy->dept->name}}
							</div>
							<div class="col">
								<strong>{{__('messages.date')}}:</strong> {{$order->req_date}}
							</div>

				</div>
				<thead>
					<tr>
						<th>{{__('messages.item_no')}}</th>
						<th>{{__('messages.item_name')}}</th>
						<th>{{__('messages.unit')}}</th>
						{{-- <th>Last <br>Supply </th>--}}
						<th>{{__('messages.last_supply')}}</th>
						<th>{{__('messages.in_stock')}}</th>
						<th>{{__('messages.total_supply')}}</th>
						<th>{{__('messages.req_qnty')}}</th>
						<th>{{__('messages.approved_qty')}} </th>
						<th>{{__('messages.delivered_qty')}} </th>
						<th>{{__('messages.received_qty')}} </th>
					</tr>
				</thead>
				<tbody>
					@if(!empty($orders))
					@foreach($orders as $index=>$orderr)
					<tr>
						<td><b class="serial">{{$loop->iteration}}</b></td>
						<td class="item-name-td">{{$orderr->name}}</td>
						<td class="item-unit">{{$orderr->unit}}</td>
						@php
						$deptStock=App\DeptStock::where('dept_id',$orderr->dept_id)->where('product_id',$orderr->Items[$index]->item_id);
						$total_supply=$deptStock->sum('qty');
						$last_supply=$deptStock->where(function ($q) {
						$q->where('created_at', '>', DB::raw('DATE_ADD(CURDATE(), INTERVAL -30 DAY)'));
						})->sum('qty');

						@endphp
						<td>{{!empty($deptStock)?$last_supply:0 }}</td>
						<td class="in_stock_class">{{!empty($orderr->in_stock)?$orderr->in_stock:'0'}}</td>
						<td>{{!empty($deptStock)?$total_supply:0 }}</td>
						<td class='req_qty'>
							{{!empty($orderr->item_qty)?$orderr->item_qty:''}}
						</td>
						<td class='deliver-qty' style="min-width: 100px">
							@if($userrole=='admin-officer' || $userrole=='sr-officer' && $order_approval->admin_officer==null && $order_approval->sr_officer==null && $order_approval->rejection_by_admin==null && $order_approval->rejection_by_officer==null )
							<input type="number" id="asked_qty" class="form-control deliver_qty" name="{{$orderr->itemId}}" value="{{$orderr->item_qty}}">
							@else
							{{!empty($orderr->approved_qty)?$orderr->approved_qty:0}}
							@endif
						</td>

						<td class='stock_delivered_qnty' style="width: 15%">
							@if($userrole=='stock-officer' && $order_approval->stock_officer==null && ($order_approval->admin_officer!==null || $order_approval->sr_officer!==null) && $order_approval->rejection_by_admin==null && $order_approval->rejection_by_officer==null)
							<input type="number" id="stock_delivered_qnty_id" class="form-control stock_delivered_qnty" name="{{$orderr->itemId}}" value="{{ $orderr->approved_qty }}">
							@else
							{{!empty($orderr->delivered_qty)?$orderr->delivered_qty:0}}
							@endif
						</td>
						<td class='stock_received_qnty' style="width: 15%">
							@if($userrole=='dept-user' && $order_approval->stock_officer!==null && $order_approval->user_received==null)
							<input type="number" id="stock_received_qnty_id" class="form-control stock_received_qnty" name="{{$orderr->itemId}}" value="{{ $orderr->delivered_qty }}">
							@else
							{{!empty($orderr->received_qty)?$orderr->received_qty:0}}
							@endif
						</td>
					</tr>

					@endforeach
					@endif
				</tbody>
				</table>

				<br>
				<hr>
				@if ($order_approval->rejection_by_admin!==null)
				<b style="color: #d63031;">Rejection Note: </b><span>{{ $order_approval->rejection_note_by_admin }} </span>
				@endif
				@if ($order_approval->rejection_by_officer!==null)
				<b style="color:#d63031;">Rejection Note: </b> <span> {{ $order_approval->rejection_note_by_officer }} </span>
				@endif
				<br>
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
							<span>Department User</span>
							<span class="signer-name">
								{{ $order->createdBy->name}}
							</span>
						</div>

						@foreach(\App\Role::get() as $key=>$role)
						@if ($role->role=='admin-officer' || $role->role=='sr-officer')
						@if($key==2)
						<div class="master-chief">
							<span class="written-sign sign">
								@if($order->orderApproval->admin_officer!==null)
								<img src="{{url('/'.$role->user->sign)}}" alt="">
								@endif
							</span>
							<span>_____________________</span>
							<span>({{__('messages.signature')}})</span>
							<span>{{ucwords($role->role)}}</span>
							<span class="signer-name">
								{{$role->user?ucwords($role->user->name):''}}
							</span>
						</div>
						@endif
						@if($key==3)
						<div class="master-chief">
							<span class="written-sign sign">
								@if( $order->orderApproval->sr_officer!==null)
								<img src="{{url('/'.$role->user->sign)}}" alt="">
								@endif
							</span>
							<span>_____________________</span>
							<span>( {{__('messages.signature')}})</span>
							<span>{{ucwords($role->role)}}</span>
							<span class="signer-name">
								{{$role->user?ucwords($role->user->name):''}}
							</span>
						</div>
						@endif
						@endif
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>


<!-- print header -->
<div id="order-print-header1" class="print-header">
	<div class="title-wrap od-title">
		<div class="logo">
			<a href="{{url('/')}}"><img src="{{asset('/images/bd govt.png')}}" alt="Site Logo"></a>
		</div>
		<div class="title-center">
			<h2 class="line1">অভ্যন্তরীণ সম্পদ বিভাগ</h2>
			<h2 class="line2">অর্থ মন্ত্রণালয়</h2>
			<h2 class="line3">শাখাঃ{{!empty($order->dept)?$order->dept->name:''}} এর চাহিদা পত্র</h2>
		</div>
		<div class="title-right">
		</div>
	</div>
</div>
<div id="order-print-header3" class="print-header">
	<table class="office-use-table">

		<body>

		</body>
	</table>
</div>
<!-- ./print header -->
@endsection