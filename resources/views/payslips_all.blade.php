@php
ini_set("memory_limit", "-1");
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" type="image/png" href="{{ asset('/images/icon.png')}}"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">   
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
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
        }
        .page-break {
            page-break-after: always;
        }
        header {
            position: fixed;
            top: -5px;
            left: 0px;
            right: 0px;
            color: black;
            text-align: left;
            background-color:#BEBEBE;
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
        table{
            table-layout: fixed;
            width: 390px;
        }
    </style>
    
</head>
<body> 
    @foreach($payrollsInfo as $payroll)
    <header>
        <div class="row"  style='vertical-align:top;padding-right:30px;width:100%;text-align:center;background-color:#95B3D7;'>
           <div class='col-md-12 text-center'>    
                <b style='font-size:14px;'>
                    @php
                        $store = strtolower($payroll->payroll->store);
                        $company = "7-STAR MANPOWER";
                     
                        if(str_contains($store,"syzygy"))
                        {
                            $company = "SYZYGY STAFFING";
                        }

                        if($company == "7-STAR MANPOWER")
                        {
                            $store_name = explode("-",$payroll->payroll->store);
                            $store_name_data = $store_name[count($store_name)-1];
                        }
                        else {
                            
                            $store_name = explode("_",$payroll->payroll->store);

                            $store_name_data = $store_name[count($store_name)-1];
                        }

                        
                    @endphp
                    {{$company}}<br> SERVICES OF THE PHILIPPINES</b> <br>
                {{-- <span style='font-size:12px;'>P A Y S L I P</span> --}}
           </div>
        </div>
       
    </header>
    <div class="row" >
        <div class='col-md-6 text-left'>    
         EMPLOYEE : {{strtoupper($payroll->employee_name)}}
        </div>
        <div class='col-md-6 text-left'>   
             
        ASSIGNED AT :    {{strtoupper($store_name_data)}}
        </div>
        <div class='col-md-3 text-left'>    
             DATE COVERED : {{date('M d, Y',strtotime($payroll->payroll->payroll_from))}} - {{date('M d, Y',strtotime($payroll->payroll->payroll_to))}}
        </div>
     </div>
    <table style='font-size:9px;' width='100%' border='1' >
        <thead>
            <tr >
                <th style='50%' style='width:50%' colspan='2' class='text-center'>
                    EARNINGS
                </th>
                <th style='50%'  style='width:50%' colspan='2' class='text-center'>
                    DEDUCTIONS
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td  class='text-left'>
                    REGULAR HOURS
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->hours_work,2)}}
                </td>
                <td   class='text-left'>
                    SSS
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->sss_contribution,2)}}
                </td>
            </tr>
            <tr>
                <td  class='text-left'>
                    TOTAL DAYS
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->days_work,2)}}
                </td>
                <td   class='text-left'>
                    PHILHEALTH
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->nhip_contribution,2)}}
                </td>
            </tr>
            <tr>
                <td  class='text-left'>
                    REGULAR PAY
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->basic_pay,2)}}
                </td>
                <td   class='text-left'>
                    PAGIBIG
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->hdmf_contribution,2)}}
                </td>
            </tr>
            <tr>
                <td  class='text-left'>
                    OVERTIME PAY
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->amount_overtime,2)}}
                </td>
                <td   class='text-left'>
                    OTHER DEDUCTIONS
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->other_deductions,2)}}
                </td>
            </tr>
            <tr>
                <td  class='text-left'>
                    LEGAL HOLIDAY
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->amount_legal_holiday,2)}}
                </td>
                <td   class='text-left'>
                    
                </td>
                <td  class='text-right'>
                    
                </td>
            </tr>
            <tr>
                <td  class='text-left'>
                    SPECIAL HOLIDAY
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->amount_special_holiday,2)}}
                </td>
                <td   class='text-left'>
                    
                </td>
                <td  class='text-right'>
                    
                </td>
            </tr>
            @foreach($payroll->payroll_allowances as $key => $allow)
            <tr>
                <td  class='text-left'>
                   {{strtoupper($allow->name)}}
                </td>
                <td  class='text-right'>
                  {{number_format($allow->amount,2)}}
                </td>
                <td   class='text-left'>
                   
                </td>
                <td  class='text-right'>
                    
                </td>
            </tr>
            @endforeach
            <tr>
                <td  class='text-left' colspan='4'>
                  
                </td>
            </tr>
            <tr>
                <td  class='text-left'>
                   GROSS PAY
                </td>
                <td  class='text-right'>
                  {{number_format($payroll->gross_pay,2)}}
                </td>
                <td   class='text-left'>
                    TOTAL DEDUCTIONS
                </td>
                <td  class='text-right'>
                   {{number_format($payroll->total_deductions,2)}}
                </td>
            </tr>
            <tr>
                <td  class='text-center' colspan='2'>
                 <b>SUMMARY </b> 
                </td>
                <td   class='text-center'  colspan='2'>
                    <b> ACKNOWLEDGEMENT</b> 
                </td>
            </tr>
            <tr>
                <td  class='text-left'>
                 GROSS PAY
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->gross_pay,2)}}
                </td>
                <td   class='text-center' rowspan='3'  colspan='2'>
                  <span style='font-size : 8px;'> I HAVE READ AND UNDERSTOOD THE ABOVE COMPUTATIONS OF MY SALARY.</span> <br><br>
                  _______________________<br>
                  Signature with Date
                </td>
            </tr>
            <tr>
                <td  class='text-left'>
                 TOTAL DEDUCTIONS
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->total_deductions,2)}}
                </td>
            </tr>
            <tr>
                <td  class='text-left'>
                <b>NET PAY</b>
                </td>
                <td  class='text-right'>
                    {{number_format($payroll->net_pay,2)}}
                </td>
            </tr>
          
        </tbody>
    </table>
    <div class="page-break"></div>
    @endforeach
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>