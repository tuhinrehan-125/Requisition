<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Internal Resource Division</title>
    <meta name="description" content="Ship Repair Department | Bangladesh Shipping Corporation">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" href="{{asset('/images/logo.png')}}">
    <link rel="shortcut icon" type="image/jpg" href="{{url('/images/favicon.ico')}}" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/sweetalert2/dist/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">

    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>

    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="{{url('/css/zebra_datepicker.min.css')}}">
    <link rel="stylesheet" href="{{url('/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{url('/css/style.css')}}">
    <link rel="stylesheet" href="{{url('/css/vesselFormstyle.css')}}">
</head>

<body>
    <aside id="left-panel" class="left-panel" style="background: #f8f9e7;">
        <nav class="navbar navbar-expand-sm navbar-default w100" style="background: #f8f9e7;">
            <div id="main-menu" class="main-menu w100 collapse navbar-collapse">
                <ul class="nav navbar-nav w100">
                    @if(auth()->user()->role()->exists())
                    <li class="{{Route::current()->uri() == 'home' ? 'active' : ''}}">
                        <a href="{{url('/home')}}"><i class="menu-icon fa fa-laptop"></i><span class="left-menu-title">{{__('messages.dashboard') }}</span> </a>
                    </li>
                    @if(auth()->user()->role->role=='super-admin' || auth()->user()->role->role=='stock-officer' )
                    <li class="{{Route::current()->uri() == 'vendor' ? 'active' : ''}}">
                        <a href="{{url('/vendor')}}">
                            <i class="menu-icon fa fa-th"></i>
                            <span class="left-menu-title">{{__('messages.vendor') }}</span></a>
                    </li>
                    <li class="{{Route::current()->uri() == 'department' ? 'active' : ''}}">
                        <a href="{{url('/department')}}">
                            <i class="menu-icon fa fa-th"></i>
                            <span class="left-menu-title">{{__('messages.department') }}</span></a>
                    </li>
                    <li class="{{Route::current()->uri() == 'category' ? 'active' : ''}}">
                        <a href="{{url('/category')}}">
                            <i class="menu-icon fa fa-th"></i>
                            <span class="left-menu-title">{{__('messages.category') }}</span></a>
                    </li>
                    <li class="{{Route::current()->uri() == 'product' ? 'active' : ''}}">
                        <a href="{{url('/product')}}">
                            <i class="menu-icon fa fa-th"></i>
                            <span class="left-menu-title">{{__('messages.product') }}</span></a>
                    </li>
                    <li class="{{Route::current()->uri() == 'purchase' ? 'active' : ''}}">
                        <a href="{{url('/purchase')}}"><i class="menu-icon fa fa-th"></i><span class="left-menu-title">{{__('messages.purchase') }}</span> </a>
                    </li>
                    <li class="{{Route::current()->uri() == 'stock' ? 'active' : ''}}">
                        <a href="{{url('/stock')}}"><i class="menu-icon fa fa-th"></i><span class="left-menu-title">{{__('messages.stock') }}</span> </a>
                    </li>
                    @endif
                    @if(auth()->user()->role->role=='dept-user' )
                    <li class="{{Route::current()->uri() == 'my-stock' ? 'active' : ''}}">
                        <a href="{{url('/my-stock')}}"><i class="menu-icon fa fa-th"></i><span class="left-menu-title">{{__('messages.my_stock') }}</span> </a>
                    </li>

                    <li class="{{Route::current()->uri() == 'create/requisition' ? 'active' : ''}}">
                        <a href="{{url('/create/requisition')}}">
                            <i class="menu-icon fa fa-th"></i>
                            <span class="left-menu-title">{{__('messages.add_requisition')}}</span></a>
                    </li>

                    <li class="{{Route::current()->uri() == 'received/product' ? 'active' : ''}}">
                        <a href="{{url('/received/product')}}">
                            <i class="menu-icon fa fa-th"></i>
                            <span class="left-menu-title">{{__('messages.received_product')}}</span></a>
                    </li>
                    @endif
                    <li class="{{Route::current()->uri() == 'approved/requisition' ? 'active' : ''}}">
                        <a href="{{url('/approved/requisition')}}">
                            <i class="menu-icon fa fa-th"></i>
                            <span class="left-menu-title">{{__('messages.approved_requisition') }}
                            </span></a>
                    </li>
                    <li class="{{Route::current()->uri() == 'pending/requisition' ? 'active' : ''}}">
                        <a href="{{url('/pending/requisition')}}">
                            <i class="menu-icon fa fa-th"></i>
                            <span class="left-menu-title">{{__('messages.pending_requisition') }}</span></a>
                    </li>
                    <li class="{{Route::current()->uri() == 'reject/requisition' ? 'active' : ''}}">
                        <a href="{{url('/reject/requisition')}}">
                            <i class="menu-icon fa fa-th"></i>
                            <span class="left-menu-title">{{__('messages.rejected_requisition') }}
                            </span></a>
                    </li>
                    <li class="panel panel-default" id="dropdown">
                        <a data-toggle="collapse" href="#dropdown-lvl1">
                            <i class="menu-icon fa fa-th"></i>{{__('messages.report') }}
                            <i class="right-icon fa fa-angle-down"></i>
                        </a>
                        <!-- Dropdown level 1 -->
                        <div id="dropdown-lvl1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ul class="nav navbar-nav">
                                    <li class="{{ Request::segment(1) == 'report' && Request::segment(2) == 'requisition' ? 'active' : '' }}">
                                        <a href="{{route('report-requisition.index')}}">
                                            <i class="menu-icon fa fa-th"></i> <span class="left-menu-title">{{__('messages.requisition') }}</span></a>
                                    </li>
                                    <li class="{{ Request::segment(1) == 'report' && Request::segment(2) == 'stock' ? 'active' : '' }}">
                                        <a href="{{route('report-stock.index')}}">
                                            <i class="menu-icon fa fa-th"></i> <span class="left-menu-title">{{__('messages.total_stock')}}</span></a>
                                    </li>

                                    @if(auth()->user()->role->role=='dept-user')
                                    <li class="{{ Request::segment(1) == 'report' && Request::segment(2) == 'total-received-product' ? 'active' : '' }}">
                                        <a href="{{route('report-total-received-product.index')}}">
                                            <i class="menu-icon fa fa-th"></i> <span class="left-menu-title">{{__('messages.received_product')}}</span></a>
                                    </li>


                                    @endif
                                    @if(auth()->user()->role->role=='stock-officer' || auth()->user()->role->role=='admin-officer' || auth()->user()->role->role=='super-admin')
                                    <li class="{{ Request::segment(1) == 'report' && Request::segment(2) == 'total-purchase' ? 'active' : '' }}">
                                        <a href="{{route('report-purchase.index')}}">
                                            <i class="menu-icon fa fa-th"></i> <span class="left-menu-title">{{__('messages.total_purchase')}}</span></a>
                                    </li>
                                    <li class="{{ Request::segment(1) == 'report' && Request::segment(2) == 'total-delivered' ? 'active' : '' }}">
                                        <a href="{{route('report-total-delivered.index')}}">
                                            <i class="menu-icon fa fa-th"></i> <span class="left-menu-title">{{__('messages.total_delivered')}}</span></a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </li>
                    {{-- --}}{{-- @if(auth()->user()->role->role=='admin-officer' || auth()->user()->role->role=='sr-officer')--}}
                    {{-- <li class="{{Route::current()->uri() == 'pending/requisition' ? 'active' : ''}}">--}}
                    {{-- <a href="{{url('/pending/requisition')}}">--}}
                    {{-- <i class="menu-icon fa fa-th"></i>--}}
                    {{-- <span class="left-menu-title">Pending Requisition</span></a>--}}
                    {{-- </li>--}}
                    {{-- <li class="{{Route::current()->uri() == 'approved/requisition' ? 'active' : ''}}">--}}
                    {{-- <a href="{{url('/approved/requisition')}}">--}}
                    {{-- <i class="menu-icon fa fa-th"></i>--}}
                    {{-- <span class="left-menu-title">Approved Requisition--}}
                    {{-- </span></a>--}}
                    {{-- </li>--}}
                    {{-- <li class="{{Route::current()->uri() == 'reject/requisition' ? 'active' : ''}}">--}}
                    {{-- <a href="{{url('/reject/requisition')}}">--}}
                    {{-- <i class="menu-icon fa fa-th"></i>--}}
                    {{-- <span class="left-menu-title">Rejected Requisition--}}
                    {{-- </span></a>--}}
                    {{-- </li>--}}
                    {{-- @endif --}}
                    @endif
                    <li class="user-menu {{Route::current()->uri() == 'reject/requisition' ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();"><i class="fas fa-power-off"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>
    <div id="right-panel" class="right-panel">
        <header id="header" class="header" style="background: #683091">
            <div class="top-left">
                <div class="navbar-header" style="background: #683091;">
                    <a class="navbar-brand" href="{{url('/home')}}" style="color: #fff;">
                        {{ auth()->user()->dept?auth()->user()->dept->name:''}}-{{ auth()->user()->username?auth()->user()->username:'' }}
                    </a>
                    <a id="menuToggle" class="menutoggle"><i style="color: #fff;" class="fa fa-bars"></i></a>

                </div>
            </div>

            <div class="top-right">
                <div class="header-menu new">

                    <div class="user-area create-user-area  float-left">
                        @if(!empty(auth()->user()->role->role) && auth()->user()->role->role=='super-admin')
                        <a href="{{url('/home/user')}}"><i style="color: #fff;" class="fa fa-user"></i> {{__('messages.user')}}</a> &nbsp;
                        &nbsp;
                        {{-- <a href="{{url('/home/trash')}}">
                        <i class="menu-icon fas fa-trash-alt"></i>
                        <span class="left-menu-title"> Trash</span></a> --}}
                        @endif


                    </div>
                    <div class="d-flex justify content-center header_middle_area">
                        <img src="{{asset('images/logo_2.jpg')}}" class="mt-1" style="width: 54px; height:44px" alt="">
                        <p class="mt-3 ml-2 font-weight-bold" style="color: #fff">{{ __('messages.ird_header') }}</p>
                    </div>

                    <div class="user-area dropdown float-right">
                        <div class="lang_select">
                            @if(app()->getLocale()=='en')
                            <a class="btn btn-info btn-lang" href="{{url('/lang/bn')}} ">বাংলা</a>
                            @else
                            <a class="btn btn-info btn-lang" href="{{url('/lang/en')}} ">English</a>
                            @endif
                        </div>
                        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class='admin_name' style="margin-right:20px;color:#fff;text-transform: capitalize;font-style: italic;">
                                {{auth()->user()->name}}</span> <img class="user-avatar rounded-circle" src="{{!empty(auth()->user()->photo)?url('/'.auth()->user()->photo):'https://recap-project.eu/wp-content/uploads/2017/02/default-user-300x300.jpg'}}" alt="User Avatar" style="    height: 40px;">
                        </a>
                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href="{{url('/profile')}}"><i class="fas fa-user"></i> My Profile</a>
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();"><i class="fas fa-power-off"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="content" style="background: #e9ead9">
            <div class="animated fadeIn">
                <div class="row">
                    @yield('main-content')
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <footer class="site-footer " style="background: #c9cab8">
            <div class="footer-inner">
                <div class="row">
                    <div class="col-sm-6">
                        Copyright &copy; 2021.
                    </div>
                    <div class="col-sm-6 text-right">
                        Powered by <a href="https://www.orionis.com.bd/">Orionis Soft Tech LTD</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('js/print-pdf-custom.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('/assets/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/zebra_datepicker.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/dataForm.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/datalist.js') }}"></script>
    @yield('home-js')
    @yield('print-pdf-custom-js')
    <script src="{{url('/')}}/assets/js/main.js"></script>
    @yield('pie-flot')
    @yield('create-order-js')
    <script type="text/javascript">
        $(document).ready(function() {

            $('input.date').Zebra_DatePicker({
                format: 'Y-m-d'
            });
            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })
        });
    </script>
    @yield('file-validate')
</body>

</html>