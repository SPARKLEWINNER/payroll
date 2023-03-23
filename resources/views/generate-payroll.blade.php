@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">
@endsection
@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
            <div class='row'>
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Stores</h4>
                            <p class="card-description">
                                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                                    <div class=row>
                                        <div class='col-md-3'>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label text-right">Stores</label>
                                                <div class="col-sm-8">
                                                    <select data-placeholder="Select Store" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='store' required>
                                                        <option value="">-- Select store --</option>
                                                        @foreach($stores as $store)
                                                        <option value="{{$store->store}}" @if ($store->store == $storeData) selected @endif>{{$store->store}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-3'>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label text-right">From</label>
                                                <div class="col-sm-8">
                                                    <input type="date" value='{{$from}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-3'>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label text-right">To</label>
                                                <div class="col-sm-8">
                                                    <input type="date" value='{{$to}}' class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required />
                                                </div>
                                            </div>
                                        </div>
                                      
                                        <div class='col-md-3'>
                                            <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Generate</button>
                                        </div>
                                    </div>
                                </form>
                            </p>
                      
                        </div>
                    </div>
                </div>
            </div>
            @if(count($employees) >0)
            <div class='row'>
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h5>Holidays <br>
                                @foreach($holidays as $holiday)
                                    {{$holiday->holiday_name}} - {{$holiday->holiday_date}} - {{$holiday->holiday_type}}  <br>
                                @endforeach
                            </h5>
                            <div class="table-responsive">
                               
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <td colspan='27'>
                                                {{$storeData}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan='27'>
                                                Payroll Period of  {{date('M d, Y',strtotime($from))}}  to  {{date('M d, Y',strtotime($to))}}
                                            </td>
                                        </tr>
                                        <tr>
                                          <th>#</th>
                                          <th>Employee No.</th>
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
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td></td>
                                                    <td>{{$employee->emp_name}}</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td>0.00</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan=27>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class='text-right' colspan=3>
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
                                            <th colspan='24'></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
            @endif
		</div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('vendors/select2/select2.min.js')}}"></script>
    <script src="{{ asset('js/select2.js')}}"></script>
@endsection

