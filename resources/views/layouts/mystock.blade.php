@extends('layouts.admin-master')
@section('main-content')
<div class="col-lg-6 col-xl-12">
    <div class="card">
        <div class="card-header pv-card-hader">
            <strong class="pptitle">{{__('messages.stock_lists')}}</strong>

        </div>
        <!-- card-hader -->
        <!-- card-body -->
        <div class="card-body">
            <table id="example" class="table table-bordered dt-responsive" style="width: 100%;">
                <thead>
                    <tr>
                        <th>{{__('messages.sl_no')}}</th>
                        <th>{{__('messages.product')}}</th>
                        <th>{{__('messages.stock_available')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($deptstock))
                    @foreach ($deptstock as $stock)
                    <tr id="purchase-{{ $stock->id }}">
                        <td class="sl_no"> <b class="serial"> {{ $loop->iteration }}</b> </td>
                        <td>{{ $stock->product? $stock->product->name : '' }}</td>
                        <td>{{ $stock->qty }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection