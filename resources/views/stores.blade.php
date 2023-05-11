@extends('layouts.header_admin')
@section('css')
<link href="{{ asset('admin/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
@endsection
@section('content')
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
            <div class='row'>
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <form method='GET' onsubmit='show();' enctype="multipart/form-data">
                                <div class='row'>
                                <div class="col-lg-3">
                                    <div class="form-group row">
                                    <label class="col-sm-4 col-form-label text-right ">Select Store</label>
                                    <div class="col-sm-8">
                                        <select 
                                            data-placeholder="Select Store" 
                                            class="form-control form-control-sm required chosen-select col-lg-8 col-sm-8" 
                                            style='width:70%; margin-bottom: 10px; margin-right: 10px;'
                                            name='store'
                                            id="storeList"
                                            required>
                                            <option value="">-- Select store --</option>
                                            @foreach($stores as $store)    
                                                <option value="{{$store->store}}" @if ($store->store == $storeData) selected @endif>{{$store->store}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group row">
                                    <label class="col-sm-4 col-form-label text-right">From</label>
                                    <div class="col-sm-8">
                                        <input type="date" value='{{$from}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required />
                                    </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group row">
                                    <label class="col-sm-4 col-form-label text-right">To</label>
                                    <div class="col-sm-8">
                                        <input type="date" value='{{$to}}' class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required />
                                    </div>
                                </div>
                                </div>
                                <div class="col-lg-3">
                                    <button type="submit" class="btn btn-primary col-sm-3 col-lg-3 col-md-3">View</button>
                                    <button type="button" id="set-rates" class="btn btn-primary col-sm-3 col-lg-3 col-md-3" style="margin-left: 15px" data-toggle="modal" title='EDIT'>Set Rates</button>
                                </div>
                                </div>
                            </form> 
              		
              	        </div>
                        {{-- New Laborer --}}
                        <div class="modal fade" id="edit_rates" tabindex="-1" role="dialog" aria-labelledby="EditRatesData" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class='row'>
                                            <div class='col-md-12'>
                                                <h5 class="modal-title" id="EditHoldayData"></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <form  method='POST' action='edit-store-rates' onsubmit='show()'>
                                        <div class="modal-body">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="status" value="2" placeholder='status' class="form-control status" required>
                                            <input type="hidden" name="rateid" placeholder='status' class="form-control rateid" required>
                                            <input type="hidden" name="store" placeholder='status' class="form-control store" required>
                                            <label>Daily Rate:</label>
                                            <input type="text" name="dailyRate" placeholder='Holiday Name'class="form-control dailyRate" required>
                                            <label >Holiday Rate:</label>
                                            <input type="text" name="holidayRate" placeholder='Holiday Name' class="form-control holidayRate" required>
                                            <label >Holiday OT Rate:</label>
                                            <input type="text" name="holidayot" placeholder='Holiday Name' class="form-control holidayot" required>
                                            <label >Holiday Rest Day Rate:</label>
                                            <input type="text" name="holidayrestday" placeholder='Holiday Name' class="form-control holidayRestDay" required>
                                            <label >Holiday Rest Day OT Rate:</label>
                                            <input type="text" name="holidayrestdayot" placeholder='Holiday Name' class="form-control holidayRestDayOt" required>
                                            <label >Nightshift Rate:</label>
                                            <input type="text" name="nightshift" placeholder='Holiday Name' class="form-control nightshift" required>
                                            <label >Overtime Rate:</label>
                                            <input type="text" name="overtime" placeholder='Holiday Name'class="form-control overtime" required>
                                            <label >SSS Rate:</label>
                                            <input type="text" name="sss" placeholder='Holiday Name' class="form-control sss" required>
                                            <label >Pag-ibig Rate:</label>
                                            <input type="text" name="pagibig" placeholder='Holiday Name' class="form-control pagibig" required>
                                            <label >Philhealth Rate:</label>
                                            <input type="text" name="philhealth" placeholder='Holiday Name' class="form-control philhealth" required>
                                            <label >Rest Day Duty Rate:</label> 
                                            <input type="text" name="restday" placeholder='Holiday Name' class="form-control restday" required>
                                            <label >Rest Day OT Rate:</label>
                                            <input type="text" name="restdayot" placeholder='Holiday Name' class="form-control restdayot" required>
                                            <label >Special Holiday Rate:</label>
                                            <input type="text" name="specialholiday" placeholder='Holiday Name' class="form-control specialholiday" required>
                                            <label >Special Holiday Rate:</label>
                                            <input type="text" name="specialholidayot" placeholder='Holiday Name' class="form-control specialholidayot" required><label >Special Holiday RD Rate:</label>
                                            <input type="text" name="specialholidayrestday" placeholder='Holiday Name' class="form-control specialholidayrestday" required>
                                            <label >Special Holiday RD OT Rate:</label>
                                            <input type="text" name="specialholidayrestdayot" placeholder='Holiday Name' class="form-control specialholidayrestdayot" required>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" id='submit1' class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" >
                                  <thead>
                                    <tr>
                                      <th>Employee Name</th>
                                      <th>Days Work</th>
                                      <th>Hours Work</th>
                                      <th>Hours Tardy</th>
                                      <th>Overtime</th>
                                      <th>Special holiday</th>
                                      <th>Legal holiday</th>
                                      <th>Night Diff</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                 
                                    @foreach($employees as $employee)
                                    @php
                                        $day_works = 0;
                                        $working_hours = 0;
                                        $hours_tardy = 0;
                                        $overtime = 0;
                                        $special_holiday = 0;
                                        $legal_holiday = 0;
                                        $night_diff = 0;
                                    @endphp
                                            @foreach($date_range as $date)
                                                @php
                                                    $time_in = (($employee->attendances)->where('status','time-in')->where('date',$date))->first();
                                                    $time_out = (($employee->attendances)->where('status','time-out')->where('date',$date))->first();
                                                    $schedule = (($employee->schedules)->where('date',$date))->first();
                                                    if(($time_in != null) && ($time_out != null))
                                                    {
                                                        $day_works = $day_works+1;
                                                        $working = get_working_hours($time_out->time,$time_in->time);
                                                        if($working > 8)
                                                        {
                                                            $working_hours = $working_hours + 8;
                                                        }
                                                        else 
                                                        {
                                                            $working_hours = $working_hours + $working;
                                                        }
                                                        if($schedule != null)
                                                        {
                                                            $late = get_late($schedule,$time_in->time);
                                                            $hours_tardy = $hours_tardy+$late;
                                                            $night_difference = night_difference(strtotime($time_in->time),strtotime($time_out->time));
                                                            $night_diff = $night_diff+$night_difference;
                                                        }
                                                    }
                                                @endphp
                                            @endforeach
                                        <tr data-target="#viewRecord{{$employee->emp_id}}" data-toggle="modal" title='RECORD'>
                                            <td>
                                                
                                                {{$employee->emp_name}}
                                               </td>
                                            <td>{{number_format($day_works,2)}}</td>
                                            <td>{{$working_hours}}</td>
                                            <td>{{$hours_tardy}}</td>
                                            <td>{{$overtime}}</td>
                                            <td>{{$special_holiday}}</td>
                                            <td>{{$legal_holiday}}</td>
                                            <td>{{$night_diff}}</td>
                                        </tr>
                                        
                                    @endforeach
                                  </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach($employees as $employee)
            @include('users.view_attedances')
            @endforeach
            {{-- <div class='row'>
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
                                            $schedule = (($schedules)->where('emp_id',$employee->_id)->where('date',$date))->first();
                                        @endphp
                                        <tr>
                                            <td>{{date('M d, Y - l',strtotime($date))}}</td>
                                            <td>
                                               <small> {{($schedule != null) ? date('h:i a',strtotime($schedule->time_in)). "-".date('h:i a',strtotime($schedule->time_out))." Working Hrs : ".$schedule->total_hours : "No Schedule"}} </small>
                                             
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
            </div> --}}
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
    <script src="{{ asset('admin/js/plugins/chosen/chosen.jquery.js')}}"></script>
    <script src="{{ asset('admin/js/inspinia.js')}}"></script>
    <script src="{{ asset('admin/js/plugins/pace/pace.min.js')}}"></script>
    <script>
          $(document).ready(function(){
            $('.chosen-select').chosen({width: "100%"});
          });
          const buttons = document.getElementById("set-rates");
          $('#set-rates').click(async function() {
            const store = $("#storeList").val()
            if (store) {
                document.getElementById("loader").style.display = "block"
                const data = {
                    "store": store
                }
                const option = {
                    method: "POST",
                    headers: {
                      "Accept": 'application/json',
                      "Content-Type": "application/json",
                    },
                    body: JSON.stringify(data)
                }
                const response = await fetch(`https://payroll.sparkles.com.ph/api/rates`, option)
                const d = await response.json()
                if (d.status === "success") {
                    $('.modal-title').text(store + " RATES")
                    $('.dailyRate').val(d.data.daily)
                    $('.rateid').val(0)
                    $('.holidayRate').val(d.data.holiday)
                    $('.holidayot').val(d.data.holidayot)
                    $('.holidayRestDay').val(d.data.holidayrestday)
                    $('.holidayRestDayOt').val(d.data.holidayrestdayot)
                    $('.nightshift').val(d.data.nightshift)
                    $('.overtime').val(d.data.overtime)
                    $('.sss').val(d.data.sss)
                    $('.pagibig').val(d.data.pagibig)
                    $('.philhealth').val(d.data.philhealth)
                    $('.restday').val(d.data.restday)
                    $('.restdayot').val(d.data.restdayot)
                    $('.specialholiday').val(d.data.specialholiday)
                    $('.specialholidayot').val(d.data.specialholidayot)
                    $('.specialholidayrestday').val(d.data.specialholidayrestday)
                    $('.specialholidayrestdayot').val(d.data.specialholidayrestdayot)
                    $('.store').val(store)
                    $(`#edit_rates`).modal().show();
                    document.getElementById("loader").style.display = "none";

                }
                else {
                    alert("Something went wrong please contact admin")
                    document.getElementById("loader").style.display = "none";
                }
            }
            else {
                alert("Please select store")
                document.getElementById("loader").style.display = "none"
            }
            document.getElementById("loader").style.display = "none"
          })
    </script>
@endsection

