@extends('layouts.header')

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
                  <p class="fs-30 mb-2">0</p>
                  <p></p>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
              <div class="card card-dark-blue">
                <div class="card-body">
                  <p class="mb-4">Total Stores</p>
                  <p class="fs-30 mb-2">0</p>
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
                  <p class="fs-30 mb-2">0</p>
                  <p></p>
                </div>
              </div>
            </div>
            <div class="col-md-6 stretch-card transparent">
              <div class="card card-light-danger">
                <div class="card-body">
                  <p class="mb-4">Number of Clients</p>
                  <p class="fs-30 mb-2">47033</p>
                  <p>0.22% (30 days)</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>

    <!-- partial -->
  </div>
@endsection
