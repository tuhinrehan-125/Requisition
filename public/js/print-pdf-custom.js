var date = new Date();
var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();
var am_pm = date.getHours() >= 12 ? "pm" : "am";
hours = hours < 10 ? "0" + hours : hours;
var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
ctime = hours + ":" + minutes + ":" + seconds + "" + am_pm;

var d = new Date();
var weekday = new Array(7);
weekday[0] = "Sunday";
weekday[1] = "Monday";
weekday[2] = "Tuesday";
weekday[3] = "Wednesday";
weekday[4] = "Thursday";
weekday[5] = "Friday";
weekday[6] = "Saturday";
var dayName = weekday[d.getDay()];

var cMonth = new Date().getMonth()+1;
var cMonth = cMonth < 10 ? '0'+cMonth : cMonth;


var cuDate = new Date().getDate() + '/'+ cMonth + '/' + new Date().getFullYear() +' '+ ctime;
var cuDateRev = new Date().getFullYear()+'/'+cMonth+'/'+new Date().getDate();

var pptitle = $(".pptitle").text();
var logo = $(".site-logo").attr("src");
var style = "{{url('/css/print-style.css')}}";

// Print_Users
function print_this() {  
    var mywindow = window.open('', 'PRINT');
    var is_chrome = Boolean(mywindow.chrome);

    mywindow.document.write('<html><head style="padding:0; margin:0 auto"><title>'+pptitle+'</title>');
    
    mywindow.document.write('<style>table{width: 100%;font-size:14px;border-collapse:collapse;margin-bottom:15px; zoom: 100% }table th{ text-transform: uppercase!important;border:1px solid #000;}table th,table td{padding:5px 10px;text-align:center;border:1px solid #000;}.footersign {text-align: center;font-size: 13px; position:relative; top:50px;width:98%;display: flex;font-weight:700;margin-top:25px; justify-content: space-between; align-item:center;} .footersign span{border-top:1px solid black;padding-top:5px;} table#example tbody tr td:last-child{display: none;} img { max-width: 100%; height: auto; }.action,.tdfile{display:none;} /*      print-header      */ .print-header{ position:relative;width:100%; margin:0 auto 5px;background:#fff;top:0;z-index:999; text-align:center; display:block;zoom:90%;} .print-header .title-wrap .line1,.print-header .title-wrap .line2,.print-header .title-wrap .line3,.print-header .title-wrap .line4{text-transform:uppercase;font-size:17px;font-weight:700; line-height:1.3;color:#000;letter-spacing:.5px;margin: 0 auto;}.print-header .title-wrap .line1,.print-header .title-wrap .line3{border-bottom:1px solid #000;line-height:1;display:inline-block}.print-header .title-wrap .line3{font-size:14px;margin:5px auto}.print-header .title-wrap .line3 span{display:inline-block;width:10px} .print-header .title-wrap .line4{font-size:15px;margin-bottom:15px} .print-header .vessel-info{display:none;text-align:left;padding-left:15px;font-size:14px}.print-header .vessel-info strong{text-transform:uppercase;font-weight:700}.print-header .vessel-info span{margin-right:15px}                     /* print header */  #order-print-header1 .od-title, #order-print-header1 .od-title .title-center{display:flex; justify-content:space-around;align-content:center;flex-flow:row wrap}#order-print-header1 .od-title .title-center{flex-flow:column;align-content:center;justify-content:center} #order-print-header1 .od-title .logo,#order-print-header1 .od-title .title-right{width:72px} img { max-width: 100%; height: auto; }   </style>');
    mywindow.document.write('</head><body>');

    mywindow.document.write(document.getElementById('order-print-header1').outerHTML); 
    mywindow.document.write(document.getElementById('example').outerHTML); 
         
    mywindow.document.write('</body></html>');

    if (is_chrome) {
        setTimeout(function () { // wait until all resources loaded 
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            mywindow.print();
            mywindow.close();
        }, 250);
    }
    else {
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/
        mywindow.print();
        mywindow.close();
    }

    return true;
}

// order_list / requisition list
function order_list() {  
    var mywindow = window.open('', 'PRINT');
    var is_chrome = Boolean(mywindow.chrome);

    mywindow.document.write('<html><head style="padding:0; margin:0 auto"><title>'+pptitle+'</title>');
    
    mywindow.document.write('<style> body{zoom: 100%; }    /* print header */  .print-header { position:relative;width:100%;margin:0 auto 10px; background:#fff;top:0;z-index:999; text-align:center;display:block;zoom:90%;} .print-header .title-wrap .line1,.print-header .title-wrap .line2,.print-header .title-wrap .line3,.print-header .title-wrap .line4{text-transform:uppercase;font-size:17px;font-weight:700;line-height:1.3;color:#000;letter-spacing:.5px;margin: 0 auto;}.print-header .title-wrap .line1,.print-header .title-wrap .line3{border-bottom:1px solid #000;line-height:1;display:inline-block} .print-header .title-wrap .line3{font-size:14px;margin:5px auto} .print-header .title-wrap .line3 span{display:inline-block;width:10px}.print-header .title-wrap .line4{font-size:15px;margin:10px auto 0px}      /* print header */  #order-print-header1 .od-title, #order-print-header1 .od-title .title-center{display:flex;justify-content:space-around;align-content:center;flex-flow:row wrap}#order-print-header1 .od-title .title-center{flex-flow:column;align-content:center;justify-content:center} #order-print-header1 .od-title .logo,#order-print-header1 .od-title .title-right{width:72px} img { max-width: 100%; height: auto; }  /* table css */  table{ width: 100%;font-size:14px;border-collapse:collapse;margin-bottom:15px;border :1px solid #000;} table th{ text-transform: uppercase!important;} table th,table td{padding:5px 10px;text-align:center;border:1px solid #000;} .action,.tdfile {display:none;} </style>');
    mywindow.document.write('</head><body>');

    mywindow.document.write(document.getElementById('order-print-header1').outerHTML); 
    mywindow.document.write(document.getElementById('example').outerHTML); 
         
    mywindow.document.write('</body></html>');

    if (is_chrome) {
        setTimeout(function () { // wait until all resources loaded 
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            mywindow.print();
            mywindow.close();
        }, 250);
    }
    else {
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/
        mywindow.print();
        mywindow.close();
    }

    return true;
}


// print_vehical_info
function print_vehical_info() {  
    var mywindow = window.open('', 'PRINT');
    var is_chrome = Boolean(mywindow.chrome);

    mywindow.document.write('<html><head style="padding:0; margin:0 auto"><title>'+pptitle+'</title>');
    
    mywindow.document.write('<style>table{width: 100%;font-size:14px;border-collapse:collapse;margin-bottom:15px;}table th{ text-transform: uppercase!important;}table th,table td{padding:2px 10px 2 0;border:0px solid #000;}.footersign {text-align: center;font-size: 13px; position:relative; top:50px;width:98%;display: flex;font-weight:700;margin-top:25px; justify-content: space-between; align-item:center;} .footersign span{border-top:1px solid black;padding-top:5px;} img { max-width: 100%; height: auto; }.action,.tdfile{display:none;}  .print-header{position:relative;width:100%;margin:0 auto;background:#fff;top:0;z-index:999;text-align:center;display:block;zoom:90%;}.print-header .title-wrap .line1,.print-header .title-wrap .line2,.print-header .title-wrap .line3,.print-header .title-wrap .line4{text-transform:uppercase;font-size:17px;font-weight:700;line-height:1.3;color:#000;letter-spacing:.5px;margin: 0 auto;}.print-header .title-wrap .line1,.print-header .title-wrap .line3{border-bottom:1px solid #000;line-height:1;display:inline-block}.print-header .title-wrap .line3{font-size:14px;margin:5px auto}.print-header .title-wrap .line3 span{display:inline-block;width:10px}.print-header .title-wrap .line4{font-size:15px;margin-bottom:15px}.p_lebel{width: 40%;}.p_dot{}.no-break h4{margin-bottom: 10px; } table { page-break-inside:auto } tr { page-break-inside:avoid; page-break-after:auto } thead { display:table-header-group } tfoot { display:table-footer-group }</style>');
    mywindow.document.write('</head><body>');

    mywindow.document.write(document.getElementById('order-print-header').outerHTML); 
    mywindow.document.write(document.getElementById('privew-wrapper').outerHTML); 
      
    mywindow.document.write('</body></html>');

    if (is_chrome) {
        setTimeout(function () { // wait until all resources loaded 
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            mywindow.print();
            mywindow.close();
        }, 250);
    }
    else {
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/
        mywindow.print();
        mywindow.close();
    }

    return true;
}


$(document).on('click','button.print-order', function(e){
    e.preventDefault();
    var tableid=$('div.card').find('table').attr('id');
    var req_date=$('input#Requisition_Date').val();
    var req_no=$('input[name="Requisition_No"]').val();
    var port_name=$('input[name="Port_Name"]').val();
    var vessel_name=$('select[name="Vessel_Name"]').children("option:selected").text();
   
    $('span.req_date').text(req_date);
    $('span.vessel_name').text(vessel_name);
    $('span.req_no').text(req_no);
    $('span.port').text(port_name);
    print_order(tableid);
})

$(document).on('click','.print-order-details', function(e){
    e.preventDefault();
    var tableid=$('div.card').find('table').attr('id');
    print_order(tableid);
})

// print_order
function print_order( tableid ) {  
    var mywindow = window.open('', 'PRINT');
    var is_chrome = Boolean(mywindow.chrome);

    mywindow.document.write('<html><head style="padding:0; margin:0 auto"><title>'+pptitle+'</title>');
    
    mywindow.document.write('<style>table{width: 100%;font-size:12px;border-collapse:collapse;margin-bottom:15px; }table th{ text-transform: uppercase!important;}table th,table td{font-size: 12px;padding:5px 10px;text-align:center;border:1px solid #000;} table#example1 tbody tr td:last-child{display: none;} img { max-width: 100%; height: auto; }.action,.tdfile{display:none;}  .print-header{position:relative;width:100%;margin:0 auto;background:#fff;top:0;z-index:999;text-align:center;display:block;}.print-header .title-wrap .line1,.print-header .title-wrap .line2,.print-header .title-wrap .line3,.print-header .title-wrap .line4x{text-transform:uppercase;font-size:13px!important;font-weight:700;line-height:1.3;color:#000;letter-spacing:.5px;margin: 0 auto;}.print-header .title-wrap .line1,.print-header .title-wrap .line3{border-bottom:1px solid #000;line-height:1;display:inline-block}.print-header .title-wrap .line3{font-size:12px;margin:5px auto}.print-header .title-wrap .line3 span{display:inline-block;width:10px}.print-header .title-wrap .line4x{font-size:15px;margin-bottom:15px}.print-header .vessel-info{text-align:left;padding-left:15px;font-size:14px} #order-print-header2 {text-transform:uppercase;font-size:12px;} #order-print-header2 strong{text-transform:uppercase;font-weight:700}.print-header .vessel-info span{margin-right:15px}table.office-use-table{width:calc(100% - 0px);margin:0 0 15px;box-sizing:border-box}table.office-use-table td{border:1px solid #000;font-size:14px;padding: 5px 0;}  table.office-use-table td.office_use {width:70px;text-transform:uppercase;padding:5px;color:#000;text-align:center;box-sizing:border-box;}table.office-use-table td.office_use_form{ calc( 100% - 70px );}table.office-use-table td p{font-size:14px;text-align:left;margin: 3px 5px;color:#000}table.office-use-table td p span{display:inline-block;border-bottom:1px dashed #000;width:125px}table.office-use-table td p span.checked_by{width:140px} table.office-use-table td p span.passed{width:124px} table.office-use-table td p span.invitation{width:227px} table.office-use-table td p span.approved_date{width:262px} table.office-use-table td p span.delevered_obdate{width:201px} table.office-use-table td p span.bil_rdate { width:85px}table.office-use-table td p span.pua_date {width:85px} table.office-use-table td p span.pfp_date{width:85px;} #order-print-header2 {display:flex;justify-content:space-between;}    .OrderDetailsTable tr td:nth-child(1){width:10px} .OrderDetailsTable{}.OrderDetailsTable tr td:nth-child(2){width:90px}    /* print header */  #order-print-header1 .od-title, #order-print-header1 .od-title .title-center{display:flex;justify-content:space-around;align-content:center;flex-flow:row wrap}#order-print-header1 .od-title .title-center{flex-flow:column;align-content:center;justify-content:center}#order-print-header1 .od-title .logo,#order-print-header1 .od-title .title-right{width:72px}     /* table css */ .item-name-print {display:inherit;}.item-name,.item-cat{display:none; }.item-name-td,.item-unit{ text-transform:uppercase;}.item-name-td {text-align:left;}    /*footer css */     #order-print-footer1{display:block;text-align:left}.footer-notes{padding:15px 0;font-size:12px}.footer-notes ul{list-style:none;padding-left:0;margin-bottom:0}.footer-notes ul li{padding-left:15px;font-size:12px}.signs-master-chief {display:flex;justify-content:center; flex-flow: row wrap; align-items:flex-end;padding:15px;border-bottom:0px solid #ddd;margin:-45px 15px 0px}.chief-officer,.master-chief{height:auto;display:flex; justify-content:center;flex-flow:column wrap;align-items:center;padding:15px 15px 0;font-size:14px}.seal,.sign{padding:0 15px}#order-print-footer2 {display:block}.signs-master-chief-admin,.signs-master-chief {display:flex;flex-flow: row wrap; justify-content:center;align-items: flex-end;} .signs-master-chief-admin >div,.signs-master-chief>div { width:30%;box-sizing:border-box; align-content: center;} .signer-name {text-transform:uppercase;display:inline-block; font-size:16px;margin:5px auto}.seal{height:105px; overflow:hidden;} .sign {max-height:95px;width: 100%; overflow:hidden;margin-top:15px; text-align: center;} .sign img{max-height:95px; max-width: 100%;height: auto;} </style>');
    mywindow.document.write('</head><body style="zoom:90%">');

    mywindow.document.write(document.getElementById('order-print-header1').outerHTML); 
    mywindow.document.write(document.getElementById('order-print-header2').outerHTML);  
    mywindow.document.write(document.getElementById('order-print-header3').outerHTML);
    mywindow.document.write(document.getElementById(tableid).outerHTML); 
    mywindow.document.write(document.getElementById('order-print-footer1').outerHTML);
    // mywindow.document.write(document.getElementById('order-print-footer2').outerHTML);
        
    mywindow.document.write('</body></html>');

    if (is_chrome) {
        setTimeout(function () { // wait until all resources loaded 
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            mywindow.print();
            mywindow.close();
        }, 500);
    }
    else {
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/
        mywindow.print();
        mywindow.close();
    }
    return true;
}

// print_cert
$(document).on('click','.btn-pcert', function(e){
    e.preventDefault();
    print_cert();
})

var logo64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2ZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo4MEI0NUE2ODU0MDhFNTExOUQ1M0M1NjRBQzEzN0FFNyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo4NDEzRjRGMzExMTAxMUU1OTU1M0M4NDY3RkNCRjM1NiIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo4NDEzRjRGMjExMTAxMUU1OTU1M0M4NDY3RkNCRjM1NiIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M2IChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjU2RDhEMTA4NUMwOEU1MTE5RDUzQzU2NEFDMTM3QUU3IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjgwQjQ1QTY4NTQwOEU1MTE5RDUzQzU2NEFDMTM3QUU3Ii8+IDwvcmRmOkRlc2NyaXB0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'
// Datatable + PDF function with customization
$(document).ready(function() {
    $('.line4').text($('.pptitle').text());

    var table = $('#example').DataTable( {
    aLengthMenu: [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
    iDisplayLength: 10,
    buttons: [
    {
        extend: 'pdfHtml5',
        text: 'Pdf',
        title: "",
        titleAttr: 'PDF',
        extension: ".pdf",
        pageSize: 'A4',
        filename: pptitle,  
        customize: function (doc) {
            // console.log(doc.content);                
            //doc.content[0].table.widths = 
            // Array(doc.content[0].table.body[0].length + 1).join('*').split('');
            doc.content[0].table.widths = [12, 60, 120, 80, 80, 60, 60];


            doc.styles = {
                tableHeader: {
                    bold: true,
                    fontSize: 8,
                    color: 'black',
                    alignment: 'center'

                },
                defaultStyle: {
                    fontSize: 8,
                    color: 'black',
                    alignment: 'center'
                }
                
            }

            doc.defaultStyle.fontSize = 8;

            var objLayout = {};
            objLayout['hLineWidth'] = function(i) { return .5; };
            objLayout['vLineWidth'] = function(i) { return .5; };
            objLayout['hLineColor'] = function(i) { return '#333'; };
            objLayout['vLineColor'] = function(i) { return '#333'; };
            objLayout['paddingLeft'] = function(i) { return 3; };
            objLayout['paddingRight'] = function(i) { return 3; };
            doc.content[0].layout = objLayout;

                // margin: [left, top, right, bottom]
                // Header Section
                doc.content.splice(0, 0, {

                    columns: [
                    {
                        image: logo64,
                        alignment: 'left',
                        width: 35,
                        margin: [5, 5, 0, -5],
                    },
                    {
                        text: 'Internal Resource Division' + '\n' + 'Ministry of Finance',
                        bold: true,
                        margin: [25, 0, 0, 0],
                    },
                    {
                        text: '',
                        alignment: 'right',
                        width: 75
                    }
                    ],
                    fontSize: 12,
                    alignment: 'center',
                });

                // Title
                doc.content.splice(1, 0, {

                    columns: [
                    {
                        text: pptitle,
                        bold: true,
                        alignment: 'center',
                        fontSize: 11
                    }
                    ],
                });

                doc.content.splice(2, 0, {

                    columns: [
                    {
                        text: 'NO:'+cuDateRev,
                        alignment: 'left'
                    },
                    {
                        text: dayName+' '+cuDate,
                        alignment: 'right'
                    }
                    ],
                    margin: [0, 0, 0, 5],
                    fontSize: 8,
                });


                // Create a footer
                doc['footer']=(function(page, pages) {
                    return {
                        text: ['Page ', { text: page.toString() },  '/', { text: pages.toString() }],
                        alignment: 'center',
                        italics: true,
                        fontSize: 10,
                    }
                });


            },
            exportOptions: {
                columns: [0,1,2,3,4,5,6]
            }
        },
        ]
    } );



    table.buttons().container().appendTo( '.right-buttons' );

    // If data table empty
    var cell_empty = $("#example tbody > tr > td").html();
    if( cell_empty == "No data available in table" ){        
        table.buttons().disable();
        $('.print-p').addClass('disabled').attr("disabled", 'disabled');
    } else {        
        table.buttons().enable();
    }

    $("#example").parent("div").css({'overflow':'auto'})

} );