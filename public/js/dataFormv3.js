$.ajaxSetup({
    headers: {
        'X_CSRF_TOKEN': $('meta[name="_token"]').attr('content')
    }
});
$(document).ready(function () {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


    var table = $('#example').DataTable();
    // var orderTable = $('#example1').DataTable();
    var id = null;
    var tr_id = null;
    var tr_sl = null;

    var language= $('.btn-lang').text();
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
    var delete_text = (language === "English" )? "আপনি ডিলিট করতে চান?": "You want to delete this?"
//   Get subcategory by selecting category

    jQuery(document).ready(function () {
        jQuery('select[name="Category_Name"]').on('change', function () {
            var countryID = jQuery(this).val();
            if (countryID) {
                jQuery.ajax({
                    url: '/items/getSubCategory/' + countryID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        // console.log(data);
                       var select_language=  (language === "English" )? "বাছাই করুন":"Please Select";
                       console.log(select_language)
                        jQuery('select[name="sub_cat_name"]').empty();
                        $('select[name="sub_cat_name"]').append('<option value=" ">'+select_language+'</option>');
                        jQuery.each(data, function (key, value) {
                            $('select[name="sub_cat_name"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="sub_cat_name"]').empty();
            }
        });
    });


    $(document).on("submit", "#item_add_form", function (event) {
        event.preventDefault();
        $("#item_add_form .form_error").css("display", "none");
        $("#item_add_form .form_error p").remove();
        $("body").addClass("loading");
        var form = $("#item_add_form");
        var formData = form.serialize();
        var url = "/item/store";
        var type = "post";
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#item_add_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                toastr.success(successful_text, excellent_text);
                var idx = table.rows().count();
                idx++;
                var rowNode = (table.row
                    .add([
                        '<b class="serial">' + idx + "</b>",
                        response[1]["name"],
                        response[1]["impa_code"],
                        response[1]["unit"],
                        response[1]["category"],
                        response[1]["created_by"],
                        '<div class="action"><button class="btn btn-info mr-1 edit-item" data-id="' +
                        response[1]["id"] +
                        '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>' +
                        '<button class="btn btn-danger delete-item" data-id="' +
                        response[1]["id"] +
                        '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button></div>',
                    ])
                    .order([0, "dsc"])
                    .draw()
                    .node().id = "item-" + response[1]["id"]);
                $(rowNode).css("color", "green").animate({color: "red"});
                swal(excellent_text, successful_text, "success").then(function () {
                    $("#item_add_form")[0].reset();
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    $("#item_add_form .form_error").css("display", "none");
                });
            },
            error: function (errors) {
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $("#item_add_form .form_error").show();
                    $("#item_add_form .form_error").append(
                        '<p style="margin-bottom:5px;">' + value + "</p>"
                    );
                    $("html, body")
                        .stop()
                        .animate(
                            {
                                scrollTop:
                                    $(".alert-danger").offset().top - 150,
                            },
                            500
                        );
                });
            },
        });
    });

    // For editing item

    $(document).on("click", ".edit-item", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        console.log(id);
        // var url = "/item/" + id;
        var url = '/product/edit-product';
        var type = 'get';
        tr = $(this).parent().parent();
        tr_id = tr.attr("id");
        tr_sl = $("#" + tr_id + " .serial").text();
        $.ajax({
            type: type,
            url: url,
            data: {
                id: id
            },

            success: function (data) {
                // console.log(data.item.category.id,data.sub_category_option);
                $('#item_edit_form').find('#edit_category_id').val(data.item.category.id);
                $('#item_edit_form').find('#edit_sub_cat_id').html(data.sub_category_option);
                $('#item_edit_form').find('#product_name_id').val(data.item.name);
                $('#item_edit_form').find('#impa_code').val(data.item.impa_code);
                $('#item_edit_form').find('#edit_unit_id').val(data.item.unit);
                $('#item_edit_form').find('#id').val(id);

            },
            error: function (errors) {
                toastr.warning(bangla_error);
            }
        });
    });

    // Item update form
    $(document).on("submit", "#item_edit_form", function (event) {
        event.preventDefault();
        $("#item_edit_form .form_error").css("display", "none");
        $("#item_edit_form .form_error p").remove();
        $("body").addClass("loading");
        var form = $("#item_edit_form");
        var formData = form.serialize();
        var url = "/item/update";
        var type = "post";
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#item_edit_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                toastr.success(successful_text, excellent_text);
                $("#item_edit_form")[0].reset();
                var rData = [
                    '<b class="serial">' + tr_sl + "</b>",
                    response[1]["impa_code"],
                    response[1]["name"],
                    response[1].unit,
                    response[2]["name"],
                    response[1]["created_by"],
                    // response[1]["updated_by"],
                    '<button class="btn btn-info edit-item mr-1" data-id="' +
                    response[1]["id"] +
                    '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button><button class="btn btn-danger delete-item" data-id="' +
                    response[1]["id"] +
                    '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>',
                ];
                table
                    .row("tr#" + tr_id)
                    .data(rData)
                    .draw();
                swal(excellent_text, successful_text, "success").then(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    $("#item_edit_form")[0].reset();
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $("#item_edit_form .form_error").show();
                    $("#item_edit_form .form_error").append(
                        '<p style="margin-bottom:5px;">' + value + "</p>"
                    );
                    $("html, body")
                        .stop()
                        .animate(
                            {
                                scrollTop:
                                    $(".alert-danger").offset().top - 150,
                            },
                            500
                        );
                });
            },
        });
    });
    // delete item function()
    $(document).on("click", ".delete-item", function () {
        var tr = $(this).parents("tr");
        var tr_id = tr.attr("id");
        // alert(tr_id+ ' '+ 'tr#'+tr_id);
        var id = $(this).data("id");
        swal({
            title: delete_title,
            text: delete_text,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: bangla_cancel_btn,
            confirmButtonText: bangla_confirm_btn,
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve) {
                    $.ajax({
                        url: "/item/delete",
                        type: "post",
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                        },
                        dataType: "json",
                    })
                        .done(function (response) {
                            swal(excellent_text, bangla_congrats_delete, "success").then(
                                function () {
                                    table
                                        .row("tr#" + tr_id)
                                        .remove()
                                        .draw();
                                }
                            );
                        })
                        .fail(function (response) {
                            swal(bangla_oops, bangla_error, "error");
                        });
                });
            },
            allowOutsideClick: false,
        });
    });

    // add category function


    $(document).on('submit', '#category_add_form', function (event) {
        event.preventDefault();
        $("#category_add_form .form_error").css('display', 'none');
        $("#category_add_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#category_add_form');
        var formData = form.serialize();
        var url = '/category/store';
        var type = 'post';
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#category_add_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response)
                toastr.success( excellent_text, successful_text)
                var idx = table.rows().count();
                idx++;
                var rowNode = table
                    .row.add(['<b class="serial">' + idx + '</b>', response[1]['category'], response[1]['subcategory'],
                        '<div class="action"><button class="btn btn-info mr-1 edit-category" data-id="' + response[1]['id'] + '"data-name="' + response[1]['category'] + '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>' +
                        '<button class="btn btn-danger delete-category" data-id="' + response[1]['id'] + '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button></div>'])
                    .order([0, 'dsc']).draw()
                    .node().id = 'category-' + response[1]['id'];
                $(rowNode)
                    .css('color', 'green')
                    .animate({color: 'red'});
                swal(excellent_text, successful_text, "success").then(function () {
                    $('#category_add_form')[0].reset();
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    $("#category_add_form .form_error").css('display', 'none');
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#category_add_form .form_error').show();
                    $('#category_add_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }
        });
    });


    //Sub category select show hide
    subCategoryToggle();

    function subCategoryToggle() {
        $("#category_div_id").show();
        $("#subcategory_div_id").hide();
        $(document).ready(function () {
            $(":checkbox").click(function (event) {
                if ($('#select_sub_category_id').is(":checked")) {

                    $("#category_div_id").show();
                    $("#subcategory_div_id").show();
                } else {

                    $("#category_div_id").show();
                    $("#subcategory_div_id").hide();
                }
            });
        });
    }

    // Edit category function
    $(document).on('click', '.edit-category', function (e) {
        e.preventDefault();

        tr = $(this).parent().parent();
        tr_id = tr.attr('id');

        var id = $(this).data('id');

        tr_sl = $('#' + tr_id + " .serial").text();

        $("#category_div_id_2").show();
        $("#subcategory_div_id_2").hide();

        $(document).ready(function () {
            $(":checkbox").click(function (event) {
                if ($('#select_sub_category_id_2').is(":checked")) {

                    $("#category_div_id_2").show();
                    $("#subcategory_div_id_2").show();
                } else {

                    $("#category_div_id_2").show();
                    $("#subcategory_div_id_2").hide();
                }
            });
        })

        var url = '/category/edit';

        $.ajax({
            url: url,
            type: "get",
            data: {
                id: id,

            },
            success: function (data) {
                console.log(data.item.parent_id);
                $('#category_edit_form').find('.Category_Name').val(data.item.name);
                $('#category_edit_form').find('#symbol_id').val(data.item.symbol);

                if (data.item.parent_id !== null) {
                    $('#category_edit_form').find('#sub_category_id_2').html(data.dropdowns);


                    console.log('not null')
                    $('#select_sub_category_id_2').prop('checked', true)
                    $("#category_div_id_2").show();
                    $("#subcategory_div_id_2").show();
                } else {

                    console.log('null')
                    $('#category_edit_form').find('#sub_category_id_2').html(data.normal_selects);

                    $('#select_sub_category_id_2').prop('checked', false)

                    $("#category_div_id_2").show();
                    $("#subcategory_div_id_2").hide();
                }

                $('#category_edit_form').find('#id').val(id);

            }
        });

    })

    // Category update function
    $(document).on('submit', '#category_edit_form', function (event) {
        event.preventDefault();
        $("#category_edit_form .form_error").css('display', 'none');
        $("#category_edit_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#category_edit_form');
        var formData = form.serialize();
        var url = '/category/update';
        var type = 'post';
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#category_edit_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {

                var sub_category_name = '';

                if (response[1].parentcat === null) {
                    sub_category_name = "N/A";
                } else {
                    sub_category_name = response[1].parentcat.name
                }

                toastr.success(excellent_text, successful_text);
                $('#category_edit_form')[0].reset();
                var rData = [
                    '<b class="serial">' + tr_sl + '</b>',
                    sub_category_name,
                    response[1]['name'],

                    '<button class="btn btn-info edit-category mr-1" data-id="' + response[1]['id'] + '" data-name="' + response[1]['name'] + '"  data-symbol="' + response[1]['symbol'] + '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button><button class="btn btn-danger delete-category" data-id="' + response[1]['id'] + '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>'
                ];
                table
                    .row('tr#' + tr_id)
                    .data(rData)
                    .draw();
                swal(excellent_text, successful_text, "success").then(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    $('#category_edit_form')[0].reset();
                });
            },
            error: function (errors) {
                // console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#category_edit_form .form_error').show();
                    $('#category_edit_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }
        });
    });
// delete category function()
    $(document).on('click', '.delete-category', function () {
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
                        url: '/category/delete',
                        type: 'post',
                        data: {
                            _token: CSRF_TOKEN,
                            'id': id,
                        },
                        dataType: 'json'
                    })
                        .done(function (response) {
                            swal(excellent_text, bangla_congrats_delete, "success").then(function () {
                                table
                                    .row("tr#" + tr_id)
                                    .remove()
                                    .draw();
                            });
                        })
                        .fail(function (response) {
                            swal(bangla_oops, bangla_error, "error");
                        });
                });
            },
            allowOutsideClick: false
        });
    });

    $(document).on('click', '.close_error_alert', function () {
        $(this).parent().hide();
    });


    $(document).on('click', '.back', function () {
        $('.active').parent().prev('li').find('a').tab('show');

        $('html, body').stop().animate({
            scrollTop: $('.form-wrap').offset().top - 150
        }, 500);
    })


// Add User Function
    $(document).on('submit', '#user_add_form', function (event) {
        event.preventDefault();
        $("#user_add_form .form_error").css('display', 'none');
        $("#user_add_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#user_add_form');
        var formData = form.serialize();
        var url = '/user/store';
        var type = 'post';
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#user_add_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                toastr.success(excellent_text, successful_text);
                var idx = table.rows().count();
                idx++;
                var rowNode = table
                    .row.add(['<b class="serial">' + idx + '</b>', response[1]['name'], response[1]['username'],response[1]['email'], response[1].role.role, response[1].dept.name,response[1].designation,
                        '<div class="action"><button class="btn btn-info mr-1 edit-user" data-id="' + response[1]['id'] + '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>' +
                        '<button class="btn btn-danger delete-user" data-id="' + response[1]['id'] + '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button><div>'])
                    .order([0, 'dsc']).draw()
                    .node().id = 'user-' + response[1]['id'];
                $(rowNode)
                    .css('color', 'green')
                    .animate({color: 'red'});
                swal('Excellent!', response[0], 'success').then(function () {
                    $('#user_add_form')[0].reset();
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    $("#user_add_form .form_error").css('display', 'none');
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#user_add_form .form_error').show();
                    $('#user_add_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }
        });
    });
// delete user  function()
    $(document).on('click', '.delete-user', function () {
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
                        url: '/user/delete',
                        type: 'post',
                        data: {
                            _token: CSRF_TOKEN,
                            'id': id,
                        },
                        dataType: 'json'
                    })
                        .done(function (response) {
                            swal(excellent_text, bangla_congrats_delete, "success").then(function () {
                                table
                                    .row("tr#" + tr_id)
                                    .remove()
                                    .draw();
                            });
                        })
                        .fail(function (response) {
                            swal(bangla_oops, bangla_error, "error");
                        });
                });
            },
            allowOutsideClick: false
        });
    });

// update order function()
    $(document).on('click', '#update_order_status', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var status = $('#order_status').children("option:selected").val();
        swal({
            title: 'Are you sure?',
            text: "You want to update this order status!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve) {
                    $.ajax({
                        url: '/order/status/update',
                        type: 'post',
                        data: {
                            _token: CSRF_TOKEN,
                            'id': id,
                            'status': status,
                        },
                        dataType: 'json'
                    })
                        .done(function (response) {
                            swal('Wel Done!', response[0], 'success').then(function () {
                                // window.location.href='/delivered/requisition';
                            })
                                .fail(function (response) {
                                    swal('Oops...', 'Something went wrong!', 'error');
                                });
                        });
                });
            },
            allowOutsideClick: false
        });
    });
    // edit user function
    $(document).on('click', '.edit-user', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        // var url = '/user/' + id;

        console.log(id)
        var url = '/user/edit';
        var type = 'get';
        tr = $(this).parent().parent();
        tr_id = tr.attr('id');
        tr_sl = $('#' + tr_id + " .serial").text();
        $.ajax({
            type: type,
            url: url,
            data: {
                id: id
            },
            success: function (data) {
                $('#user_edit_form').find('#edit_name_id').val(data.item.name);
                $('#user_edit_form').find('#edit_email_id').val(data.item.email);
                $('#user_edit_form').find('#edit_user_role_id').val(data.item.role.id);
                $('#user_edit_form').find('#edit_username').val(data.item.username);
                $('#user_edit_form').find('#edit_department_id').val(data.item.dept.id);
                $('#user_edit_form').find('#edit_designation').val(data.item.designation);
                $('#user_edit_form').find('#edit_limit').val(data.item.req_limit);
                $('#user_edit_form').find('#edituserid').val(id);
            },
            error: function (errors) {
                toastr.warning(error_text);
            }
        });
    })

    $(document).on('click', '.edit-order', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        // var url = '/user/' + id;
        var url = '/edit-pending/edit-pending-requisition';
        var type = 'get';
        tr = $(this).parent().parent();
        tr_id = tr.attr('id');
        tr_sl = $('#' + tr_id + " .serial").text();
        $.ajax({
            type: type,
            url: url,
            data: {
                id: id
            },

            success: function (data) {

                $('#order_edit_form').find('#edit_name_id').val(data.item.name);
                $('#user_edit_form').find('#edit_email_id').val(data.item.email);
                $('#user_edit_form').find('#edit_user_role_id').val(data.item.role.id);
                $('#user_edit_form').find('#edit_department_id').val(data.item.dept.id);
                $('#user_edit_form').find('#id').val(id);

            },
            error: function (errors) {
                toastr.warning(error_text);
            }
        });
    })




    // user update form function
    $(document).on('submit', '#user_edit_form', function (event) {
        event.preventDefault();
        $("#user_edit_form .form_error").css('display', 'none');
        $("#user_edit_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#user_edit_form');
        var formData = form.serialize();
        var url = '/user/update';
        var type = 'post';
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#user_edit_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                toastr.success(excellent_text, successful_text);
                $('#user_edit_form')[0].reset();
                var rData = [
                    '<b class="serial">' + tr_sl + '</b>',
                    response[1]['name'],
                    response[1]['username'],
                    response[1]['email'],
                    response[1].role.role, response[1].dept.name, response[1].designation,
                    '<button class="btn btn-info edit-user mr-1" data-id="' + response[1]['id'] + '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button><button class="btn btn-danger delete-user" data-id="' + response[1]['id'] + '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>'
                ];
                table
                    .row('tr#' + tr_id)
                    .data(rData)
                    .draw();
                swal(excellent_text, successful_text, "success").then(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    $('#user_edit_form')[0].reset();
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#user_edit_form .form_error').show();
                    $('#user_edit_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }
        });
    });



    $(document).on('submit', '#requisition_search_form', function (event) {
        event.preventDefault();
        $("#requisition_search_form .form_error").css('display', 'none');
        $("#requisition_search_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#requisition_search_form');
        var formData = form.serialize();
        var url = '/search/requisition';
        var type = 'post';
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#requisition_search_form")[0]),
            processData: false,
            contentType: false,
            success: function (data) {

                console.log(data.table);

                $('#report_requisition').html(data.table);


            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#requisition_search_form .form_error').show();
                    $('#requisition_search_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }
        });
    });

    $(document).on('submit', '#purchase_search_form', function (event) {
        event.preventDefault();
        $("#purchase_search_form .form_error").css('display', 'none');
        $("#purchase_search_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#purchase_search_form');
        var formData = form.serialize();
        var url = '/search/total-purchase';
        var type = 'post';
        var purchase_report_table = $("#example").DataTable();
        purchase_report_table.clear().draw();
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#purchase_search_form")[0]),
            processData: false,
            contentType: false,
            success: function (data) {

                console.log(data.table);
                // orderTable.clear();

                $.each(data.purchases, function(key, value) {
                    (purchase_report_table.row
                        .add([
                            value.item.name,
                            value.qty,
                            value.purchase_date,

                        ])
                        .order([0, "asc"])
                        .draw()
                        .node().id =  value.id);
                });

                // $('#report_purchase').html(data.table);


            },

            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#purchase_search_form .form_error').show();
                    $('#purchase_search_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }

        });


    });



    $(document).on('submit', '#report_received_product_search_form', function (event) {
        event.preventDefault();
        $("#report_received_product_search_form .form_error").css('display', 'none');
        $("#report_received_product_search_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#report_received_product_search_form');
        var formData = form.serialize();
        var url = '/search/total-received-product';
        var type = 'post';
        var received_product_table = $("#example").DataTable();
        received_product_table.clear().draw();
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#report_received_product_search_form")[0]),
            processData: false,
            contentType: false,
            success: function (data) {

                // alert('hi')
                console.log(data.lists);
                // orderTable.clear();

                $.each(data.lists, function(key, value) {
                    (received_product_table.row
                        .add([
                            value.name,
                            value.unit,
                            value.received_qty,
                            new Date(value.updated_at).toLocaleDateString()
                        ])
                        .order([0, "asc"])
                        .draw()
                        .node().id =  value.id);
                });
            },

            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#report_received_product_search_form .form_error').show();
                    $('#report_received_product_search_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }

        });


    });


    $(document).on('submit', '#stock_search_form', function (event) {
        event.preventDefault();
        $("#stock_search_form .form_error").css('display', 'none');
        $("#stock_search_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#stock_search_form');
        var formData = form.serialize();
        var url = '/search/stock';
        var type = 'post';
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#stock_search_form")[0]),
            processData: false,
            contentType: false,
            success: function (data) {
                $('#report_stock').html(data.table);
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#stock_search_form .form_error').show();
                    $('#stock_search_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }
        });
    });
    
    $(document).on('submit', '#total_delivered_search_form', function (event) {
        event.preventDefault();
        $("#stock_search_form .form_error").css('display', 'none');
        $("#stock_search_form .form_error p").remove();
        $('body').addClass("loading");
        var form = $('#total_delivered_search_form');
        var formData = form.serialize();
        var url = '/search/total-delivered';
        var type = 'post';
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#total_delivered_search_form")[0]),
            processData: false,
            contentType: false,
            success: function (data) {
                $('#report_total_delivered_id').html(data.table);
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $('#total_delivered_search_form .form_error').show();
                    $('#total_delivered_search_form .form_error').append('<p style="margin-bottom:5px;">' + value + '</p>');
                    $('html, body').stop().animate({
                        scrollTop: $('.alert-danger').offset().top - 150
                    }, 500);
                });
            }
        });
    });

}); // End document.ready




