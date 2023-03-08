@extends('layouts.header')
@section('css')
@endsection
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-md-12 grid-margin">
          <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
              <h3 class="font-weight-bold">Welcome {{auth()->user()->name}}</h3>
              {{-- <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6> --}}
            </div>
            <div class="col-12 col-xl-4">
             <div class="justify-content-end d-flex">
              <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                <button class="btn btn-sm btn-light bg-white " type="button"  aria-haspopup="true" aria-expanded="true">
                 <i class="mdi mdi-calendar"></i> Today ({{date('d M, Y')}})
                </button>
              </div>
             </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card tale-bg">
            <div class="card-people mt-auto">
              <img src="{{asset('images/people.svg')}}" alt="people">
              <div class="weather-info">
                <div class="d-flex">
                  <div>
                    <h2 class="mb-0 font-weight-normal"><i class="icon-sun me-2"></i>31<sup>C</sup></h2>
                  </div>
                  <div class="ms-2">
                    <h4 class="location font-weight-normal">Chicago</h4>
                    <h6 class="font-weight-normal">Illinois</h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 grid-margin transparent">
          <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-tale">
                <div class="card-body">
                  <p class="mb-4">Total Groups</p>
                  <p class="fs-30 mb-2">{{$groups->count()}}</p>
                  <p></p>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-dark-blue">
                <div class="card-body">
                  <p class="mb-4">Total Stores</p>
                  <p class="fs-30 mb-2">{{count($companies)}}</p>
                  <p></p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
              <div class="card card-light-blue">
                <div class="card-body">
                  <p class="mb-4">Users</p>
                  <p class="fs-30 mb-2">{{$users->count()}}</p>
                  <p></p>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
              <div class="card card-light-danger">
                <div class="card-body">
                  <p class="mb-4">Unregistered Store</p>
                  <p class="fs-30 mb-2">{{count($companies)-$groups->count()}}</p>
                  <p></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
</div>
@endsection
@section('js')
    <!-- Plugin js for this page -->

    <script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('js/dataTables.select.min.js') }}"></script>
    <!-- End plugin js for this page -->
   
 <!-- Custom js for this page-->
 <script src="{{ asset('js/jquery.cookie.js') }}" type="text/javascript"></script>
 <script src="{{ asset('js/dashboard.js') }}"></script>
 <script src="{{ asset('js/Chart.roundedBarCharts.js') }}"></script>
 <!-- End custom js for this page-->
@endsection
