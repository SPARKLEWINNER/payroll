@extends('layouts.header_admin')
@section('css')
<link href="{{ asset('admin/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
@endsection
@section('content')
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
                            <button type="submit" class="btn btn-primary col-sm-3 col-lg-3 col-md-3">Generate</button>
                        </div>
                        </div>
                    </form> 
                </div>
                <div class="ibox-content">
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <td colspan='26'>
                                        {{$storeData}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='26'>
                                        Payroll Period of  {{date('M d, Y',strtotime($from))}}  to  {{date('M d, Y',strtotime($to))}}
                                        {{-- @if(count($employees) >0)
                                            <h5>Holidays <br>
                                                @foreach($holidays as $holiday)
                                                    {{$holiday->holiday_name}} - {{$holiday->holiday_date}} - {{$holiday->holiday_type}}  <br>
                                                @endforeach
                                            </h5>
                                        @endif --}}
                                    </td>
                                </tr>
                                <tr>
                                  <th>#</th>
                                  <th>Employee Name</th>
                                  <th>Daily Rate</th>
                                  <th>Daily Rate/Hour </th>
                                  <th>Days Work</th>
                                  <th>Hours Work</th>
                                  <th>Basic Pay</th>
                                  <th>Hours Tardy</th>
                                  <th>Hours Tardy Basic</th>
                                  <th>Overtime</th>
                                  <th>Amount Overtime</th>
                                  <th>Special holiday</th>
                                  <th>Amount Special holiday</th>
                                  <th>Legal holiday</th>
                                  <th>Amount Legal Holiday</th>
                                  <th>Night Diff</th>
                                  <th>Amount Night Diff</th>
                                  <th>Gross Pay</th>
                                  <th>Other Income Non Taxable</th>
                                  <th>SSS Contribution</th>
                                  <th>NHIP Contribution</th>
                                  <th>HDMF Contribution</th>
                                  <th>Other Deductions</th>
                                  <th>Total Deductions</th>
                                  <th>NET PAY</th>
                                  <th>ATM</th>
                                </tr>
                              </thead>
                            <tbody>
                                @php
                                    $c = 1;
                                @endphp
                                    @foreach($employees as $key => $employee)
                                    @php
                                        $day_works = 0;
                                        $working_hours = 0;
                                        $hours_tardy = 0;
                                        $overtime = 0;
                                        $special_holiday = 0;
                                        $legal_holiday = 0;
                                        $night_diff = 0;
                                        $basic_pay = 0;
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
                                        @php
                                            if($employee->rate != null)
                                            {
                                                $rate_d = ($employee->rate)->first();
                                                $rate_employee = $rate_d->daily;
                                            }
                                            else {
                                                $rate_employee = $rate;
                                            }

                                                $basic_pay = ($rate_employee/8)*$working_hours;
                                                $tardy_amount = ($rate_employee/60)*$hours_tardy;
                                                $overtime_amount = ($rate_employee*1.25)*$overtime;
                                                $nightdiff_amount = ($rate_employee*.1)*$night_diff;
                                                $gross_pay = $basic_pay - $tardy_amount + $overtime_amount + $nightdiff_amount;
                                                $other_income_non_tax = $day_works*15;
                                                $sss = 0;
                                                $philhealth = 0;
                                                $pagibig = 0;
                                                if($basic_pay >= 1)
                                                {
                                                    
                                                    $sssData = $sssTable->where('from_range','<',$gross_pay)->first();
                                                    if($sssData != null)
                                                    {
                                                        $sss = $sssData->ee;
                                                    }
                                                    $philhealth = ((($rate_employee*313*.04)/12)/2);
                                                    $pagibig = 100.00;
                                                    
                                                }
                                                $total_deduction = $sss + $philhealth + $pagibig;
                                                $net = $gross_pay - $total_deduction + $other_income_non_tax;
                                                
                                                
                                        @endphp
                                        <tr >
                                            <td>{{$c++}}</td>
                                            <td>{{$employee->emp_name}}</td>
                                            <td class='text-right'>{{number_format($rate_employee,2)}}</td>
                                            <td class='text-right'>{{number_format($rate_employee/8,2)}}</td>
                                            <td class='text-right'>{{number_format($day_works,2)}}</td>
                                            <td class='text-right'>{{number_format($working_hours,2)}}</td>
                                            <td class='text-right'>{{number_format($basic_pay,2)}}</td>
                                            <td class='text-right'>{{number_format($hours_tardy,2)}}</td>
                                            <td class='text-right'>{{number_format($tardy_amount,2)}}</td>
                                            <td class='text-right'>{{number_format($overtime,2)}}</td>
                                            <td class='text-right'>{{number_format($overtime_amount,2)}}</td>
                                            <td class='text-right'>{{number_format($special_holiday,2)}}</td>
                                            <td class='text-right'>0.00</td>
                                            <td class='text-right'>{{number_format($legal_holiday,2)}}</td>
                                            <td class='text-right'>0.00</td>
                                            <td class='text-right'>{{number_format($night_diff,2)}}</td>
                                            <td class='text-right'>{{number_format($nightdiff_amount,2)}}</td>
                                            <td class='text-right'>{{number_format($gross_pay,2)}}</td>
                                            <td class='text-right'>{{number_format($other_income_non_tax,2)}}</td>
                                            <td class='text-right'>{{number_format($sss,2)}}</td>
                                            <td class='text-right'>{{number_format($philhealth,2)}}</td>
                                            <td class='text-right'>{{number_format($pagibig,2)}}</td>
                                            <td class='text-right'>0.00</td>
                                            <td class='text-right'>{{number_format($total_deduction,2)}}</td>
                                            <td class='text-right'>{{number_format($net,2)}}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan=23>
                                    </th>
                                </tr>
                                <tr>
                                    <th class='text-right' colspan=2>
                                        GRAND TOTALS 
                                    </th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th>0.00</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th class='text-right' colspan=3>
                                        <p class='text-right'>{{count($employees)}} Records</p> 
                                    </th>
                                    <th colspan='23'></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endsection

@section('js')
<script src="{{ asset('admin/js/plugins/chosen/chosen.jquery.js')}}"></script>
<script src="{{ asset('admin/js/inspinia.js')}}"></script>
<script src="{{ asset('admin/js/plugins/pace/pace.min.js')}}"></script>
<script>
    $(document).ready(function(){
      $('.chosen-select').chosen({width: "100%"});
    });
</script>
@endsection

