@extends('layouts.header_admin')
@section('css')
<link href="{{ asset('admin/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class='row'>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
               Generated By : {{$payroll->user->name}} <br>
               Generated Date : {{date('M. d, Y',strtotime($payroll->created_at))}} <br>
               Store : {{$payroll->store}} <br>
               Payroll Period : {{date('M. d, Y',strtotime($payroll->payroll_from))}} -  {{date('M. d, Y',strtotime($payroll->payroll_to))}}<br>
            </div>
        </div>
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th></th>
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
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $c = 1;
                            @endphp
                            @foreach($payroll->informations as $payrollInfo)
                            <tr>
                                <td>
                                    <a title='Edit Payroll' href="#editPayroll{{$payrollInfo->id}}" data-toggle="modal" title='EDIT'  ><button type="button"  class="btn btn-success btn-icon">
                                        <i class="fa fa-edit"></i>
                                        </button>
                                    </a>
                                </td>
                                <td>{{$c++}}</td>
                                <td>{{$payrollInfo->employee_name}}</td>
                                <td>{{number_format($payrollInfo->daily_rate,2)}}</td>
                                <td>{{number_format($payrollInfo->hour_rate,2)}}</td>
                                <td>{{number_format($payrollInfo->days_work,2)}}</td>
                                <td>{{number_format($payrollInfo->hours_work,2)}}</td>
                                <td>{{number_format($payrollInfo->basic_pay,2)}}</td>
                                <td>{{number_format($payrollInfo->hours_tardy,2)}}</td>
                                <td>{{number_format($payrollInfo->hours_tardy_basic,2)}}</td>
                                <td>{{number_format($payrollInfo->overtime,2)}}</td>
                                <td>{{number_format($payrollInfo->amount_overtime,2)}}</td>
                                <td>{{number_format($payrollInfo->special_holiday,2)}}</td>
                                <td>{{number_format($payrollInfo->amount_special_holiday,2)}}</td>
                                <td>{{number_format($payrollInfo->legal_holiday,2)}}</td>
                                <td>{{number_format($payrollInfo->amount_legal_holiday,2)}}</td>
                                <td>{{number_format($payrollInfo->night_diff,2)}}</td>
                                <td>{{number_format($payrollInfo->amount_night_diff,2)}}</td>
                                <td>{{number_format($payrollInfo->gross_pay,2)}}</td>
                                <td>{{number_format($payrollInfo->other_income_non_taxable,2)}}</td>
                                <td>{{number_format($payrollInfo->sss_contribution,2)}}</td>
                                <td>{{number_format($payrollInfo->nhip_contribution,2)}}</td>
                                <td>{{number_format($payrollInfo->hdmf_contribution,2)}}</td>
                                <td>{{number_format($payrollInfo->other_deduction,2)}}</td>
                                <td>{{number_format($payrollInfo->total_deductions,2)}}</td>
                                <td>{{number_format($payrollInfo->net_pay,2)}}</td>
                            </tr>
                            @include('edit_payroll_data')
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
<script src="{{ asset('admin/js/plugins/chosen/chosen.jquery.js')}}"></script>
<script src="{{ asset('admin/js/inspinia.js')}}"></script>
<script src="{{ asset('admin/js/plugins/pace/pace.min.js')}}"></script>
@endsection

