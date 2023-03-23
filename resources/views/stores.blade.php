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
                                          <th>Schedule</th>
                                          <th>Time In</th>
                                          <th>Time Out</th>
                                          <th>Working Hrs </th>
                                          <th>Lates </th>
                                          <th>Undertime</th>
                                          <th>Overtime</th>
                                          <th>Night Diff</th>
                                        </tr>
                                      </thead>
                                    <tbody>
                                        @foreach($date_range as $date)
                                        @php
                                            $time_in = (($employee->attendances)->where('status','time-in')->where('date',$date))->first();
                                            $time_out = (($employee->attendances)->where('status','time-out')->where('date',$date))->first();
                                            $schedule = (($employee->schedules)->where('date',$date))->first();
                                        @endphp
                                        <tr>
                                            <td>{{date('M d, Y - l',strtotime($date))}}</td>
                                            <td>
                                               <small> {{($schedule != null) ? date('h:i a',strtotime($schedule->time_in)). "-".date('h:i a',strtotime($schedule->time_out))." Working Hrs : ".$schedule->total_hours : "No Schedule"}} </small>
                                               {{-- <small> 
                                                    @foreach(($employee->attendances)->where('date',$date) as $attendances)
                                                        {{$attendances->time." ".$attendances->status}} <br>
                                                    @endforeach
                                                </small> --}}
                                            </td>
                                            <td>{{($time_in != null) ? date('h:i a',strtotime($time_in->time)) : ""}}</td>
                                            <td>{{($time_out != null) ? date('h:i a',strtotime($time_out->time)) : ""}}</td>
                                            <td>{{(($time_in != null) && ($time_out != null) ) ? get_working_hours($time_out->time,$time_in->time)." hrs" : "0.00 hrs" }}  </td>
                                            <td>{{((($time_in != null) && ($time_out != null) && ($schedule != null)) ) ? get_late($schedule,$time_in->time)." hrs" : "0.00 hrs" }}</td>
                                            <td>0.00 </td>
                                            <td>0.00 </td>
                                            <td>{{((($time_in != null) && ($time_out != null) && ($schedule != null)) ) ? night_difference(strtotime($time_in->time),strtotime($time_out->time))." hrs" : "0.00 hrs"}}</td>
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
    function get_late($schedule,$timein)
    {
        $late = (((strtotime($schedule->time_in) - strtotime($timein)))/3600);
        // dd($late);
        if($late < 0)
        {
            $late_data = $late;
        }
        else {
            $late_data = 0;
        
        }
        return round($late_data*-1,2);
    }

    function night_difference($start_work,$end_work)
    {
        $start_night = mktime('22','00','00',date('m',$start_work),date('d',$start_work),date('Y',$start_work));
        $end_night   = mktime('06','00','00',date('m',$start_work),date('d',$start_work) + 1,date('Y',$start_work));

        if($start_work >= $start_night && $start_work <= $end_night)
        {
            if($end_work >= $end_night)
            {
                return ($end_night - $start_work) / 3600;
            }
            else
            {
                return ($end_work - $start_work) / 3600;
            }
        }
        elseif($end_work >= $start_night && $end_work <= $end_night)
        {
            if($start_work <= $start_night)
            {
                return ($end_work - $start_night) / 3600;
            }
            else
            {
                return ($end_work - $start_work) / 3600;
            }
        }
        else
        {
            if($start_work < $start_night && $end_work > $end_night)
            {
                return ($end_night - $start_night) / 3600;
            }
            return 0;
        }
    }
@endphp
@section('js')
    <script src="{{ asset('vendors/select2/select2.min.js')}}"></script>
    <script src="{{ asset('js/select2.js')}}"></script>
@endsection

