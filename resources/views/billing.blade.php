<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" type="image/png" href="{{ asset('/images/icon.png')}}"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        .page_break { page-break-before: always; }
        body { margin-top: 63px; }
        #first 
        {
            display:none;
        }
        table { 
            border-spacing: 0;
            border-collapse: collapse;
            margin-top: 10px;
            font-size : 8px;
        }
        body{
            font-family: Calibri;
            font-size: 8px;
        }
        .page-break {
            page-break-after: always;
        }
        header {
            position: fixed;
            top: -15px;
            left: 0px;
            right: 0px;
            color: black;
            text-align: left;
            font
        }
        .text-right
        {
            text-align: right;
        }
        .footer
        {
            position: fixed;
            top: 750px;
            left: 500px;
            right: 0px;
            height: 50px;
        }
        .fixed
        {
            position: fixed;
            top: -35px;
            left: 800px;
            right: 0px;
            height: 20px;
        }
        .page-number:after { content: counter(page); }
    </style>
    
</head>
<body> 
    
    <header>
        <b style='font-size:14px;'>  {{$payroll->company}}</b><br>
        <div class='noBorder'  style='vertical-align:top;padding-right:30px;'>
            <br>
                <b style='font-size:12px;'>BILLING</b><Br>
                Store : {{$payroll->store}}<br>
                Payroll Period of : {{$payroll->payroll_from}} to {{$payroll->payroll_to}}<br>
                Date and time Printed : {{date('M. d, Y h:i a')}}
            </div>
    </header>
    <div class="footer fixed-section">
        <div class="center">
            <span class="page-number">Page <script type="text/php">echo $PAGE_NUM</script> </span>
        </div>
    </div>
    <table style='font-size:9px;' width='100%' border='1'>
        <thead>
            <tr>
                <th>#</th>
                <th>Emp No.</th>
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
                <th>13th Month</th>
                <th>Sub Total</th>
                <th>SSS Contribution</th>
                <th>NHIP Contribution</th>
                <th>HDMF Contribution</th>
                <th>Total Contribution</th>
                <th>Gross Billing</th>
              </tr>
        </thead>
        <tbody>
            @php
                $c = 1;
                $gross_total = 0;
                $months_total = 0;
            @endphp
            @foreach($payroll->informations as $key => $pay)
            @php
                $net = round($pay->basic_pay+$pay->amount_overtime+$pay->amount_special_holiday+$pay->amount_legal_holiday+$pay->amount_night_diff-$pay->hours_tardy_basic,2);
                $month = round(($pay->basic_pay-$pay->hours_tardy_basic)/12,2);
                $gross = $net+$month+$pay->sss_contribution+$pay->nhip_contribution+$pay->hdmf_contribution;
                $gross_total = $gross_total + round($gross,2);
                $months_total=$months_total+$month;
            @endphp
                <tr>
                    <td class='text-right'>{{$c++}}</td>
                    <td class='text-right'>{{$pay->employee_id}}</td>
                    <td>{{$pay->employee_name}}</td>
                    <td class='text-right'>{{number_format($pay->daily_rate,2)}}</td>
                    <td class='text-right'>{{number_format($pay->hour_rate,2)}}</td>
                    <td class='text-right'>{{number_format($pay->days_work,2)}}</td>
                    <td class='text-right'>{{number_format($pay->hours_work,2)}}</td>
                    <td class='text-right'>{{number_format($pay->basic_pay,2)}}</td>
                    <td class='text-right'>{{number_format($pay->hours_tardy,2)}}</td>
                    <td class='text-right'>{{number_format($pay->hours_tardy_basic,2)}}</td>
                    <td class='text-right'>{{number_format($pay->overtime,2)}}</td>
                    <td class='text-right'>{{number_format($pay->amount_overtime,2)}}</td>
                    <td class='text-right'>{{number_format($pay->special_holiday,2)}}</td>
                    <td class='text-right'>{{number_format($pay->amount_special_holiday,2)}}</td>
                    <td class='text-right'>{{number_format($pay->legal_holiday,2)}}</td>
                    <td class='text-right'>{{number_format($pay->amount_legal_holiday,2)}}</td>
                    <td class='text-right'>{{number_format($pay->night_diff,2)}}</td>
                    <td class='text-right'>{{number_format($pay->amount_night_diff,2)}}</td>
                    <td class='text-right'>{{number_format($month,2)}}</td>
                    <td class='text-right'>{{number_format(0,2)}}</td>
                    <td class='text-right'>{{number_format($pay->sss_contribution,2)}}</td>
                    <td class='text-right'>{{number_format($pay->nhip_contribution,2)}}</td>
                    <td class='text-right'>{{number_format($pay->hdmf_contribution,2)}}</td>
                    <td class='text-right'>{{number_format($pay->sss_contribution+$pay->nhip_contribution+$pay->hdmf_contribution,2)}}</td>
                    <td class='text-right'>{{number_format($gross,2)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan=25>
                </th>
            </tr>
            <tr>
                <th class='text-right' colspan=3>
                    GRAND TOTALS <br>
                    <small >{{count($payroll->informations)}} Records</small> 
                </th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('daily_rate')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('hour_rate')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('days_work')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('hours_work')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('basic_pay')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('hours_tardy')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('hours_tardy_basic')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('overtime')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('amount_overtime')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('special_holiday')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('amount_special_holiday')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('legal_holiday')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('amount_legal_holiday')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('night_diff')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('amount_night_diff')),2)}}</th>
                <th class='text-right'>{{number_format($months_total,2)}}</th>
                <th class='text-right'>{{number_format(0,2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('sss_contribution')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('nhip_contribution')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('hdmf_contribution')),2)}}</th>
                <th class='text-right'>{{number_format((($payroll->informations)->sum('sss_contribution'))+(($payroll->informations)->sum('nhip_contribution'))+(($payroll->informations)->sum('hdmf_contribution')),2)}}</th>
                <th class='text-right'>{{number_format($gross_total,2)}}</th>
            </tr>
            <tr>
                <th class='text-right' colspan=25>
                </th>
              
            </tr>
            <tr>
                <th class='text-right' colspan=3>
                    Gross Billing  

                </th>
                <th class='text-right' colspan=2>
                    {{number_format($gross_total,2)}}
                </th>
                <th class='text-right'  colspan=21>

                </th>
            </tr>
            <tr>
                <th class='text-right' colspan=3>
                    Administrative Cost  

                </th>
                <th class='text-right' colspan=2>
                    {{number_format($gross_total*.1,2)}}
                </th>
                <th class='text-right'  colspan=21>

                </th>
            </tr>
            <tr>
                <th class='text-right' colspan=3>
                    SUM BILLING 

                </th>
                <th class='text-right' colspan=2>
                    {{number_format($gross_total+$gross_total*.1,2)}}
                </th>
                <th class='text-right'  colspan=21>

                </th>
            </tr>
            <tr>
                <th class='text-right' colspan=3>
                    VAT (12%)

                </th>
                <th class='text-right' colspan=2>
                    {{number_format(($gross_total+$gross_total*.1)*.12,2)}}
                </th>
                <th class='text-right'  colspan=21>

                </th>
            </tr>
            <tr>
                <th class='text-right' colspan=3>
                    Less: 2% Withholding Tax

                </th>
                <th class='text-right' colspan=2>
                    {{number_format(($gross_total+$gross_total*.1)*.02,2)}}
                </th>
                <th class='text-right'  colspan=21>

                </th>
            </tr>
            <tr>
                <th class='text-right' colspan=3>
                    <b>NET BILLING</b>
                </th>
                <th class='text-right' colspan=2>
                    {{number_format($gross_total+$gross_total*.1+($gross_total+$gross_total*.1)*.12-($gross_total+$gross_total*.1)*.02,2)}}
                </th>
                <th class='text-right'  colspan=21>

                </th>
            </tr>
        </tfoot>
    </table>
    <br>
    <table  width='100%' style='font-size:12px;margin-top:15px;'>
        <tr class='noBorder' align='center'>
        <th class='noBorder'>
            @php
                $length = strlen(auth()->user()->name);
                $approved_by = str_repeat('_',$length+8);
            @endphp
            <span><u>____{{auth()->user()->name}}____</u></span><br>
            <span>Prepared By:</span>
        </th>
    </tr>
    </table>

</body>
</html>