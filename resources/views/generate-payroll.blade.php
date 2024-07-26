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
                                    required onchange='get_last_payroll(this);'>
                                    <option value="">-- Select store --</option>
                                    @php
                                        $userId = auth()->user()->id;
                                    @endphp
                                    @foreach($stores as $store)
                                        @if(($userId == 1 && strpos($store->store, 'Inhouse') === false) || 
                                            ($userId == 2 && (strpos($store->store, 'Inhouse') !== false || strpos($store->store, 'Star Terran') !== false)))
                                            <option value="{{$store->store}}" @if ($store->store == $storeData) selected @endif>{{$store->store}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-right">From</label>
                            <div class="col-sm-8">
                                <input type="date" value='{{$from}}' class="form-control" id='from' name="from" min='{{date('Y-m-d', strtotime("+1 day", strtotime($payroll_last)))}}' max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required />
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-right">To</label>
                            <div class="col-sm-8">
                                <input type="date" value='{{$to}}' class="form-control" name="to" min='{{date('Y-m-d', strtotime("+1 day", strtotime($payroll_last)))}}' id='to' max='{{date('Y-m-d')}}' required />
                            </div>
                        </div>
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary col-sm-3 col-lg-3 col-md-3">Generate</button>
                        </div>
                        </div>
                    </form> 
                </div>
            
                @if(count($employees) > 0)
                @if($rates != null)
                <form onsubmit="handleSubmit(event);">
                    @csrf
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example" id="myTable">
                                <thead>
                                    <tr>
                                        <td colspan='26'>
                                            <div class='row'>
                                                <div class="col-lg-12">
                                                    <button type="submit" class="btn btn-danger" id='save' >Save Payroll</button>
                                                </div>
                                                <div class="col-lg-12">
                                                    {{$storeData}}  
                                                </div>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='26'>
                                            <input type='hidden' name='from_date' id="fromDate" value='{{$from}}'>
                                            <input type='hidden' name='from_to' id="toDate" value='{{$to}}'>
                                            <input type='hidden' id="store" value='{{$storeData}}'>
                                            Payroll Period of  {{date('M d, Y',strtotime($from))}}  to  {{date('M d, Y',strtotime($to))}}
                                            @if(count($employees) >0)
                                                <h5>Holidays <br>
                                                    @foreach($holidays as $holiday)
                                                        {{$holiday->holiday_name}} - {{date('M d',strtotime($holiday->holiday_date))}} - {{$holiday->holiday_type}}  <br>
                                                    @endforeach
                                                </h5>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                    
                                    <th>#</th>
                                    <th>Employee Name</th>
                                    @if(!empty($rates) && $rates->daily > 0)
                                        <th>Daily Rate</th>
                                        <th>Daily Rate/Hour </th>
                                    @endif
                                    <th>Days Work</th>
                                    <th>Hours Work</th>
                                    <th>Basic Pay</th>
                                    <th>Hours Tardy</th>
                                    <th>Hours Tardy Basic</th>
                                    <th>Overtime</th>
                                    <th>Amount Overtime</th>
                                    @if($rates->specialholiday != "undefined" && $rates->specialholiday > 0)
                                    <th>Special holiday</th>
                                    <th>Amount Special holiday</th>
                                    @endif
                                    @if($rates->holiday != "undefined" && $rates->holiday > 0)
                                    <th>Legal holiday</th>
                                    <th>Amount Legal Holiday</th>
                                    @endif
                                    @if($rates->nightshift != "undefined" && $rates->nightshift > 0)
                                    <th>Night Diff</th>
                                    <th>Amount Night Diff</th>
                                    @endif
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
                                                        $holi = $holidays->pluck('holiday_date');
                                                        $holiday = $holidays->where('holiday_date',$date)->first();
                                                        if($holiday != null)
                                                        {
                                                            if($holiday->holiday_type == "Special Holiday")
                                                            {
                                                                $special_holiday = $special_holiday+1;
                                                            }
                                                            else {
                                                                $legal_holiday = $legal_holiday+1;
                                                            }
                                                        }
                                                        $time_in = (($employee->attendances)->where('status','time-in')->where('date',$date))->first();
                                                        $time_out = (($employee->attendances)->where('status','time-out')->where('date',$date))->first();
                                                        $schedule = (($employee->schedules)->where('date',$date))->first();
                                                        if(($time_in != null) && ($time_out != null))
                                                        {
                                                            $day_works = $day_works+1;
                                                            $working = get_working_hours($time_out->time,$time_in->time);
                                                            if($rates->late != null)
                                                            {
                                                                $working = 8;
                                                            }

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
                                                                if($rates->late == null)
                                                                {
                                                                    $late = get_late($schedule,$time_in->time);
                                                                }
                                                                
                                                                $hours_tardy = $hours_tardy+$late;
                                                                $night_difference = night_difference(strtotime($time_in->time),strtotime($time_out->time), $schedule);
                                                                $night_diff = $night_diff+$night_difference;
                                                            }
                                                        }

                                                    
                                                    @endphp
                                                @endforeach
                                                @php
                                                    if(count($employee->rate) >0)
                                                    {
                                                        $rate_d = ($employee->rate)->first();
                                                        $rate_employee = $rate_d->daily;
                                                    }
                                                    else {
                                                        $rate_employee = $rate;
                                                    }

                                                        $basic_pay = ($rate_employee/8)*$working_hours;
                                                        $tardy_amount = ($rate_employee/8/60)*$hours_tardy;
                                                        $overtime_amount = ($rate_employee*1.25)*$overtime;
                                                        $nightdiff_amount = ($rate_employee*.1)*$night_diff;
                                                        $special_holiday_amount = ($rate_employee * .3) * $special_holiday;
                                                        $legal_holiday_amount = $legal_holiday * $rate_employee;
                                                        $gross_pay = $basic_pay - $tardy_amount + $overtime_amount + $nightdiff_amount +$special_holiday_amount +$legal_holiday_amount;
                                                        $other_income_non_tax = 0;
                                                      
                                                        $sss = 0;
                                                        $philhealth = 0;
                                                        $pagibig = 0;
                                                        $sss_er = 0;
                                                        if($basic_pay >= 1)
                                                        {
                                                            
                                                            $sssData = $sssTable->where('from_range','<=',$gross_pay)->where('to_range', '>=', $gross_pay)->first();
                                                            if($sssData != null)
                                                            {
                                                                $sss = $sssData->ee;
                                                                $sss_er = $sssData->er;
                                                            }
                                                            if($rates->gross_pay != 0) {
                                                                $philhealth = ((($rate_employee*313*.04)/12)/2);
                                                                $philhealth = 200;
                                                                $pagibig = 200.00;    
                                                            }
                                                            else {
                                                                $philhealth = ((($rate_employee*313*.05)/12)/2);
                                                            $philhealth = $rates->philhealth;
                                                            $pagibig = $rates->pagibig;
                                                            }
                                                            
                                                            
                                                        }
                                                        $total_deduction = $sss + $philhealth + $pagibig;
                                                        $net = $gross_pay - $total_deduction + $other_income_non_tax;
                                                        
                                                        
                                                @endphp
                                                
                                                <tr >
                                                    @if($working_hours > 0)
                                                    <td>{{$c++}}<input type='hidden' id='emp_id[{{$key}}]' value='{{$employee->emp_id}}'><input type='hidden' id='emp_name[{{$key}}]' value='{{$employee->emp_name}}'></td>
                                                    <td>{{$employee->emp_name}}</td>
                                                    @if(!empty($rates) && $rates->daily > 0)
                                                    <td class='text-right'>{{number_format($rate_employee,2)}}<input type='hidden' id='rate[{{$key}}]' value='{{$rate_employee}}'></td>

                                                    <td class='text-right'>{{number_format($rate_employee/8,2)}} <input type='hidden' id='daily_rate[{{$key}}]' value='{{$rate_employee/8}}'></td>
                                                    @endif
                                                    <td class='text-right'>{{number_format($day_works,2)}} <input type='hidden' id='day_works[{{$key}}]' value='{{$day_works}}'></td>
                                                    <td class='text-right'>{{number_format($working_hours,2)}} <input type='hidden' id='working_hours[{{$key}}]' value='{{$working_hours}}'></td>
                                                    <td class='text-right'>{{number_format($basic_pay,2)}} <input type='hidden' id='basic_pay[{{$key}}]' value='{{$basic_pay}}'></td>
                                                    <td class='text-right'>{{number_format($hours_tardy,2)}} <input type='hidden' id='hours_tardy[{{$key}}]' value='{{$hours_tardy}}'></td>
                                                    <td class='text-right'>{{number_format($tardy_amount,2)}} <input type='hidden' id='tardy_amount[{{$key}}]' value='{{$tardy_amount}}'></td>
                                                    <td class='text-right'>{{number_format($overtime,2)}} <input type='hidden' id='overtime[{{$key}}]' value='{{$overtime}}'></td>
                                                    <td class='text-right'>{{number_format($overtime_amount,2)}} <input type='hidden' id='overtime_amount[{{$key}}]' value='{{$overtime_amount}}'></td>
                                                    @if($rates->specialholiday != "undefined" && $rates->specialholiday > 0)
                                                    <td class='text-right'>{{number_format($special_holiday,2)}} <input type='hidden' id='special_holiday[{{$key}}]' value='{{$special_holiday}}'></td>
                                                    <td class='text-right'>{{number_format($special_holiday_amount,2)}}  <input type='hidden' id='special_holiday_amount[{{$key}}]' value='{{$special_holiday_amount}}'></td>
                                                    @endif
                                                    @if($rates->holiday != "undefined" && $rates->holiday > 0)
                                                    <td class='text-right'>{{number_format($legal_holiday,2)}} <input type='hidden' id='legal_holiday[{{$key}}]' value='{{$legal_holiday}}'></td>
                                                    <td class='text-right'>{{number_format($legal_holiday_amount,2)}} <input type='hidden' id='legal_holiday_amount[{{$key}}]' value='{{$legal_holiday_amount}}'></td>
                                                    @endif
                                                    @if($rates->nightshift != "undefined" && $rates->nightshift > 0)
                                                    <td class='text-right'>{{number_format($night_diff,2)}} <input type='hidden' id='night_diff[{{$key}}]' value='{{$night_diff}}'></td>
                                                    <td class='text-right'>{{number_format($nightdiff_amount,2)}} <input type='hidden' id='nightdiff_amount[{{$key}}]' value='{{$nightdiff_amount}}'></td>
                                                    @endif
                                                    <td class='text-right'>{{number_format($gross_pay,2)}} <input type='hidden' id='gross_pay[{{$key}}]' value='{{$gross_pay}}'></td>
                                                    <td class='text-right'>{{number_format($other_income_non_tax,2)}} <input type='hidden' id='other_income_non_tax[{{$key}}]' value='{{$other_income_non_tax}}'></td>
                                                    <td class='text-right'>{{number_format($sss,2)}} <input type='hidden' id='sss[{{$key}}]' value='{{$sss}}'> <input type='hidden' id='sss_er[{{$key}}]' value='{{$sss_er}}'></td>
                                                    <td class='text-right'>{{number_format($philhealth,2)}} <input type='hidden' id='philhealth[{{$key}}]' value='{{$philhealth}}'></td>
                                                    <td class='text-right'>{{number_format($pagibig,2)}} <input type='hidden' id='pagibig[{{$key}}]' value='{{$pagibig}}'></td>
                                                    <td class='text-right'>0.00<input type='hidden' id='other_deduction[{{$key}}]' value='0.00'></td>
                                                    <td class='text-right'>{{number_format($total_deduction,2)}} <input type='hidden' id='total_deduction[{{$key}}]' value='{{$total_deduction}}'></td>
                                                    <td class='text-right'>{{number_format($net,2)}} <input type='hidden' id='net[{{$key}}]' value='{{$net}}'></td>
                                                    <td></td>
                                                </tr>
                                                @endif
                                            @endforeach
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </form>
                @else
                
                <div class="ibox-content">
                    <div class="alert alert-warning">
                        No rates found for {{$storeData}} <br>
                        <b>Please click <a href='{{url('/stores')}}' target="_blank">{{$storeData}}</a>, select store then set rates. </b>
                    </div>
                </div>

                @endif
                @endif
               
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
        
        $startTime = new DateTime($timein);
        $timeInTimeOnly = $startTime->format('H:i:s');
        $endTime = new DateTime($schedule->time_in);
        $ScheduledtimeInTimeOnly = $endTime->format('H:i:s');
        $formattedDateTimeIn = new DateTime($timeInTimeOnly);
        if ($timeInTimeOnly > $ScheduledtimeInTimeOnly)
        {
            $interval = $formattedDateTimeIn->diff(new DateTime ($ScheduledtimeInTimeOnly));
            $hours = $interval->h;
            $minutes = $interval->i;
            $lateMinutes = $hours * 60 + $minutes;
        }
        else {
            $lateMinutes = 0;
        }
        return $lateMinutes;
    }

    function night_difference($start_work,$end_work,$schedule)
    {
        $startTime = new DateTime($schedule->time_in);
        $startTimeTimeOnly = $startTime->format('H:i:s');
        $start_night = mktime('22','00','00',date('m',$start_work),date('d',$start_work),date('Y',$start_work));
        $end_night   = mktime('06','00','00',date('m',$start_work),date('d',$start_work) + 1,date('Y',$start_work));
        if($startTime <= new DateTime("22:00:00")){
            return 0;
        }
        else {
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
        
    }
   
@endphp
@endsection

@section('js')
<script src="{{ asset('admin/js/plugins/chosen/chosen.jquery.js')}}"></script>
<script src="{{ asset('admin/js/inspinia.js')}}"></script>
<script src="{{ asset('admin/js/plugins/pace/pace.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function(){
      $('.chosen-select').chosen({width: "100%"});
    });
    async function handleSubmit(e) {
        e.preventDefault();

        var data = [];
        var table = document.getElementById('myTable');

        var userId = "{{ auth()->user()->id }}";
        var userName = "{{ auth()->user()->name }}";
    
        for (var r = 0; r < table.rows.length; r++) { 
            var id = `emp_id[${r}]`;
            var name = `emp_name[${r}]`;
            var dailyRate = `rate[${r}]`;
            var ratePerHour = `daily_rate[${r}]`;
            var workingHours = `working_hours[${r}]`;
            var daysWork = `day_works[${r}]`;
            var basicPay = `basic_pay[${r}]`;
            var hoursTardy = `hours_tardy[${r}]`;
            var tardyAmount = `tardy_amount[${r}]`;
            var overtime = `overtime[${r}]`;
            var overtimeAmount = `overtime_amount[${r}]`;
            var specialHoliday = `special_holiday[${r}]`;
            var specialHolidayAmount = `special_holiday_amount[${r}]`;
            var legalHoliday = `legal_holiday[${r}]`;
            var legalHolidayAmount = `legal_holiday_amount[${r}]`;
            var nightDiff = `night_diff[${r}]`;
            var nightDiffAmount = `nightdiff_amount[${r}]`;
            var grossPay = `gross_pay[${r}]`;
            var otherIncomeNonTax = `other_income_non_tax[${r}]`;
            var sssEr = `sss_er[${r}]`;
            var philhealth = `philhealth[${r}]`;
            var pagibig = `pagibig[${r}]`;
            var otherDeduction = `other_deduction[${r}]`;
            var totalDeduction = `total_deduction[${r}]`;
            var net = `net[${r}]`;
            var sss = `sss[${r}]`;

        if (document.getElementById(workingHours) !== null) {
            let rowData = {
                emp_id: document.getElementById(id) ? document.getElementById(id).value : null,
                emp_name: document.getElementById(name) ? document.getElementById(name).value : null,
                rate: document.getElementById(dailyRate) ? document.getElementById(dailyRate).value : null,
                daily_rate: document.getElementById(ratePerHour) ? document.getElementById(ratePerHour).value : null,
                working_hours: document.getElementById(workingHours) ? document.getElementById(workingHours).value : null,
                days_work: document.getElementById(daysWork) ? document.getElementById(daysWork).value : null,
                basic_pay: document.getElementById(basicPay) ? document.getElementById(basicPay).value : null,
                hours_tardy: document.getElementById(hoursTardy) ? document.getElementById(hoursTardy).value : null,
                tardy_amount: document.getElementById(tardyAmount) ? document.getElementById(tardyAmount).value : null,
                overtime: document.getElementById(overtime) ? document.getElementById(overtime).value : null,
                overtime_amount: document.getElementById(overtimeAmount) ? document.getElementById(overtimeAmount).value : null,
                special_holiday: document.getElementById(specialHoliday) ? document.getElementById(specialHoliday).value : null,
                special_holiday_amount: document.getElementById(specialHolidayAmount) ? document.getElementById(specialHolidayAmount).value : null,
                legal_holiday: document.getElementById(legalHoliday) ? document.getElementById(legalHoliday).value : null,
                legal_holiday_amount: document.getElementById(legalHolidayAmount) ? document.getElementById(legalHolidayAmount).value : null,
                night_diff: document.getElementById(nightDiff) ? document.getElementById(nightDiff).value : null,
                nightdiff_amount: document.getElementById(nightDiffAmount) ? document.getElementById(nightDiffAmount).value : null,
                gross_pay: document.getElementById(grossPay) ? document.getElementById(grossPay).value : null,
                other_income_non_tax: document.getElementById(otherIncomeNonTax) ? document.getElementById(otherIncomeNonTax).value : null,
                sss_er: document.getElementById(sssEr) ? document.getElementById(sssEr).value : null,
                sss: document.getElementById(sss) ? document.getElementById(sss).value : null,
                philhealth: document.getElementById(philhealth) ? document.getElementById(philhealth).value : null,
                pagibig: document.getElementById(pagibig) ? document.getElementById(pagibig).value : null,
                other_deduction: document.getElementById(otherDeduction) ? document.getElementById(otherDeduction).value : null,
                total_deduction: document.getElementById(totalDeduction) ? document.getElementById(totalDeduction).value : null,
                net: document.getElementById(net) ? document.getElementById(net).value : null,
                from: document.getElementById("fromDate") ? document.getElementById("fromDate").value : null,
                to: document.getElementById("toDate") ? document.getElementById("toDate").value : null,
                id: userId ? userId : (document.getElementById("id") ? document.getElementById("id").value : null),
                generatedbyname: userName ? userName : (document.getElementById("generatedbyname") ? document.getElementById("generatedbyname").value : null),
                store: document.getElementById("store") ? document.getElementById("store").value : null
            };
            data.push(rowData);
        }
    }

    data.sort((a, b) => a.emp_name.localeCompare(b.emp_name));

    try {
        let response = await fetch('https://payroll-live.7star.com.ph/public/api/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        let result = await response.json();
        console.log('Response from server:', result); 

        if (result.message === "payroll already created") {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Payroll already created.',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('payrolls') }}"
                }
            });
        } else if (result.message === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Payroll successfully saved.',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('payrolls') }}"
                }
            });
        } else {
            console.log('Unexpected response message:', result.message);
        }
        } catch (error) {
            console.error('Error submitting data:', error); // Log any errors
        }
    }
    function get_last_payroll(data)
    {
        document.getElementById("from").value = "";
        document.getElementById("to").value = "";
        document.getElementById("from").min = "";
        document.getElementById("to").min = "";
        let sites = {!! json_encode($stores) !!}
        let searchIndex = sites.findIndex((site) => site.store==data.value);
        if(sites[searchIndex].payroll != null)
        {  
            let date = new Date(sites[searchIndex].payroll.payroll_to);
                date = date.setDate(date.getDate() + 1);
            document.getElementById("to").min = formatDate(date);
            document.getElementById("from").min = formatDate(date);
        }
    }
    function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}
</script>
@endsection

