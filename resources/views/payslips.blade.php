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
                                    id="storeList">
                                    <option value="">-- Select store --</option>
                                    @foreach($stores as $store)    
                                        <option value="{{$store->store}}" @if ($store->store == $storeData) selected @endif>{{$store->store}}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-right">Date Period</label>
                            <div class="col-sm-8">
                                <select 
                                    data-placeholder="Select Period" 
                                    class="form-control form-control-sm required chosen-select col-lg-8 col-sm-8" 
                                    style='width:70%; margin-bottom: 10px; margin-right: 10px;'
                                    name='from'
                                    id="storeList">
                                    <option value="">-- Select Period --</option>
                                    @foreach($cutoffs as $cutoff)    
                                        <option value="{{$cutoff->payroll_from}}" @if ($cutoff->payroll_from == $payrolldate) selected @endif>{{date('M d, Y',strtotime($cutoff->payroll_from))}} - {{date('M d, Y',strtotime($cutoff->payroll_to))}}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary col-sm-3 col-lg-3 col-md-3">Generate</button>
                        </div>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>Payslip @if(count($payrollsInfo) > 0) <a title='Print Payslip' target='_blank' href='{{url('/payslips_all?store='.$storeData.'&from='.$payrolldate)}}'  ><button type="button"  class="btn btn-danger btn-icon btn-sm">
                                        <i class="fa fa-print"></i>
                                        </button>
                                    </a> @endif </th>
                                    <th>Employee</th>
                                    <th>Gross Pay</th>
                                    <th>Net Pay</th>
                                    <th>Store</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrollsInfo as $payroll)
                                <tr>
                                    <td>
                                        <a title='Print Payslip' target='_blank' href='{{url('/payslip/'.$payroll->id)}}' id='{{$payroll->id}}' ><button type="button"  class="btn btn-danger btn-icon btn-sm">
                                            <i class="fa fa-print"></i>
                                            </button>
                                        </a>
                                    </td>
                                    <td>{{strtoupper($payroll->employee_name)}}</td>
                                    <td>{{number_format($payroll->gross_pay,2)}}</td>
                                    <td>{{number_format($payroll->net_pay,2)}}</td>
                                    <td>{{$storeData}}</td>
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
<script>
    $(document).ready(function(){
      $('.chosen-select').chosen({width: "100%"});
    });
</script>
@endsection

