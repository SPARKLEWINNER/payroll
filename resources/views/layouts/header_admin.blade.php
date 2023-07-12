<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @laravelPWA
    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <link rel="shortcut icon" href="{{ asset('images/icons/icon-144x144.png') }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">

    @yield('css')
 
    <style>
      .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url("{{ asset('images/loader.gif') }}") 50% 50% no-repeat white;
        opacity: .8;
        background-size: 320px 320px;
      }
      .dataTables_filter {
            width: 50%;
            float: right;
            text-align: right;
            }
      .dataTables_info {
        width: 50%;
        float: left;
        text-align: left;
        }
        @media (min-width: 768px) {
            .modal-xl {
                width: 100%;
                max-width:1700px;
            }
        }
    </style>
</head>
<body>
    <div id="loader" style="display:none;" class="loader">
	</div>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> <span>
                            <img alt="image" class="rounded-circle bg-light" style='width:54px  ;height:54px;' src="{{'images/no_image.png'}}"/>
                                 </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{auth()->user()->name}}</strong>
                                 </span> <span class="text-muted text-xs block">{{auth()->user()->position}} <b class="caret"></b></span> </span> </a>
                                 <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                    {{-- <li><a class="dropdown-item" href="profile.html">Profile</a></li> --}}
                                    {{-- <li class="dropdown-divider"></li> --}}
                                    <li><a class="dropdown-item" data-target="#change_pass" data-toggle="modal"  >Change Password</a></li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"  onclick="logout(); show();">Logout</a></li>
                                </ul>
                        </div>
                        <div class="logo-element">
                            PAY
                        </div>
                    </li>
                  
                    <li>
                        <a href="{{url('/')}}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">Settings</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="{{url('/holidays')}}">Holiday</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-list-ul"></i> <span class="nav-label">Masterfiles</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="{{url('/groups')}}">Groups</a></li>
                            <li><a href="{{url('/stores')}}">Stores</a></li>
                            <li><a href="{{url('/users')}}">Personnel</a></li>
                            {{-- <li><a href="{{url('/salaries')}}">Salaries</a></li> --}}
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-files-o"></i> <span class="nav-label">Payroll</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="{{url('/generate')}}">Generate Payroll</a></li>
                            <li><a href="{{url('/payrolls')}}">Payrolls</a></li>
                            <li><a href="{{url('/payslips')}}">Payslips</a></li>
                        </ul>
                    </li>
                </ul>
    
            </div>
        </nav>
    
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">Welcome to Sparkle Payroll.</span>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                        </a>
                        <ul class="dropdown-menu dropdown-alerts">
                            <li>
                                <a href="mailbox.html">
                                    <div>
                                        <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                        <span class="pull-right text-muted small">4 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="profile.html">
                                    <div>
                                        <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                        <span class="pull-right text-muted small">12 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="grid_options.html">
                                    <div>
                                        <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                        <span class="pull-right text-muted small">4 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="text-center link-block">
                                    <a href="notifications.html">
                                        <strong>See All Alerts</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
    
    
                    <li>
                        <a href="{{ route('logout') }}" onclick="logout(); show();">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>
                </ul>
    
            </nav>
            </div>
         
          
            <div class="wrapper wrapper-content ">
                @yield('content')
            </div>
        </div>
    </div>
    @include('sweetalert::alert')   
    <form id="logout-form"  action="{{ route('logout') }}"  method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    @include('changepass')
    <script>
        function logout() {
			event.preventDefault();
			document.getElementById('logout-form').submit();
		}    
    function show() {
			document.getElementById("loader").style.display = "block";
		}
    function unshow() {
			document.getElementById("loader").style.display = "None";
		}
        function get_min(value)
      {
        document.getElementById("to").min = value;
      }
    </script>

       <!-- Mainly scripts -->
       <script src="{{ asset('admin/js/jquery-3.1.1.min.js') }}"></script>
       <script src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
       <script src="{{ asset('admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
       <script src="{{ asset('admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
   
       <script src="{{ asset('admin/js/plugins/dataTables/datatables.min.js') }}"></script>
   
       <!-- Custom and plugin javascript -->
       @yield('js')
      
   
       <!-- Page-Level Scripts -->
       <script>
           $(document).ready(function(){
               $('.dataTables-example').DataTable({
                   pageLength: -1,
                   responsive: true,
                   ordering: false,
                   dom: '<"html5buttons"B>lTfgitp',
                   buttons: [
                       {extend: 'csv'},
                       {extend: 'excel', title: 'ExampleFile'},
                       {extend: 'pdf', title: 'ExampleFile'},
   
                       {extend: 'print',
                        customize: function (win){
                               $(win.document.body).addClass('white-bg');
                               $(win.document.body).css('font-size', '10px');
   
                               $(win.document.body).find('table')
                                       .addClass('compact')
                                       .css('font-size', 'inherit');
                       }
                       }
                   ]
   
               });
               $('.home-payroll').DataTable({
                   pageLength: 10,
                   responsive: true,
                   ordering: false,
                   dom: '<"html5buttons"B>lTfgitp',
                   
                   buttons: [
                       {extend: 'csv'},
                       {extend: 'excel', title: 'ExampleFile'},
                       {extend: 'pdf', title: 'ExampleFile'},
   
                       {extend: 'print',
                        customize: function (win){
                               $(win.document.body).addClass('white-bg');
                               $(win.document.body).css('font-size', '10px');
   
                               $(win.document.body).find('table')
                                       .addClass('compact')
                                       .css('font-size', 'inherit');
                       }
                       }
                   ]
   
               });
   
           });
   
       </script>

</body>
</html>
