<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }} ">

    @yield('css')
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
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
    </style>
</head>
<body>
  <div id="loader" style="display:none;" class="loader">
	</div>
    <div class="container-scroller">
       
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
          <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo me-5" href="{{url('/')}}"><img src="{{ asset('images/m.png') }}" class="me-2" alt="logo"/></a>
            <a class="navbar-brand brand-logo-mini" href="{{url('/')}}"><img src="{{ asset('images/icon.png') }}" alt="logo"/></a>
          </div>
          <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="icon-menu"></span>
            </button>
            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                  <img src="{{ asset('images/no_image.png') }}" alt="profile"/>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                  <a class="dropdown-item">
                    <i class="ti-settings text-primary"></i>
                    Settings
                  </a>
                  <a class="dropdown-item" href="{{ route('logout') }}" onclick="logout(); show();">
                    <i class="ti-power-off text-primary"></i>
                    Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                </div>
              </li>
            
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="icon-menu"></span>
            </button>
          </div>
        </nav> 
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
          <!-- partial:partials/_sidebar.html -->
          <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
              <li class="nav-item {{ Route::current()->getName() == "home" || "" ? "active" : "" }}"  >
                <a class="nav-link"  href="{{url('/')}}" onclick='show()'>
                  <i class="icon-grid menu-icon"></i>
                  <span class="menu-title">Dashboard</span>
                </a>
              </li>
              <li class="nav-item {{ Route::current()->getName() == "settings" || "" ? "active" : "" }}">
                <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="{{ Route::current()->getName() == "settings" || "" ? "true" : "" }}" aria-controls="ui-basic">
                    <i class="icon-cog menu-icon"></i>
                    <span class="menu-title">Settings</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ Route::current()->getName() == "settings" || "" ? "show" : "" }}" id="settings">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ url('/holidays') }}">Holidays</a></li>
                    </ul>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#masterfiles" aria-expanded="false" aria-controls="ui-basic">
                    <i class="icon-align-center menu-icon"></i>
                    <span class="menu-title">Masterfiles</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="masterfiles">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/groups') }}" onclick='show();'>Groups</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/stores') }}" onclick='show();'>Stores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/salaries') }}" onclick='show();'>Salaries</a>
                        </li>
                    </ul>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#payroll" aria-expanded="false" aria-controls="ui-basic">
                    <i class="icon-book menu-icon"></i>
                    <span class="menu-title">Payroll</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="payroll">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/generate') }}" onclick='show();'>Generate Payroll</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/payrolls') }}" onclick='show();'>Payrolls</a>
                        </li>
                    </ul>
                </div>
              </li>
              <li class="nav-item {{ Route::current()->getName() == "users" ? "active" : "" }}"  >
                <a class="nav-link"  href="{{url('/users')}}" onclick='show()'>
                  <i class="icon-head menu-icon"></i>
                  <span class="menu-title">Users</span>
                </a>
              </li>
            </ul>
          </nav>
          <!-- partial -->
          @yield('content')
          <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
      </div>
     
      @include('sweetalert::alert')    
    <script>
        function logout() {
			event.preventDefault();
			document.getElementById('logout-form').submit();
		}    
    function show() {
			document.getElementById("loader").style.display = "block";
		}
    </script>
    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('js/dataTables.select.min.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/settings.js') }}"></script>
    <script src="{{ asset('js/todolist.js') }}"></script>
    <!-- endinject -->
    @yield('js')
    <!-- Custom js for this page-->
    {{-- <script src="{{ asset('js/jquery.cookie.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ asset('js/dashboard.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/Chart.roundedBarCharts.js') }}"></script> --}}
    <!-- End custom js for this page-->
    <script>
    function get_min(value)
      {
        document.getElementById("to").min = value;
      }
  </script>
</body>
</html>
