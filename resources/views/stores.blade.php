@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">
@endsection
@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
            <div class='row'>
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Stores</h4>
                            <p class="card-description">
                                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                                    <div class=row>
                                        <div class='col-md-3'>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label text-right">Stores</label>
                                                <div class="col-sm-8">
                                                    <select data-placeholder="Select Store" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='store' required>
                                                        <option value="">-- Select store --</option>
                                                        @foreach($stores as $store)
                                                        <option value="{{$store->store}}" @if ($store->store == $storeData) selected @endif>{{$store->store}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-3'>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label text-right">From</label>
                                                <div class="col-sm-8">
                                                    <input type="date" value='{{$from}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-3'>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label text-right">To</label>
                                                <div class="col-sm-8">
                                                    <input type="date" value='{{$to}}' class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required />
                                                </div>
                                            </div>
                                        </div>
                                      
                                        <div class='col-md-3'>
                                            <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Generate</button>
                                        </div>
                                    </div>
                                </form>
                            </p>
                      
                        </div>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                          
                            <h4 class="card-title">Employees</h4>
                            <div class="table-responsive">
                                @foreach($employees as $employee)
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <td colspan='11'>{{$employee->emp_id}} - {{$employee->emp_name}}</td>
                                          </tr>
                                        <tr>
                                          <th>Date</th>
                                          <th>Schedules</th>
                                          <th>Time In</th>
                                          <th>Time Out</th>
                                          <th>Work </th>
                                          <th>Lates </th>
                                          <th>Undertime</th>
                                          <th>Overtime</th>
                                          <th>Approved Overtime</th>
                                          <th>Night Diff</th>
                                          <th>OT Night Diff</th>
                                          <th>Remarks</th>
                                        </tr>
                                      </thead>
                                    <tbody>
                                        @foreach($date_range as $date)
                                        @php
                                            $time_in = (($employee->attendances)->where('status','time-in')->where('date',$date))->first();
                                            $time_out = (($employee->attendances)->where('status','time-out')->where('date',$date))->first();
                                        @endphp
                                        <tr>
                                            <td>{{date('M d, Y - l',strtotime($date))}}</td>
                                            <td></td>
                                            <td>{{($time_in != null) ? date('h:i a',strtotime($time_in->time)) : ""}}</td>
                                            <td>{{($time_out != null) ? date('h:i a',strtotime($time_out->time)) : ""}}</td>
                                            <td> {{(($time_in != null) && ($time_out != null) ) ? get_working_hours($time_out->time,$time_in->time)." hrs" : "" }}  </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                          </tr>
                                          @endforeach
                                    </tbody>
                                </table>
                                @endforeach
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
		</div>
    </div>
@endsection
@php
    function get_working_hours($timeout,$timein)
    {
        return round((((strtotime($timeout) - strtotime($timein)))/3600),2);
    }
@endphp
@section('js')
    <script src="{{ asset('vendors/select2/select2.min.js')}}"></script>
    <script src="{{ asset('js/select2.js')}}"></script>
@endsection

