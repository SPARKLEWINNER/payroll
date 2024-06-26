@extends('layouts.header_admin')
@section('css')
<link href="{{ asset('admin/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet" defer>
<link href="{{ asset('admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" defer>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-success pull-right">as of Today</span>
                <h5>Total Generated Payroll</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{$payrolls->count()}}</h1>
                <small>&nbsp;</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-warning pull-right">@if($payrolls->first() != null)<small>{{date('M. d, Y', strtotime(($payrolls->first())->payroll_from))}} - {{date('M. d, Y', strtotime(($payrolls->first())->payroll_to))}}</small>@endif</span>
                <h5>Generated this cutoff</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">@if($payrolls->first() != null){{count($payrolls->where('payroll_to', ($payrolls->first())->payroll_to)->where('status', ""))}}@endif</h1>
                <small>&nbsp;</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-primary pull-right">@if($payrolls->first() != null)<small>{{date('M. d, Y', strtotime(($payrolls->first())->payroll_from))}} - {{date('M. d, Y', strtotime(($payrolls->first())->payroll_to))}}</small>@endif</span>
                <h5>Active Stores</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{count($stores)}}</h1>
                <small>&nbsp;</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-danger pull-right">@if($payrolls->first() != null)<small>{{date('M. d, Y', strtotime(($payrolls->first())->payroll_from))}} - {{date('M. d, Y', strtotime(($payrolls->first())->payroll_to))}}</small>@endif</span>
                <h5>Posted</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">@if($payrolls->first() != null){{count($payrolls->where('payroll_to', ($payrolls->first())->payroll_to)->where('status', '!=', ''))}}@endif</h1>
                <small>&nbsp;</small>
            </div>
        </div>
    </div>
</div>
<div class='row'>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="table-responsive">
                    <div class="d-flex justify-content-end mb-2">
                        <button type="button" class="btn btn-primary" id="updateRecordsButton">Update Records</button>
                    </div>
                    <table class="table table-striped table-bordered table-hover home-payroll">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Company</th>
                                <th>Store</th>
                                <th>Generated By</th>
                                <th>Cut Off</th>
                                <th>Employee Count</th>
                                <th>Total Basic Pay</th>
                                <th>Contributions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $payroll)
                                <tr>
                                    <td>
                                        @if($payroll->status == "")
                                            <a title='Edit Payroll' href='{{ url("edit-payroll/" . $payroll->id) }}'>
                                                <button type="button" class="btn btn-success btn-icon btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </a>
                                            <a title='Delete Payroll' class='delete-payroll' id='{{ $payroll->id }}'>
                                                <button type="button" class="btn btn-danger btn-icon btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </a>
                                            <a title='Save Payroll' class='save-payroll' id='{{ $payroll->id }}'>
                                                <button type="button" class="btn btn-warning btn-icon btn-sm">
                                                    <i class="fa fa-save"></i>
                                                </button>
                                            </a>
                                        @else
                                            @if($payroll->display == 0)
                                                <a title='Display Payslip' href='{{ url("display/" . $payroll->id) }}'>
                                                    <button type="button" class="btn btn-success btn-icon btn-sm">
                                                        <i class="fa fa-exchange"></i>
                                                    </button>
                                                </a>
                                            @else
                                                <a title='Remove Payslip' href='{{ url("display/" . $payroll->id) }}'>
                                                    <button type="button" class="btn btn-success btn-icon btn-sm">
                                                        <i class="fa fa-minus-square"></i>
                                                    </button>
                                                </a>
                                            @endif
                                            <a title='Print Payroll' href='{{ url("payroll/" . $payroll->id) }}' target="_blank">
                                                <button type="button" class="btn btn-danger btn-icon btn-sm">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </button>
                                            </a>
                                            <a title='Print Billing' href='{{ url("billing/" . $payroll->id) }}' target="_blank">
                                                <button type="button" class="btn btn-info btn-icon btn-sm">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </button>
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $payroll->company ?? 'N/A' }}</td>
                                    <td>{{ $payroll->store ?? 'N/A' }}</td>
                                    <td>{{ optional($payroll->user)->name ?? 'N/A' }}</td>
                                    <td>{{ date('M d, Y', strtotime($payroll->payroll_from)) }} - {{ date('M d, Y', strtotime($payroll->payroll_to)) }}</td>
                                    <td>{{ count($payroll->informations) }}</td>
                                    <td>{{ number_format((($payroll->informations)->sum('basic_pay')), 2) }}</td>
                                    <td>
                                        <small>
                                            SSS : {{ number_format((($payroll->informations)->sum('sss_contribution')), 2) }} <br>
                                            HDMF : {{ number_format((($payroll->informations)->sum('hdmf_contribution')), 2) }} <br>
                                            NHIP : {{ number_format((($payroll->informations)->sum('nhip_contribution')), 2) }}
                                        </small>
                                    </td>
                                </tr>
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
<script src="{{ asset('admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $(document).ready(function() {
        // Log the payroll data
        console.log('Payrolls:', @json($payrolls));
        
        // Existing code for delete and save actions
        $('.delete-payroll').click(function () {
            var id = this.id;
            console.log('Delete payroll ID:', id);
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
                    url:  '{{url("delete-payroll")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log('Delete response:', data);
                    swal("Deleted!", "Your record has been deleted.", "success");
                    location.reload();
                }).fail(function(data) {
                    console.log('Delete failed response:', data);
                    swal("Deleted!", "Your record has been deleted.", "success");
                    location.reload();
                });
            });
        });

        $('.save-payroll').click(function () {
            var id = this.id;
            console.log('Save payroll ID:', id);
            swal({
                title: "Are you sure to save this payroll?",
                text: "After saving, you cannot edit this payroll anymore",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, save it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("save-payroll")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log('Save response:', data);
                    swal("Saved!", "Successfully Saved.", "success");
                    location.reload();
                }).fail(function(data) {
                    console.log('Save failed response:', data);
                    swal("Saved!", "Successfully Saved.", "success");
                    location.reload();
                });
            });
        });

        $('#updateRecordsButton').click(function () {
            var settings = {
                "url": "https://sparkle-time-keep.herokuapp.com/api/list/breaklistapproved",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "store": "Star Concorde Group"
                }),
            };

            $.ajax(settings).done(function (response) {
                console.log('Fetched data:', response);
                if (response.success) {
                    var newPayrolls = response.data;

                    // Function to fetch rates for an employee
                    function fetchRates(employeeId) {
                        return $.ajax({
                            url: `/rates/${employeeId}`,
                            method: 'GET',
                            contentType: 'application/json'
                        });
                    }

                    // Iterate over each payroll to fetch rates and save payroll
                    var payrollSavePromises = [];

                    newPayrolls.forEach(function (payroll) {
                        // Format the date fields
                        var dateFrom = new Date(payroll.datefrom).toISOString().split('T')[0];
                        var dateTo = new Date(payroll.dateto).toISOString().split('T')[0];

                        var detailsPromises = payroll.details.map(detail => {
                            return fetchRates(detail.employeeid).then(rateResponse => {
                                if (rateResponse.status === 'success') {
                                    var rate = rateResponse.data;
                                    var hourlyRate = rate.daily / 8;
                                    return {
                                        employeeid: detail.employeeid,
                                        employeename: detail.employeename,
                                        rate: rate.daily,
                                        daily_rate: rate.daily,
                                        hour_rate: hourlyRate,
                                        days_work: detail.dayswork,
                                        hours_work: detail.hourswork,
                                        basic_pay: rate.daily * detail.dayswork,
                                        hours_tardy: detail.hourstardy,
                                        tardy_amount: (rate.daily / 8 / 60) * detail.hourstardy,
                                        overtime: detail.overtime,
                                        overtime_amount: rate.overtime * detail.overtime,
                                        special_holiday: detail.specialholiday,
                                        special_holiday_amount: rate.specialholiday * detail.specialholiday,
                                        legal_holiday: detail.legalholiday,
                                        legal_holiday_amount: rate.holiday * detail.legalholiday,
                                        night_diff: detail.nightdiff,
                                        nightdiff_amount: rate.nightshift * detail.nightdiff,
                                        gross_pay: rate.daily * detail.dayswork - (rate.daily / 8 / 60) * detail.hourstardy + rate.overtime * detail.overtime + rate.specialholiday * detail.specialholiday + rate.holiday * detail.legalholiday + rate.nightshift * detail.nightdiff,
                                        other_income_non_tax: 0,
                                        sss: rate.sss,
                                        philhealth: rate.philhealth,
                                        pagibig: rate.pagibig,
                                        total_deduction: rate.sss + rate.philhealth + rate.pagibig,
                                        other_deduction: 0,
                                        net: rate.daily * detail.dayswork - (rate.daily / 8 / 60) * detail.hourstardy + rate.overtime * detail.overtime + rate.specialholiday * detail.specialholiday + rate.holiday * detail.legalholiday + rate.nightshift * detail.nightdiff - (rate.sss + rate.philhealth + rate.pagibig),
                                        sss_er: rate.sss
                                    };
                                } else {
                                    console.error('Failed to fetch rates for employee:', detail.employeeid);
                                    return null;
                                }
                            });
                        });

                        payrollSavePromises.push(Promise.all(detailsPromises).then(function (details) {
                            details = details.filter(detail => detail !== null); // Filter out any failed requests

                            console.log('New payroll details for store:', payroll.store);
                            details.forEach(function (detail) {
                                console.log('Employee detail:', detail);
                            });

                            var payload = {
                                store: payroll.store,
                                from: dateFrom,
                                to: dateTo,
                                employeecount: payroll.employeecount,
                                generatedby: payroll.generatedby,
                                details: details
                            };

                            return $.ajax({
                                url: '{{ route("save-payroll") }}',
                                method: 'POST',
                                contentType: 'application/json',
                                data: JSON.stringify(payload),
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                        }));
                    });

                    // Wait for all payroll save operations to complete before refreshing the page
                    Promise.all(payrollSavePromises).then(function () {
                        console.log('All payrolls saved successfully');
                        location.reload(); // Refresh the website
                    }).catch(function (error) {
                        console.error('Error saving some payrolls:', error);
                    });
                }
            });
        });
    });
</script>
@endsection
