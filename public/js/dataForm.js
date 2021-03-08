$.ajaxSetup({
    headers: {
        X_CSRF_TOKEN: $('meta[name="_token"]').attr("content"),
    },
});
$(document).ready(function () {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    function readURL(input) {
        imgId = "#prev_" + $(input).attr("id");
        if (input.files && input.files[0].size / 1024 / 1024 < 10) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(imgId).attr("src", e.target.result);
                $(imgId).attr("hidden", false);
                $(imgId).css({
                    display: "inline-block",
                    height: "auto",
                    padding: "10px 0px",
                    "max-width": "70%",
                    width: "auto",
                    "max-height": "200px",
                });
                $("img.app_page").css({ height: "220px" });
            };
            reader.readAsDataURL(input.files[0]);
            $(input).siblings("div.err_msg").attr("hidden", true);
        } else {
            $(input).val("");
            $(imgId).attr("hidden", true);
            $(input)
                .siblings("div .err_msg")
                .find("span")
                .text(
                    "File size exceeds 300 KB.Please reduce file size less than 300 kb"
                );
            $(input).siblings("div .err_msg").attr("hidden", false);
        }
    }
    $("form.form input[type='file']").change(function () {
        readURL(this);
        $(this).on("click", function () {
            imgId = "#prev_" + $(this).attr("id");
            $(imgId).attr("hidden", true);
        });
    });



    var language= $('.btn-lang').text();
    // console.log(language);
    var bangla_confirm_btn = (language === "English" )? "হ্যা":"Yes, delete it!";
    var bangla_cancel_btn = (language === "English" )? "না":"Cancel";
    var bangla_congrats = language==="English"?"অভিনন্দন":"Well Done!";
    var bangla_congrats_delete = (language === "English" )?"ডিলিট সম্পন্ন হয়েছে":"Your information has been deleted successfully!";
    var bangla_oops = (language === "English" )?"দুঃখিত":"Oops...";
    var bangla_error = (language === "English" )?"কিছু একটা ভুল দেখা দিয়েছে": "An error has occurred";

    var excellent_text = language==="English"?"অভিনন্দন!" :"Excellent";
    var successful_text = language==="English"?"আপনার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে।" :"Your information has been saved successfully";
    var error_text = language==="English"?"দুঃখিত! আপনার তথ্যগুলো পুনরায় পরীক্ষা করুন" :"Error! Check Your Form Information Please.";
    var delete_title = (language === "English" )? "আপনি কি নিশ্চিত?":'Are you sure?'
    var delete_text = (language === "English" )? "আপনি কি ডিলিট করতে চান?": "You want to delete this?";
    var requisition_submit_text = (language === "English" )? "রিকুইজিশন সাবমিট এর ব্যাপারে আপনি নিশ্চিত?": "Are you sure you want to submit this requisition?";
    var requisition_update_text = (language === "English" )? "রিকুইজিশন আপডেট এর ব্যাপারে আপনি নিশ্চিত?": "Are you sure want to update this requisition?";
    var requisition_deliver_text = (language === "English" )? "আপনি রিকুইজিশন ডেলিভার করতে চান?": "You want to deliver this Requisition?";
    var update_text = (language === "English" )? "আপনি আপডেট করতে চান?": "You want to update this order status?";

    var approveRequisition = (language === "English" )? "আপনি এই রিকুইজিশন অনুমোদন করতে চান?":"You want to approve this Requisition?"
    var confirmation_approve = (language === "English" )? "রিকুইজিশন এপ্রুভ করা হয়েছে।":"The requisition has been approved";
    var forward_requisition_text = (language === "English" )? "আপনি রিকুইজিশনটি ফরোওয়ার্ড করতে চান?":"You want to forward this Requisition?";
    var reject_requisition_title = (language === "English" )? "রিকুইজিশনটি রিজেক্ট এর ব্যাপারে আপনার কারণ সাবমিট করুন":"Please submit your reason to reject this requisition";

    var excellent = (language === "English" )? "অভিনন্দন!" :"Excellent!";
    var submit_text = (language === "English" )? "সাবমিট" :"Submit";
    var received_requisition_text = (language === "English" )? "আপনি রিকুইজিশনটি রিসিভ করেছেন?" :"You received this Requisition?";

    var table = $("#example").DataTable({
        stateSave: true,
        "bDestroy": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        "language": {
            "emptyTable": "কোন তথ্য খুজে পাওয়া যায় নি"
          }
    });

    var orderTable = $("#example1").DataTable();
    var id = null;
    var tr_id = null;
    var tr_sl = null;


    $(document).on("click", ".back", function () {
        $(".active").parent().prev("li").find("a").tab("show");

        $("html, body")
            .stop()
            .animate(
                {
                    scrollTop: $(".form-wrap").offset().top - 150,
                },
                500
            );
    });

    // -------------------Vendor by Tuhin-----------------

    // Add vendor Function
    $(document).on("submit", "#vendor_add_form", function (event) {
        event.preventDefault();
        $("#vendor_add_form .form_error").css("display", "none");
        $("#vendor_add_form .form_error p").remove();
        $("body").addClass("loading");
        var form = $("#vendor_add_form");
        var formData = form.serialize();
        var url = "/vendor/store";
        var type = "post";
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#vendor_add_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                // toastr.success("New Vendor Added Successfully!", "Well Done!");
                var idx = table.rows().count();
                idx++;
                var rowNode = (table.row
                    .add([
                        '<b class="serial">' + idx + "</b>",
                        response[1]["name"],
                        response[1]["phone_no"],
                        response[1]["email"],
                        response[1]["address"],
                        response[1]["contact_person"],
                        response[1]["created_by"],
                        '<div class="action"><button class="btn btn-info mr-1 edit-vendor" data-id="' +
                            response[1]["id"] +
                            '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>' +
                            '<button class="btn btn-danger delete-vendor" data-id="' +
                            response[1]["id"] +
                            '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button></div>',
                    ])
                    .order([0, "dsc"])
                    .draw()
                    .node().id = "vendor-" + response[1]["id"]);
                $(rowNode).css("color", "green").animate({ color: "red" });
                swal(excellent, (language === "English" )? "নতুন বিক্রেতা সফলভাবে সংরক্ষণ করা হয়েছে" :response[0], "success").then(function () {
                    $("#vendor_add_form")[0].reset();
                    $("[data-dismiss=modal]").trigger({ type: "click" });
                    $("#vendor_add_form .form_error").css("display", "none");
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning("Error! Check Your Form Information Please.");
                $.each(errors.responseJSON.errors, function (key, value) {
                    $("#vendor_add_form .form_error").show();
                    $("#vendor_add_form .form_error").append(
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

    // Edit vendor
    $(document).on("click", ".edit-vendor", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var url = "/vendor/" + id;
        var type = "get";
        tr = $(this).parent().parent();
        tr_id = tr.attr("id");
        tr_sl = $("#" + tr_id + " .serial").text();
        $.ajax({
            type: type,
            url: url,
            data: {},
            processData: false,
            contentType: false,
            success: function (res) {
                $("#vendor_edit_form .name").val(res[0]["name"]);
                $("#vendor_edit_form .phone_no").val(res[0]["phone_no"]);
                $("#vendor_edit_form .email").val(res[0]["email"]);
                $("#vendor_edit_form .address").val(res[0]["address"]);
                $("#vendor_edit_form .contact_person").val(
                    res[0]["contact_person"]
                );
                $("#vendor_edit_form .vendor_id").val(res[0]["id"]);
            },
            error: function (errors) {
                toastr.warning("Error! Something went wrong.");
            },
        });
    });
    $(document).on("submit", "#vendor_edit_form", function (event) {
        event.preventDefault();
        $("#vendor_edit_form .form_error").css("display", "none");
        $("#vendor_edit_form .form_error p").remove();
        $("body").addClass("loading");
        var form = $("#vendor_edit_form");
        var formData = form.serialize();
        var url = "/vendor/update";
        var type = "post";
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#vendor_edit_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                // toastr.success("Vendor Updated Successfully!", "Well Done!");
                $("#vendor_edit_form")[0].reset();
                var rData = [
                    '<b class="serial">' + tr_sl + "</b>",
                    response[1]["name"],
                    response[1]["phone_no"],
                    response[1]["email"],
                    response[1]["address"],
                    response[1]["contact_person"],
                    response[1]["created_by"],
                    '<button class="btn btn-info edit-vendor mr-1" data-id="' +
                        response[1]["id"] +
                        '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button><button class="btn btn-danger delete-vendor" data-id="' +
                        response[1]["id"] +
                        '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>',
                ];
                table
                    .row("tr#" + tr_id)
                    .data(rData)
                    .draw();
                swal(excellent, (language === "English" )? "অনুরোধ করা বিক্রেতা সফলভাবে আপডেট হয়েছে" :response[0], "success").then(function () {
                    $("[data-dismiss=modal]").trigger({ type: "click" });
                    $("#vendor_edit_form")[0].reset();
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning("Error! Check Your Form Information Please.");
                $.each(errors.responseJSON.errors, function (key, value) {
                    $("#vendor_edit_form .form_error").show();
                    $("#vendor_edit_form .form_error").append(
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

    // delete vendor function()
    $(document).on("click", ".delete-vendor", function () {
        var tr = $(this).parents("tr");
        var tr_id = tr.attr("id");
        // alert(tr_id+ ' '+ 'tr#'+tr_id);
        var id = $(this).data("id");
        console.log(language)
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
                        url: "/vendor/delete",
                        type: "post",
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                        },
                        dataType: "json",
                    })
                        .done(function (response) {
                            // swal((language === "বাংলা" )? bangla_congrats :"Wel Done!", response[0], "success").then(
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
    // --------------------End Vendor----------------------

    // -------------------Department by Tuhin-----------------
    // Add Department Function
    $(document).on("submit", "#department_add_form", function (event) {
        event.preventDefault();
        $("#department_add_form .form_error").css("display", "none");
        $("#department_add_form .form_error p").remove();
        $("body").addClass("loading");
        var form = $("#department_add_form");
        var formData = form.serialize();
        var url = "/department/store";
        var type = "post";
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#department_add_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                toastr.success(excellent_text, successful_text);
                var idx = table.rows().count();
                idx++;
                var rowNode = (table.row
                    .add([
                        '<b class="serial">' + idx + "</b>",
                        response[1]["name"],
                        response[1]["dept_code"],
                        response[1]["details"],
                        // response[1]["created_by"],
                        '<div class="action"><button class="btn btn-info mr-1 edit-department" data-id="' +
                            response[1]["id"] +
                            '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>' +
                            '<button class="btn btn-danger delete-department" data-id="' +
                            response[1]["id"] +
                            '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button></div>',
                    ])
                    .order([0, "dsc"])
                    .draw()
                    .node().id = "department-" + response[1]["id"]);
                $(rowNode).css("color", "green").animate({ color: "red" });
                // swal("Excellent!", response[0], "success").then(function () {
                swal(excellent_text, successful_text, "success").then(function () {
                    $("#department_add_form")[0].reset();
                    $("[data-dismiss=modal]").trigger({ type: "click" });
                    $("#department_add_form .form_error").css(
                        "display",
                        "none"
                    );
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $("#department_add_form .form_error").show();
                    $("#department_add_form .form_error").append(
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

    // Edit Department
    $(document).on("click", ".edit-department", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var url = "/department/" + id;
        var type = "get";
        tr = $(this).parent().parent();
        tr_id = tr.attr("id");
        tr_sl = $("#" + tr_id + " .serial").text();
        $.ajax({
            type: type,
            url: url,
            data: {},
            processData: false,
            contentType: false,
            success: function (res) {
                $("#department_edit_form .name").val(res[0]["name"]);
                $("#department_edit_form .dept_code").val(res[0]["dept_code"]);
                $("#department_edit_form .details").val(res[0]["details"]);
                $("#department_edit_form .department_id").val(res[0]["id"]);
            },
            error: function (errors) {
                toastr.warning(error_text);
            },
        });
    });
    $(document).on("submit", "#department_edit_form", function (event) {
        event.preventDefault();
        $("#department_edit_form .form_error").css("display", "none");
        $("#department_edit_form .form_error p").remove();
        $("body").addClass("loading");
        var form = $("#department_edit_form");
        var formData = form.serialize();
        var url = "/department/update";
        var type = "post";
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#department_edit_form")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                toastr.success(successful_text, excellent_text);
                $("#department_edit_form")[0].reset();
                var rData = [
                    '<b class="serial">' + tr_sl + "</b>",
                    response[1]["name"],
                    response[1]["dept_code"],
                    response[1]["details"],
                    // response[1]["created_by"],
                    '<button class="btn btn-info edit-department mr-1" data-id="' +
                        response[1]["id"] +
                        '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button><button class="btn btn-danger delete-department" data-id="' +
                        response[1]["id"] +
                        '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>',
                ];
                table
                    .row("tr#" + tr_id)
                    .data(rData)
                    .draw();
                swal(excellent_text, successful_text, "success").then(function () {
                    $("[data-dismiss=modal]").trigger({ type: "click" });
                    $("#department_edit_form")[0].reset();
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning(error_text);
                $.each(errors.responseJSON.errors, function (key, value) {
                    $("#department_edit_form .form_error").show();
                    $("#department_edit_form .form_error").append(
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

    // delete vendor function()
    $(document).on("click", ".delete-department", function () {
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
                        url: "/department/delete",
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

    $(document).on("click", ".delete-order", function () {
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
                        url: "/pending-requisition/delete",
                        type: "post",
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                        },
                        dataType: "json",
                    })
                        .done(function (response) {
                            swal(excellent_text, bangla_congrats_delete, 'success').then(
                                function () {
                                    table
                                        .row("tr#" + tr_id)
                                        .remove()
                                        .draw();
                                }
                            );
                        })
                        .fail(function (response) {
                            swal(bangla_oops, bangla_error, 'error');
                        });
                });
            },
            allowOutsideClick: false,
        });
    });

    $(document).on("change", "#cate_name", function () {
        $("img.field-loader").css({ display: "block" });
        var cat_id = $(this).children("option:selected").val();
        console.log(cat_id);
        $("option.item_opt").remove();
        url = "/get-items/" + cat_id;
        $.ajax({
            type: "get",
            url: url,
            data: {
                id: cat_id,
            },
            success: function (response) {
                $("img.field-loader").css({ display: "none" });
                $.each(response, function (key, value) {
                    $("option.item_opt_default").after(
                        "<option class='item_opt' value='" +
                            value["id"] +
                            "' data-unit='" +
                            value["unit"] +
                            "' data-stock='" +
                            value["stock"] +
                            "' data-impa='" +
                            value["impa_code"] +
                            "' >" +
                            value["name"] +
                            "</option>"
                    );
                });
                $("option.searched_item_opt").remove();
            },
        });
    });
    $(document).on("change", "#Item_Name", function () {
     let current_stock= parseInt($("#Item_Name").children("option:selected").data("stock"));
     if(current_stock>0){
        $('.available_stock').text('YES')
     }else{
        $('.available_stock').text('NO')
     }
    })
    $(document).on("click", "#order_add", function (e) {
        e.preventDefault();
        var item_name = $("#Item_Name").children("option:selected").text();
        var unit = $("#Item_Name").children("option:selected").data("unit");
        var item_id = $("#Item_Name").children("option:selected").val();

        var cat_name = $("#cate_name").children("option:selected").text();
        var cat_id = $("#cate_name").children("option:selected").val();
        var qty = $("#item_qty").val();

        var exist_item_count = 0;
        if (qty != "" && item_id != "") {
            $("#example1 > tbody  > tr").each(function () {
                if ($(this).attr("id") == "row_ordered_item-" + item_id) {
                    exist_item_count += 1;
                }
            });
            if (exist_item_count == 0) {
                var idx = orderTable.rows().count();
                idx++;
                var rowNode = (orderTable.row
                    .add([
                        '<b class="serial">' + idx + "</b>",
                        item_name +
                            '<input type="hidden" name="item_id[]" value="' +
                            item_id +
                            '">',
                        '<span class="added_qty">' +
                            qty +
                            "</span>" +
                            '<input type="hidden" class="form-control qty-edit" name="item_qty[]" value="' +
                            qty +
                            '">',
                        unit,
                        cat_name,
                        '<button class="btn btn-info mr-1 edit-order-item" data-id="' +
                            item_id +
                            '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>' +
                            '<button class="btn btn-danger delete-order-item" data-id="' +
                            item_id +
                            '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>',
                    ])
                    .order([0, "asc"])
                    .draw()
                    .node().id = "row_ordered_item-" + item_id);
                var rows = $("#example1 tbody").html();
                console.log(rows);
                localStorage.setItem("orderItemRows", rows);
                $("#item_qty").val(1);
                $("#Item_Name").prop("selectedIndex", 0);
            } else {
                alert("this item already added");
            }
        }

        else {
            return  false;
        }
    });

    var orderedItemRows = localStorage.getItem("orderItemRows");
    if (orderedItemRows != null && orderTable.rows().count()==0) {
        //  orderTable.rows.add($(orderedItemRows)).draw();
        // $('table.orderedItemTable tbody').append(orderedItemRows);
    }
    var edititemRowId = "";
    var editItemname = "";
    var editItemId = "";
    var editUnit = "";
    var editCat = "";
    var edititemRowId = "";
    $(document).on("click", ".edit-order-item", function (e) {
        e.preventDefault();
        edititemRowId = $(this).parent().parent().attr("id");
        $("tr#" + edititemRowId + " .qty-edit").attr("type", "number");
        $("tr#" + edititemRowId + " .added_qty").hide();
        editItemname = $("tr#" + edititemRowId + " td")
            .eq(1)
            .text();
        editItemId = $(this).data("id");
        editUnit = $("tr#" + edititemRowId + " td")
            .eq(3)
            .text();
        editCat = $("tr#" + edititemRowId + " td")
            .eq(4)
            .text();

    });

    $(document).on("change", ".qty-edit", function (e) {
        e.preventDefault();
        var value = $(this).val();
        edititemRowId = $(this).parent().parent().attr("id");
            $("tr#" + edititemRowId + " .qty-edit").attr("type", "hidden");
            $("tr#" + edititemRowId + " .added_qty")
                .text(value)
                .show();

            tr = $(this).parent().parent();
            tr_id = tr.attr("id");
            tr_sl = $("#" + tr_id + " .serial").text();
            var rData = [
                '<b class="serial">' + tr_sl + "</b>",
                editItemname +
                    '<input type="hidden" name="item_id[]" value="' +
                    editItemId +
                    '">',
                '<span class="added_qty">' +
                    value +
                    "</span>" +
                    '<input type="hidden" class="form-control qty-edit" name="item_qty[]" value="' +
                    value +
                    '">',
                editUnit,
                editCat,
                '<button class="btn btn-info mr-1 edit-order-item" data-id="' +
                    editItemId +
                    '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>' +
                    '<button class="btn btn-danger delete-order-item" data-id="' +
                    editItemId +
                    '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>',
            ];
            orderTable
                .row("tr#" + tr_id)
                .data(rData)
                .draw();
            localStorage.removeItem("orderItemRows");
            var rows = $("table.orderedItemTable tbody").html();
            var idx = orderTable.rows().count();
            if (idx > 0) {
                localStorage.setItem("orderItemRows", rows);
            }
    });

    $(document).on("click", ".delete-order-item", function (e) {
        e.preventDefault();
        if (!confirm(delete_title)) {
            return false;
        } else {
            var itemRowId = $(this).parent().parent().attr("id");
            orderTable
                .row("tr#" + itemRowId)
                .remove()
                .draw();
            swal(
                excellent_text, bangla_congrats_delete, 'success'
            ).then(function () {});
            localStorage.removeItem("orderItemRows");
            var rows = $("table.orderedItemTable tbody").html();
            var idx = orderTable.rows().count();
            if (idx > 0) {
                localStorage.setItem("orderItemRows", rows);
            }
        }
    });
    $(document).on("submit", "#req-list", function (event) {
        event.preventDefault();
        $('#reqdetail').modal('show');
        $("#req-list-body").empty();
        $("#req-list-body").append(localStorage.getItem("orderItemRows"));
    })
    //submit new order
    $(document).on("submit", "#add_order_form", function (event) {

        event.preventDefault();
        $("#add_order_form .form_error").css("display", "none");
        $("#add_order_form .form_error p").remove();
        $("body").addClass("loading");
        var form = $("#add_order_form");
        var formData = form.serialize();
        console.log(form);
        var url = "/order/store";
        var type = "post";
        swal({
            title: delete_title,
            text: requisition_submit_text,
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
                        type: type,
                        url: url,
                        data: new FormData($("#add_order_form")[0]),
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response[0] == "success") {
                                $('#reqdetail').modal('hide');
                                toastr.success(excellent_text, successful_text);
                                localStorage.removeItem("orderItemRows");
                                localStorage.removeItem("orderInfo");
                                swal(excellent_text, successful_text, 'success').then(
                                    function () {
                                        $("#add_order_form")[0].reset();
                                        window.location.href = "/pending/requisition";
                                    }
                                );
                            } else if (response[0] == "fail") {
                                toastr.warning(error_text, "Error!");

                                swal(bangla_oops,response[1]?response[1]:bangla_error,"warning").then(function () {});
                            }
                        },
                        error: function (errors) {
                            $('#reqdetail').modal('hide');
                            console.log(errors.responseJSON.errors);
                            toastr.warning(error_text);
                            $.each(errors.responseJSON.errors, function (key, value) {
                                $("#add_order_form .form_error").show();
                                $("#add_order_form .form_error").append(
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
            },
            allowOutsideClick: false,
        });


    });
    //update order
    $(document).on("submit", "#update_order_form", function (event) {

        event.preventDefault();
        $("#update_order_form .form_error").css("display", "none");
        $("#update_order_form .form_error p").remove();
        $("body").addClass("loading");
        var form = $("#update_order_form");
        var formData = form.serialize();
        console.log(form);
        var url = "/order/update";
        var type = "post";
        swal({
            title: delete_title,
            text: requisition_update_text,
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
                        type: type,
                        url: url,
                        data: new FormData($("#update_order_form")[0]),
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response[0] == "success") {
                                $('#reqdetail').modal('hide');
                                toastr.success(excellent_text, successful_text);
                                localStorage.removeItem("orderItemRows");
                                localStorage.removeItem("orderInfo");
                                swal(excellent_text, successful_text, 'success').then(
                                    function () {
                                        $("#update_order_form")[0].reset();
                                        window.location.href = "/pending/requisition";
                                    }
                                );
                            } else if (response[0] == "fail") {
                                toastr.warning(error_text, "Error!");
                                swal(bangla_oops, bangla_error, "warning").then(function () {});
                            }
                        },
                        error: function (errors) {
                            $('#reqdetail').modal('hide');
                            console.log(errors.responseJSON.errors);
                            toastr.warning(error_text);
                            $.each(errors.responseJSON.errors, function (key, value) {
                                $("#update_order_form .form_error").show();
                                $("#update_order_form .form_error").append(
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
            },
            allowOutsideClick: false,
        });


    });


    // approve order
    $(document).on("click", "#approve_order", function () {
        var id = $(this).data("id");
        var deliver_qty = $('.deliver_qty').serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});


            swal({
                title: delete_title,
                text: approveRequisition,
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
                            url: "/order/approve",
                            type: "post",
                            data: {
                                _token: CSRF_TOKEN,
                                id: id,
                                deliver_qty: deliver_qty,
                            },
                            dataType: "json",
                        })
                            .done(function (response) {
                                swal(excellent_text, confirmation_approve,
                                    "success"
                                ).then(function () {
                                    window.location.href = "/approved/requisition";
                                });
                            })
                            .fail(function (response) {
                                swal(bangla_oops, bangla_error, "error");
                            });
                    });
                },
                allowOutsideClick: false,
            });

        });

    // deliver order
    $(document).on("click", "#delivered_order", function () {
        var id = $(this).data("id");
        var stock_delivered_qnty = $('.stock_delivered_qnty').serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});

        swal({
            title: delete_title,
            text: requisition_deliver_text,
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
                        url: "/order/delivered",
                        type: "post",
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                            stock_delivered_qnty: stock_delivered_qnty,
                        },
                        dataType: "json",
                    })
                        .done(function (response) {
                            swal(
                                excellent_text, successful_text, 'success'
                            ).then(function () {
                                window.location.href = "/approved/requisition";
                            });
                        })
                        .fail(function (response) {
                            swal(bangla_oops, bangla_error, 'error');
                        });
                });
            },
            allowOutsideClick: false,
        });

    });

    // received order
    $(document).on("click", "#received_order", function () {
        var id = $(this).data("id");
        var stock_received_qnty = $('.stock_received_qnty').serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});

        swal({
            title: delete_title,
            text: received_requisition_text,
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
                        url: "/order/received",
                        type: "post",
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                            stock_received_qnty: stock_received_qnty,
                        },
                        dataType: "json",
                    })
                        .done(function (response) {
                            swal(
                                excellent_text, successful_text, 'success'
                            ).then(function () {
                                window.location.href = "/approved/requisition";
                            });
                        })
                        .fail(function (response) {
                            swal(bangla_oops, bangla_error, 'error');
                        });
                });
            },
            allowOutsideClick: false,
        });

    });

    // reject order
    $(document).on("click", "#reject_order", function () {
        var id = $(this).data("id");
        swal({
            title: reject_requisition_title,
            input: 'textarea',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: submit_text,
            cancelButtonText: bangla_cancel_btn,
            showLoaderOnConfirm: true,
            preConfirm: (res)=>{
                return new Promise(function (resolve) {
                    $.ajax({
                        url: "/order/reject",
                        type: "post",
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                            reason:res
                        },
                        dataType: "json",
                    })
                        .done(function (response) {
                            swal(
                                excellent_text, successful_text, 'success'
                            ).then(function () {
                                window.location.href = "/pending/requisition";
                            });
                        })
                        .fail(function (response) {
                            swal(bangla_oops, bangla_error, 'error');
                        });
                });
            },
            allowOutsideClick: false,
        });
    });

    // approve order
    $(document).on("click", "#forward_to", function () {
        var id = $(this).data("id");
        swal({
            title: delete_title,
            text: forward_requisition_text,
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#5DADE2",
            cancelButtonColor: "#CD6155",
            cancelButtonText: bangla_cancel_btn,
            confirmButtonText: bangla_confirm_btn,
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve) {
                    $.ajax({
                        url: "/order/forward",
                        type: "post",
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                        },
                        dataType: "json",
                    })
                        .done(function (response) {
                            swal(
                                excellent_text, successful_text, 'success'
                            ).then(function () {
                                window.location.href = "/approved/requisition";
                            });
                        })
                        .fail(function (response) {
                            swal(bangla_oops, bangla_error, 'error');
                        });
                });
            },
            allowOutsideClick: false,
        });
    });


    // delete survey function()
    $(document).on("click", "#update_order_status", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var status = $("#order_status").children("option:selected").val();
        swal({
            title: delete_title,
            text: update_text,
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
                        url: "/order/status/update",
                        type: "post",
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                            status: status,
                        },
                        dataType: "json",
                    }).done(function (response) {
                        swal(  excellent_text, successful_text, 'success')
                            .then(function () {
                                // window.location.href='/delivered/requisition';
                            })
                            .fail(function (response) {
                                swal(bangla_oops, bangla_error, 'error');
                            });
                    });
                });
            },
            allowOutsideClick: false,
        });
    });

    $(document).on("submit", "form#deliveredQtyForm", function (event) {
        event.preventDefault();
        $("body").addClass("loading");
        var form = $("#deliveredQtyForm");
        var formData = form.serialize();
        var url = "/item-qty/update";
        var type = "post";
        $.ajax({
            type: type,
            url: url,
            data: new FormData($("#deliveredQtyForm")[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                toastr.success(
                    "Delivered Quantity Updated Successfully!",
                    "Well Done!"
                );
                // $('#deliveredQtyForm')[0].reset();
                swal("Excellent!", response[0], "success").then(function () {
                    $("[data-dismiss=modal]").trigger({ type: "click" });
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning("Error! Check Your Form Information Please.");
            },
        });
    });
    $(document).on("click", "#indSave", function (e) {
        e.preventDefault();
        var role = $(this).data("role");
        if (role == "am-ssm") {
            var itemId = $(this)
                .parent("td")
                .parent("tr")
                .find("td.deliver_qty input")
                .data("id");
            var itemValue = $(this)
                .parent("td")
                .parent("tr")
                .find("td.deliver_qty input")
                .val();
        } else if (role == "operator") {
            var itemId = $(this)
                .parent("td")
                .parent("tr")
                .find("td.rcv_qty input")
                .data("id");
            var itemValue = $(this)
                .parent("td")
                .parent("tr")
                .find("td.rcv_qty input")
                .val();
        } else if (role == "am-srd") {
            var itemId = $(this)
                .parent("td")
                .parent("tr")
                .find("td.req_qty input")
                .data("id");
            var itemValue = $(this)
                .parent("td")
                .parent("tr")
                .find("td.req_qty input")
                .val();
            // alert(itemId)
        }
        var url = "/single-qty/update";
        var type = "post";
        $.ajax({
            url: url,
            type: type,
            data: {
                _token: CSRF_TOKEN,
                itemId: itemId,
                itemValue: itemValue,
            },
            dataType: "json",
            success: function (response) {
                toastr.success(
                    "Delivered Quantity Updated Successfully!",
                    "Well Done!"
                );
                // $('#deliveredQtyForm')[0].reset();
                swal("Excellent!", response[0], "success").then(function () {
                    $("[data-dismiss=modal]").trigger({ type: "click" });
                });
            },
            error: function (errors) {
                console.log(errors.responseJSON.errors);
                toastr.warning("Error! Check Your Form Information Please.");
            },
        });
    });

    // Trash - restore
    $(document).on("click", ".restore", function () {
        var tr = $(this).parents("tr");
        var tr_id = tr.attr("id");
        // alert(tr_id+ ' '+ 'tr#'+tr_id);
        var id = $(this).data("id");
        var type = $(this).data("type");
        swal({
            title: "Are you sure?",
            text: "You want to restore this!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, restore it!",
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve) {
                    $.ajax({
                        url: "/restore",
                        type: "post",
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                            type: type,
                        },
                        dataType: "json",
                    })
                        .done(function (response) {
                            swal(
                                "Congratulation!",
                                "Your Requested " +
                                    type +
                                    " restored successfully",
                                "success"
                            ).then(function () {
                                window.location.replace(response.url);
                            });
                        })

                        .fail(function (response) {
                            swal("Oops...", "Something went wrong!", "error");
                        });
                });
            },
            allowOutsideClick: false,
        });
    });

    // Trash - Delete
    $(document).on("click", ".permanent_delete", function () {
        var tr = $(this).parents("tr");
        var tr_id = tr.attr("id");
        // alert(tr_id+ ' '+ 'tr#'+tr_id);
        var id = $(this).data("id");
        var type = $(this).data("type");
        swal({
            title: "Are you sure?",
            text: "You want to delete this!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve) {
                    $.ajax({
                        url: "/permanent-delete",
                        type: "post",
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                            type: type,
                        },
                        dataType: "json",
                    })
                        .done(function (response) {
                            if (response.deleted) {
                                swal(
                                    "Success!",
                                    "Your Requested " +
                                        type +
                                        " deleted successfully",
                                    "success"
                                ).then(function () {
                                    table
                                        .row("tr#" + tr_id)
                                        .remove()
                                        .draw();
                                });
                            } else {
                                swal(
                                    "Sorry...",
                                    "Something went wrong!",
                                    "warning"
                                );
                            }
                            // console.log(response.delete);
                            // location.reload(true);
                        })
                        .fail(function (response) {
                            swal("Oops...", "Something went wrong!", "error");
                        });
                });
            },
            allowOutsideClick: false,
        });
    });

    //dataformv2
    $('select[name="category_Name"]').on('change', function () {
        var countryID = jQuery(this).val();
        if (countryID) {
            $.ajax({
                url: '/items/getSubCategory/' + countryID,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    // console.log(data);
                    var select_language=  (language === "English" )? "বাছাই করুন":"Please Select";
                    jQuery('select[name="sub_cat_name"]').empty();
                    $('select[name="sub_cat_name"]').append('<option value=" ">'+select_language+'</option>');
                    $.each(data, function (key, value) {
                        $('select[name="sub_cat_name"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        }
        else {
            $('select[name="sub_cat_name"]').empty();
        }
    });

    $('select[name="category_Name"]').on('change', function () {
        var countryID = jQuery(this).val();
        if (countryID) {
            $.ajax({
                url: '/purchase/getProduct/' + countryID,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    // console.log(data);
                    jQuery('select[name="product_Name"]').empty();
                    $('select[name="product_Name"]').append('<option value=" ">Please select</option>');
                    $.each(data, function (key, value) {
                        $('select[name="product_Name"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        }
        else {
            $('select[name="sub_cat_name"]').empty();
        }
    });
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
                    response[1]['created_by'],
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
                // console.log(response[1]);
                toastr.success(excellent_text, successful_text)
                var idx = table.rows().count();
                idx++;
                var rowNode = table
                    .row.add(['<b class="serial">' + idx + '</b>', response[1]['product'], response[1]['category'], response[1]['qty'], response[1]['vendor_name'], response[1]['date'],response[1]['created_by'],
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

    //dataformv3

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

                    response[1]["name"],
                    response[1]["impa_code"],
                    response[1].unit,
                    response[2]["name"],
                    response[3]["name"],
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
