@extends('layouts.header_admin')
@section('css')
<link href="{{ asset('admin/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
<link href="{{ asset('admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
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
                                $height = 28;
                            @endphp
                            @foreach($payroll->informations as $payrollInfo)
                            
                          
                            <tr>
                                <td>
                                    @if($payroll->status == null)
                                    <div class="btn-group">
                                        <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><i class="fa fa-ellipsis-v"></i> </button>
                                        <ul class="dropdown-menu">
                                            <li><a title='Edit Payroll' href="#editPayroll{{$payrollInfo->id}}" data-toggle="modal" >Edit</a></li>
                                            <li><a title='Transfer Payroll' href="#transfer{{$payrollInfo->id}}" data-toggle="modal"  >Transfer</a></li>
                                            <li><a title='Additional Income' href="#AdditionalIncome{{$payrollInfo->id}}" data-toggle="modal"  >Additional Income</a></li>
                                            <li><a title='Additional Deduction' href="#DeductionIncome{{$payrollInfo->id}}" data-toggle="modal"  >Additional Deduction</a></li>
                                            <li><a title='Edit Government Benefits' href="#editgov{{$payrollInfo->id}}" data-toggle="modal" >Edit Government</a></li>
                                            <li class="divider"></li>
                                            <li><a title='Delete' class='remove-payroll' id='{{$payrollInfo->id}}' data-toggle="modal" title='Delete'  >Remove Employee</a></li>
                                        </ul>
                                    </div>
                                    @endif
                                </td>
                                <td>{{$c++}}</td>
                                <td>{{strtoupper($payrollInfo->employee_name)}}</td>
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
                                <td>{{number_format($payrollInfo->other_deductions,2)}}</td>
                                <td>{{number_format($payrollInfo->total_deductions,2)}}</td>
                                <td>{{number_format($payrollInfo->net_pay,2)}}</td>
                            </tr>
                            
                            @endforeach
                         
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='3' class='text-right'>Total</td>
                                <td>{{number_format(($payroll->informations)->sum('daily_rate'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('hour_rate'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('days_work'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('hours_work'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('basic_pay'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('hours_tardy'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('hours_tardy_basic'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('overtime'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('amount_overtime'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('special_holiday'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('amount_special_holiday'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('legal_holiday'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('amount_legal_holiday'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('night_diff'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('amount_night_diff'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('gross_pay'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('other_income_non_taxable'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('sss_contribution'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('nhip_contribution'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('hdmf_contribution'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('other_deductions'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('total_deductions'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('net_pay'),2)}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@foreach($payroll->informations as $payrollInfo)
@include('additional_income')
@include('deduction')
@include('edit_payroll_data')
@include('edit_government')
@include('transfer_employee')
@endforeach
@endsection
@section('js')
<script src="{{ asset('admin/js/plugins/chosen/chosen.jquery.js')}}"></script>
<script src="{{ asset('admin/js/inspinia.js')}}"></script>
<script src="{{ asset('admin/js/plugins/pace/pace.min.js')}}"></script>
<script src="{{ asset('admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $(document).ready(function(){
      $('.chosen-select').chosen({width: "100%"});
    });

    $('.remove-payroll').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this payroll",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("remove-payroll")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deleted!", "Your record been deleted.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                swal("Deleted!", "Your record been deleted.", "success");
                location.reload();
                });
            });
        });
        function add_income(id)
        {
            show();
            var lastItemID = $('#allowance-'+id).children().last().attr('id');
            if(lastItemID){
                var last_id = lastItemID.split("-");
                finalLastId = parseInt(last_id[2]) + 1;
            }else{
                finalLastId = 0;
            }
            var item = "<div class='row ' id='allowance-"+id+"-"+finalLastId+"'>";
            item += "<div class='col-md-5 border form-group'><input name='allowance_name[]' type='text' min='0' placeholder='Meal Allowance' class='form-control form-control-sm' required>";
            item += "</div>";
            item += "<div class='col-md-5 border form-group'><input name='allowance_amount[]' type='number' min='0' placeholder='1.00' class='form-control form-control-sm' required>";
            item += "</div>";
            item += "<div class='col-md-2 border form-group'><button class='btn btn-danger btn-circle' onclick='remove_allowance("+id+","+finalLastId+")' type='button'><i class='fa fa-minus'></i></button>";
            item += "</div>";
            item += "</div>";
          
            $("#allowance-"+id).append(item);
            unshow();
            
        }
        function add_deduction(id)
        {
            show();
            var lastItemID = $('#deduction-'+id).children().last().attr('id');
            if(lastItemID){
                var last_id = lastItemID.split("-");
                finalLastId = parseInt(last_id[2]) + 1;
            }else{
                finalLastId = 0;
            }
            var item = "<div class='row ' id='deduction-"+id+"-"+finalLastId+"'>";
            item += "<div class='col-md-5 border form-group'><input name='deduction_name[]' type='text' min='0' placeholder='Company Loan' class='form-control form-control-sm' required>";
            item += "</div>";
            item += "<div class='col-md-5 border form-group'><input name='deduction_amount[]' type='number' min='0' placeholder='1.00' class='form-control form-control-sm' required>";
            item += "</div>";
            item += "<div class='col-md-2 border form-group'><button class='btn btn-danger btn-circle' onclick='remove_deduction("+id+","+finalLastId+")' type='button'><i class='fa fa-minus'></i></button>";
            item += "</div>";
            item += "</div>";
          
            $("#deduction-"+id).append(item);
            unshow();
            
        }
        function remove_allowance(id,finalId)
        {
            show();
            $("#allowance-"+id+"-"+finalId).remove();
            unshow();
        }
        function remove_deduction(id,finalId)
        {
            show();
            $("#deduction-"+id+"-"+finalId).remove();
            unshow();
        }

</script>
@endsection

