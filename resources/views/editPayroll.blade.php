@extends('layouts.header_admin')
@section('css')
<link href="{{ asset('admin/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet" as="style" onload="this.onload=null;this.rel='stylesheet'" defer>
<link href="{{ asset('admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" as="style" onload="this.onload=null;this.rel='stylesheet'" defer>
@endsection
@section('content')
<div class='row'>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                Generated By : {{ optional($payroll->user)->name ?? 'N/A' }} <br>
                Generated Date : {{ date('M. d, Y',strtotime($payroll->created_at)) }} <br>
                Store : {{ $payroll->store }} <br>
                Payroll Period : {{ date('M. d, Y',strtotime($payroll->payroll_from)) }} -  {{ date('M. d, Y',strtotime($payroll->payroll_to)) }}<br>
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
                                <th>Hours Work (hr)</th>
                                <th>Overtime (hr)</th>
                                <th>Special holiday (day)</th>
                                <th>Legal holiday (day)</th>
                                <th>Days Work (day)</th>
                                <th>Hours Tardy (hr)</th>
                                <th>Night Diff (hr)</th>
                                <th>Basic Pay</th>
                                <th>Amount Overtime</th>
                                <th>Amount Special holiday</th>
                                <th>Amount Legal Holiday</th>
                                <th>Amount Night Diff</th>
                                <th>Hours Tardy Basic</th>
                                <th>Other Income Non Taxable</th>
                                <th>Other Income Remarks</th>
                                <th>Gross Pay</th>
                                <th>SSS Contribution</th>
                                <th>NHIP Contribution</th>
                                <th>HDMF Contribution</th>
                                <th>Other Deductions</th>
                                <th>Other Deductions Remarks</th>
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
                                            {{-- <li><a title='Additional Gross Income' href="#AdditionalGrossIncome{{$payrollInfo->id}}" data-toggle="modal"  >Additional Gross Allowance</a></li> --}}
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
                                <td>{{number_format($payrollInfo->hours_work,2)}}</td>
                                <td>{{number_format($payrollInfo->overtime,2)}}</td>
                                <td>{{number_format($payrollInfo->special_holiday,2)}}</td>
                                <td>{{number_format($payrollInfo->legal_holiday,2)}}</td>
                                <td>{{number_format($payrollInfo->days_work,2)}}</td>
                                <td>{{number_format($payrollInfo->hours_tardy,2)}}</td>
                                <td>{{number_format($payrollInfo->night_diff,2)}}</td>
                                <td>{{number_format($payrollInfo->basic_pay,2)}}</td>
                                <td>{{number_format($payrollInfo->amount_overtime,2)}}</td>
                                <td>{{number_format($payrollInfo->amount_special_holiday,2)}}</td>
                                <td>{{number_format($payrollInfo->amount_legal_holiday,2)}}</td>
                                <td>{{number_format($payrollInfo->amount_night_diff,2)}}</td>
                                <td>{{number_format($payrollInfo->hours_tardy_basic,2)}}</td>
                                @if($payroll->status == null)
                                <td contenteditable="true" onkeydown="add_other_payments(event,'{{$payrollInfo->gross_pay}}','{{$payrollInfo->employee_id}}','{{$payrollInfo->payroll_id}}')" id="otherPayments">{{number_format($payrollInfo->other_income_non_taxable,2)}}</td>
                                <td contenteditable="true" onkeydown="add_additional_remarks(event, '{{$payrollInfo->employee_id}}','{{$payrollInfo->payroll_id}}')">{{$payrollInfo->income_remarks}}</td>
                                <td id="{{$payrollInfo->employee_id}}" data="{{$payrollInfo->gross_pay}}">{{number_format($payrollInfo->gross_pay,2)}}</td>
                                @else
                                <td>{{number_format($payrollInfo->other_income_non_taxable,2)}}</td>
                                <td>{{$payrollInfo->income_remarks}}</td>
                                <td>{{number_format($payrollInfo->gross_pay,2)}}</td>
                                @endif
                                <td>{{number_format($payrollInfo->sss_contribution,2)}}</td>
                                <td>{{number_format($payrollInfo->nhip_contribution,2)}}</td>
                                <td>{{number_format($payrollInfo->hdmf_contribution,2)}}</td>

                                @if($payroll->status == null)
                                <td contenteditable="true" onkeydown="add_other_deduction(event,'{{$payrollInfo->net_pay}}','{{$payrollInfo->total_deductions}}', '{{$payrollInfo->employee_id}}','{{$payrollInfo->payroll_id}}')" id="otherDeductions-{{$payrollInfo->employee_id}}">{{number_format($payrollInfo->other_deductions,2)}}</td>
                                <td contenteditable="true" onkeydown="add_deduction_remarks(event, '{{$payrollInfo->employee_id}}','{{$payrollInfo->payroll_id}}')">{{$payrollInfo->deduction_remarks}}</td>
                                <td id="deductions-{{$payrollInfo->employee_id}}" data="{{$payrollInfo->gross_pay}}">{{number_format($payrollInfo->total_deductions,2)}}</td>
                                @else
                                <td>{{number_format($payrollInfo->other_deductions,2)}}</td>
                                <td>{{$payrollInfo->deduction_remarks}}</td>
                                <td id="deductions-{{$payrollInfo->employee_id}}">{{number_format($payrollInfo->total_deductions,2)}}</td>
                                @endif

                                <td id="netpay-{{$payrollInfo->employee_id}}">{{number_format($payrollInfo->net_pay,2)}}</td>
                            </tr>
                            @include('additional_income')
                            @include('gross_allowances')
                            @include('deduction')
                            @include('edit_payroll_data')
                            @include('edit_government')
                            @include('transfer_employee')
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='3' class='text-right'>Total</td>
                                <td>{{number_format(($payroll->informations)->sum('daily_rate'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('hour_rate'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('hours_work'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('overtime'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('special_holiday'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('legal_holiday'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('days_work'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('hours_tardy'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('night_diff'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('basic_pay'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('amount_overtime'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('amount_special_holiday'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('amount_legal_holiday'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('amount_night_diff'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('hours_tardy_basic'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('other_income_non_taxable'),2)}}</td>
                                <td>0.00</td>
                                <td>{{number_format(($payroll->informations)->sum('gross_pay'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('sss_contribution'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('nhip_contribution'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('hdmf_contribution'),2)}}</td>
                                <td>{{number_format(($payroll->informations)->sum('other_deductions'),2)}}</td>
                                <td>0.00</td>
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
        function add_income_gross(id)
        {
            show();
            var lastItemID = $('#allowance-gross-'+id).children().last().attr('id');
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

            $("#allowance-gross-"+id).append(item);
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
        function remove_allowance(id,finalId)
        {
            show();
            $("#allowance-gross-"+id+"-"+finalId).remove();
            unshow();
        }
        function remove_deduction(id,finalId)
        {
            show();
            $("#deduction-"+id+"-"+finalId).remove();
            unshow();
        }
        async function add_other_payments(e, gross, emp, id)
        {
            if (event.key === 'Tab') {
                const formatter = new Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                const result = (parseFloat(gross) + parseFloat(e.target.innerText)).toFixed(2);
                const formattedNumber = formatter.format(result);
                const deduction = $(`#deductions-${emp}`).text();
                $(`#${emp}`).text(formattedNumber);
                const net = (Number(result) - parseFloat(deduction)).toFixed(2);
                const formattedNet = formatter.format(net);
                $(`#netpay-${emp}`).text(formattedNet);
                const body = {
                    "emp_id": emp,
                    "income": Number(e.target.innerText),
                    "remarks": ""
                }
                const response = await fetch(`http://127.0.0.1:8000/api/additional/${id}`, {
                  method: 'post',
                  body: JSON.stringify(body),
                  headers: {'Content-Type': 'application/json'}
                });
                if (response.status !== 200) {
                  await logError(err, "Reports", req.body, id, "POST");
                  return res.status(400).json({
                    success: false,
                    msg: "Connection to payroll error",
                  });
                }
            }
        }
        async function add_additional_remarks(e, emp, id)
        {
            if (event.key === 'Tab') {
                const body = {
                    "emp_id": emp,
                    "remarks": e.target.innerText
                }
                const response = await fetch(`https://payroll-live.7star.com.ph/api/additional-remarks/${id}`, {
                  method: 'post',
                  body: JSON.stringify(body),
                  headers: {'Content-Type': 'application/json'}
                });
                if (response.status !== 200) {
                  await logError(err, "Reports", req.body, id, "POST");
                  return res.status(400).json({
                    success: false,
                    msg: "Connection to payroll error",
                  });
                }
            }
        }
        async function add_other_deduction(e, gross, deduction, emp, id)
        {
            if (event.key === 'Tab') {
                const formatter = new Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                const result = (parseFloat(deduction) + parseFloat(e.target.innerText)).toFixed(2);
                const formattedNumber = formatter.format(result);
                $(`#deductions-${emp}`).text(formattedNumber);
                const grossPay = $(`#${emp}`).text();
                const net = (parseFloat(grossPay.replace(/,/g, '')) - parseFloat(result)).toFixed(2);
                const formattedNet = formatter.format(net);
                console.log(parseFloat(grossPay.replace(/,/g, '')) + " " + parseFloat(result))
                $(`#netpay-${emp}`).text(formattedNet);
                /*const body = {
                    "emp_id": emp,
                    "deduction": Number(e.target.innerText),
                }
                const response = await fetch(`https://payroll-live.7star.com.ph/api/deduction/${id}`, {
                  method: 'post',
                  body: JSON.stringify(body),
                  headers: {'Content-Type': 'application/json'}
                });
                if (response.status !== 200) {
                  await logError(err, "Reports", req.body, id, "POST");
                  return res.status(400).json({
                    success: false,
                    msg: "Connection to payroll error",
                  });
                }*/
            }
        }
        async function add_deduction_remarks(e, emp, id)
        {
            if (event.key === 'Tab') {
                const body = {
                    "emp_id": emp,
                    "remarks": e.target.innerText
                }
                const response = await fetch(`http://127.0.0.1:8000/api/deduction-remarks/${id}`, {
                  method: 'post',
                  body: JSON.stringify(body),
                  headers: {'Content-Type': 'application/json'}
                });
                if (response.status !== 200) {
                  await logError(err, "Reports", req.body, id, "POST");
                  return res.status(400).json({
                    success: false,
                    msg: "Connection to payroll error",
                  });
                }
            }
        }

</script>
@endsection

