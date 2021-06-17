@extends('layouts.admin-master')
@section('main-content')
<div class="order-section container">
    <div class="row">
        <div class="col-xl-12">
            <div class="card order-card">
                <div class="card-header first">
                    <strong class="pptitle">Edit requisition form for
                        <span style="color:red;">{{auth()->user()->dept->name}}</span>
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
                                <label for="category"> Category: </label>
                                <select class="form-control Category_Name" id="cate_name" name="Category_Name">
                                    <option selected>-- Choose Category --</option>
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
                                                Open Add Items Form
                                            </button>
                                        </span>
                                    </h5>
                                </div>
                                <div id="collapseOne" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="item-selection container mt-4">
                                        <div class="form-group row justify-content-between">
                                            <div class="col-md-5">
                                                <label for="Item_Name"> Choose Item: </label>
                                                <select type="text" class="form-control date" id="Item_Name" placeholder="">
                                                    <option value="" selected class="item_opt_default">-- Select Item -- </option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="item_qty"> Quantity: </label>
                                                <input type="number" class="form-control" id="item_qty" placeholder="" value="1">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">&nbsp;</label> <br>
                                                <button class="btn btn-info btn-add" id="order_add">
                                                    <i class="fa fa-plus"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                        <hr>
                                        <input type="hidden" name="edit_order_id" id="edit_order_id" value="{{$orderId}}">
                                        <div class="form-group row">
                                            <div class="col-md-12 item-list-shown">
                                                <table id="example1" class="table table-striped table-bordered orderedItemTable table-responsive" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>SL. No.</th>
                                                            <th>Item Name</th>
                                                            <th>Req. Quantity</th>
                                                            <th>Unit</th>
                                                            <th>Category</th>
                                                            <th class="action">Action</th>
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
                                                <button class="btn btn-primary view">View Requisition Items</button>
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
            <form id="add_order_form" class="order">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header justify-content-between" style="background: #579eb9; color: #fff;">
                    <legend class="modal-title text-center"><i class="fab fa-wpforms"></i> &nbsp;
                        Requisition Details
                    </legend>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Please Review the items before submit your requisition.</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12 item-list-shown">
                            <table id="example1" class="table table-striped table-bordered table-responsive" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL. No.</th>
                                        <th>Item Name</th>
                                        <th>Req. Quantity</th>
                                        <th>Unit</th>
                                        <th>Category</th>
                                        <th class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="req-list-body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <label for="sub"></label>
                            <button type="submit" class="btn btn-success btn-sub">Submit</button>
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
        $('#edit_order_id').val();
        $('#example1').DataTable();

        $('select#cate_name, input#Port_Name').on('change keyup', function(e) {
            e.preventDefault();
            if ($('input#Port_Name').val() != '' && $('select#cate_name').val() != '') {
                $('button.btn-addnew').attr('disabled', false);
            } else {
                $('button.btn-addnew').attr('disabled', true);
                $('#collapseOne').removeClass('show');
            }
        })

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