@extends('layouts.header_admin')
@section('css')
@endsection
@section('content')
<div class="row">
  <div class="col-lg-3">
      <div class="ibox float-e-margins">
          <div class="ibox-title">
              <span class="label label-success pull-right">as of Today</span>
              <h5>Total Groups</h5>
          </div>
          <div class="ibox-content">
              <h1 class="no-margins">{{$groups->count()}}</h1>
              {{-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> --}}
              <small>&nbsp;</small>
          </div>
      </div>
  </div>
  <div class="col-lg-3">
      <div class="ibox float-e-margins">
          <div class="ibox-title">
              <span class="label label-warning pull-right">as of Today</span>
              <h5>Active Stores</h5>
          </div>
          <div class="ibox-content">
              <h1 class="no-margins">{{$stores->count()}}</h1>
              {{-- <div class="stat-percent font-bold text-navy">44% <i class="fa fa-level-up"></i></div> --}}
              <small>&nbsp;</small>
          </div>
      </div>
  </div>
  <div class="col-lg-3">
      <div class="ibox float-e-margins">
          <div class="ibox-title">
              <span class="label label-primary pull-right">as of Today</span>
              <h5>Users</h5>
          </div>
          <div class="ibox-content">
              <h1 class="no-margins">{{$users->count()}}</h1>
              {{-- <div class="stat-percent font-bold text-navy">44% <i class="fa fa-level-up"></i></div> --}}
              <small>&nbsp;</small>
          </div>
      </div>
  </div>
  <div class="col-lg-3">
      <div class="ibox float-e-margins">
          <div class="ibox-title">
              <span class="label label-danger pull-right">as of Today {{date('M d, Y')}}</span>
              <h5>Employees Time-In</h5>
          </div>
          <div class="ibox-content">
              <h1 class="no-margins">{{$employees->count()}}</h1>
              {{-- <div class="stat-percent font-bold text-navy">44% <i class="fa fa-level-up"></i></div> --}}
              <small>&nbsp;</small>
          </div>
      </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-7">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Attendance Logs</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
              
            </div>
        </div>
        <div class="ibox-content">

          <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover dataTables-example" >
                <thead>
                  <tr>
                      <th>Date - Time</th>
                      <th>Name</th>
                      <th>Store</th>
                      <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr>
                        <td>{{date('M d, Y H:i:s',strtotime($attendance->time))}}</td>
                        <td>{{$attendance->emp_name}}</td>
                        <td>{{$attendance->store}}</td>
                        <td>{{$attendance->status}}</td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
          </div>

      </div>
 
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{ asset('admin/js/inspinia.js')}}"></script>
<script src="{{ asset('admin/js/plugins/pace/pace.min.js')}}"></script>
@endsection