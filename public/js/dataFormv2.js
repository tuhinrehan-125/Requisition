$.ajaxSetup({
    headers: {
        'X_CSRF_TOKEN': $('meta[name="_token"]').attr('content')
    }
});
$(document).ready(function () {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    var table = $('#example').DataTable();
    var id = null; var tr_id = null; var tr_sl = null;

    ////////////////////// Purchase //////////////////////
    var language= $('.btn-lang').text();
    jQuery(document).ready(function () {
        jQuery('select[name="category_Name"]').on('change', function () {
            var countryID = jQuery(this).val();
            if (countryID) {
                jQuery.ajax({
                    url: '/items/getSubCategory/' + countryID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        // console.log(data);
                        var select_language=  (language === "English" )? "বাছাই করুন":"Please Select";
                        jQuery('select[name="sub_cat_name"]').empty();
                        $('select[name="sub_cat_name"]').append('<option value=" ">'+select_language+'</option>');
                        jQuery.each(data, function (key, value) {
                            $('select[name="sub_cat_name"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            }
            else {
                $('select[name="sub_cat_name"]').empty();
            }
        });
    });

    var language= $('.btn-lang').text();
    // console.log(language);

    // var bangla_sure = (language === "English" )?"আপনি নিশ্চিত?";
    // var bangla_delete_text = (language === "English" )?"আপনি ডিলিট করতে চান!";
    var bangla_confirm_btn = (language === "English" )? "হ্যা":"Yes, delete it!";
    var bangla_cancel_btn = (language === "English" )? "না":"Cancel";
    var bangla_congrats = language==="English"?"অভিনন্দন":"Well Done!";
    var bangla_congrats_delete = (language === "English" )?"ডিলিট সম্পন্ন হয়েছে":"Your information has been deleted successfully!";
    var bangla_oops = (language === "English" )?"দুঃখিত":"Oops...";
    var bangla_error = (language === "English" )?"কিছু একটা ভুল দেখা দিয়েছে": "An error has occurred";

    var excellent_text = language==="English"?"অভিনন্দন!" :"Excellent";
    var successful_text = language==="English"?"আপনার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে" :"Your information has been saved successfully";
    var error_text = language==="English"?"দুঃখিত! আপনার তথ্যগুলো পুনরায় পরীক্ষা করুন" :"Error! Check Your Form Information Please.";
    var delete_title = (language === "English" )? "আপনি নিশ্চিত?":'Are you sure?'
    var delete_text = (language === "English" )? "আপনি ডিলিট করতে চান!": "You want to delete this!"

    var excellent = (language === "English" )? "অভিনন্দন!" :"Excellent!";


    jQuery(document).ready(function () {
        jQuery('select[name="category_Name"]').on('change', function () {
            var countryID = jQuery(this).val();
            if (countryID) {
                jQuery.ajax({
                    url: '/purchase/getProduct/' + countryID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        // console.log(data);
                        jQuery('select[name="product_Name"]').empty();
                        $('select[name="product_Name"]').append('<option value=" ">Please select</option>');
                        jQuery.each(data, function (key, value) {
                            $('select[name="product_Name"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            }
            else {
                $('select[name="sub_cat_name"]').empty();
            }
        });
    });
    var language= $('.btn-lang').text();
    // console.log(language);
    var bangla_confirm_btn = (language === "English" )? "জ্বি":"Yes, delete it!";
    var bangla_cancel_btn = (language === "English" )? "না":"Cancel";
    var bangla_congrats = language==="English"?"অভিনন্দন":"Well Done!";
    var bangla_congrats_delete = (language === "English" )?"ডিলিট সম্পন্ন হয়েছে":"Your information has been deleted successfully!";
    var bangla_oops = (language === "English" )?"দুঃখিত":"Oops...";
    var bangla_error = (language === "English" )?"কিছু একটা ভুল দেখা দিয়েছে": "An error has occurred";

    var excellent_text = language==="English"?"চমৎকার!" :"Excellent";
    var successful_text = language==="English"?"আপনার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে" :"Your information has been saved successfully";
    var error_text = language==="English"?"দুঃখিত! আপনার তথ্যগুলো পুনরায় পরীক্ষা করুন" :"Error! Check Your Form Information Please.";
    var delete_title = (language === "English" )? "আপনি নিশ্চিত?":'Are you sure?'
    var delete_text = (language === "English" )? "আপনি ডিলিট করতে চান!": "You want to delete this!"
    // delete purchase function()
    $(document).on('click', '.delete-purchase', function () {
        var tr = $(this).parents('tr');
        var tr_id = tr.attr('id');
        // alert(tr_id+ ' '+ 'tr#'+tr_id);
        var id = $(this).data('id');
        swal({
            title: delete_title,
            text: delete_text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: bangla_cancel_btn,
            confirmButtonText: bangla_confirm_btn,
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve) {
                    $.ajax({
                        url: '/purchase/delete',
                        type: 'post',
                        data: {
                            _token: CSRF_TOKEN,
                            'id': id,
                        },
                        dataType: 'json'
                    })
                        .done(function (response) {
                            swal(excellent_text, bangla_congrats_delete, 'success').then(function () {
                                table
                                    .row("tr#" + tr_id)
                                    .remove()
                                    .draw();
                            });
                        })
                        .fail(function (response) {
                            swal(bangla_oops, bangla_error, 'error');
                        });
                });
            },
            allowOutsideClick: false
        });
    });

    // Purchase view starts here
    $(document).on("click", ".view-purchase", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        console.log(id);
        // var url = "/item/" + id;
        var url = '/view-purchase/view';
        var type = 'get';
        tr = $(this).parent().parent();
        tr_id = tr.attr("id");
        tr_sl = $("#" + tr_id + " .serial").text();
        $.ajax({
            type: type,
            url: url,
            data: {
                id : id
            },

            success: function (response) {

                var document_url = response[0]['document'];
                $('#purchase_view_form').find('#view_category').text(response[0]['category']);
                $('#purchase_view_form').find('#view_vendor').text(response[0]['vendor_name']);
                $('#purchase_view_form').find('#view_product').text(response[0]['product']);
                $('#purchase_view_form').find('#view_quantity').text(response[0]['qty']);
                $('#purchase_view_form').find('#view_document').attr('src',document_url).show();


            },
            error: function (errors) {
                toastr.warning("Error! Something went wrong.");
            }
        });
    });


    // Purchase view ends here


    //update purchase function
    $(document).on('submit', '#purchase_edit_form', function (event) {
        event.preventDefault();
        $("#purchase_edit_form .form_error").css('display', 'none');
        $("#purchase_edit_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#purchase_edit_form');
        var formData = form.serialize();
        var url = '/purchase/update';
        var type = 'post';
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#purchase_edit_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {

                toastr.success(successful_text,
                    excellent_text);
                $('#purchase_edit_form')[0].reset();
                var rData = [
                    '<b class="serial">' + tr_sl + '</b>',
                    response[1]['product'],
                    response[1]['category'],
                    response[1]['qty'],
                    response[1]['vendor_name'],
                    response[1]['purchase_date'],
                    '<div class="action"><button class="btn btn-info mr-1 edit-item" data-id="' + response[1]['id'] + '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>' +
                    '<button class="btn btn-danger delete-item" data-id="' + response[1]['id'] + '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button></div>'

                ];
                table
                    .row('tr#' + tr_id)
                    .data(rData)
                    .draw();
                swal(excellent_text, successful_text, 'success').then(function () {
                    $("[data-dismiss=modal]").trigger({ type: "click" });
                    $('#purchase_edit_form')[0].reset();
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning("Error! Check Your Form Information Please.");
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#purchase_edit_form .form_error').show();
                    $('#purchase_edit_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }
        });
    });




    //edit purchase function
    $(document).on('click', '.edit-purchase', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = '/purchase/' + id;
        var type = 'get';
        tr = $(this).parent().parent();
        tr_id = tr.attr('id');
        tr_sl = $('#' + tr_id + " .serial").text();
        $.ajax({
            type: type,
            url: url,
            data: {},
            processData: false,
            contentType: false,
            success: function (res) {
                $("option.cat_pur").each(function () {
                    if ($(this).val() == res[0]['category_id']) {
                        $(this).attr('selected', 'true');
                    };
                });
                $("option.pro_opt").each(function () {
                    if ($(this).val() == res[0]['product_id']) {
                        $(this).attr('selected', 'true');
                    };
                });
                $("option.van_pur").each(function () {
                    if ($(this).val() == res[0]['vendor']) {
                        $(this).attr('selected', 'true');
                    };
                });
                $('#purchase_edit_form .purchase_id').val(res[0]['id'])
                $('#purchase_edit_form .quantity').val(res[0]['qty'])
                $('#purchase_edit_form .purchase_date').val(res[0]['purchase_date'])
            },
            error: function (errors) {
                toastr.warning("Error! Something went wrong.");
            }
        });
    })
    // Add purchase Function
    $(document).on('submit', '#purchase_add_form', function (event) {
        event.preventDefault();
        $("#purchase_add_form .form_error").css('display', 'none');
        $("#purchase_add_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#purchase_add_form');
        var formData = form.serialize();
        var url = '/purchase/store';
        var type = 'post';
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#purchase_add_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response[1]['id']);
                toastr.success(excellent_text, successful_text)
                var idx = table.rows().count();
                idx++;
                var rowNode = table
                    .row.add(['<b class="serial">' + idx + '</b>', response[1]['product'], response[1]['category'], response[1]['qty'], response[1]['vendor_name'], response[1]['date'],
                        '<div class="action">'+
                        '<button style="" class="btn btn-primary mr-1 view-purchase" data-id="' + response[1]['id'] + '" data-toggle="modal" data-target="#view_purchase_modal"><i class="fas fa-eye"></i></button>'+
                    '<button style="" class="btn btn-danger delete-purchase" data-id="' + response[1]['id'] + '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button></div>'])
                    .order([0, 'dsc']).draw()
                    .node().id = 'item-' + response[1]['id'];
                $(rowNode)
                    .css('color', 'green')
                    .animate({ color: 'red' });
                swal(excellent_text, successful_text, 'success').then(function () {
                    $('#purchase_add_form')[0].reset();
                    $("[data-dismiss=modal]").trigger({ type: "click" });
                    $("#purchase_add_form .form_error").css('display', 'none');
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#purchase_add_form .form_error').show();
                    $('#purchase_add_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }
        });
    });
    ////////////////////// Stock //////////////////////

    // Add opeming stock Function
    $(document).on('submit', '#openingstock_add_form', function (event) {
        event.preventDefault();
        $("#openingstock_add_form .form_error").css('display', 'none');
        $("#openingstock_add_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#openingstock_add_form');
        var formData = form.serialize();
        var url = '/add-opening-stock';
        var type = 'post';
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#openingstock_add_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                toastr.success(excellent_text, successful_text);
                var idx = table.rows().count();
                idx++;
                var rowNode = table
                    .row.add(['<b class="serial">' + idx + '</b>', response[1]['product'], response[1]['opening_stock'], response[1]['in_stock']])
                    .order([0, 'dsc']).draw()
                    .node().id = 'item-' + response[1]['id'];
                $(rowNode)
                    .css('color', 'green')
                    .animate({ color: 'red' });
                swal(excellent_text, successful_text, "success").then(function () {
                    $('#openingstock_add_form')[0].reset();
                    $("[data-dismiss=modal]").trigger({ type: "click" });
                    $("#openingstock_add_form .form_error").css('display', 'none');
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#openingstock_add_form .form_error').show();
                    $('#openingstock_add_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }
        });
    });
})

