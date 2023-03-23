@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">
@endsection
@section('content')
	<div class="main-panel">
		<div class="content-wrapper">
            <div class='row'>
                <div class="col-lg-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Salaries</h4>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Store</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Less to Billing</th>
                                            <th>Added on Payroll</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($salaries as $salary)
                                        <tr>
                                            <td>{{$salary->store}}</td>
                                            <td>{{number_format($salary->rate,2)}}</td>
                                            <td>{{number_format($salary->amount,2)}}</td>
                                            <td>{{number_format($salary->less_to_billing,2)}}</td>
                                            <td>{{number_format($salary->deducted_on_payroll,2)}}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-icon-text btn-sm">
                                                    <i class="ti-file btn-icon-prepend"></i>
                                                    Edit
                                                  </button>
                                            </td>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">New Salary</h4>
                            <form method='POST' action='new-salary' onsubmit='show()'>
                                @csrf
                                    <div class="row">
                                       
                                        <div class="col-lg-12 form-group">
                                            <label for="employee">Stores</label>
                                            <select data-placeholder="Select Store"
                                                class="form-control form-control-sm required js-example-basic-multiple w-100"  style='width:100%;' name='stores' required>
                                                <option value="">--Select Store--</option>
                                                @foreach ($stores as $store)
                                                    <option value="{{$store->store}}">{{$store->store}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class='col-lg-12 form-group'>
                                            <label for="allowanceType">Daily Rate</label>
                                            <input name='rate' class='form-control form-control-sm' type='number' min="0" step="0.01" required> 
                                        </div>
                                        <div class='col-lg-12 form-group'>
                                            <label for="allowanceType">Allowance Amount</label>
                                            <input name='allowance_amount' class='form-control form-control-sm' type='number' min="0" step="0.01" required> 
                                        </div>
                                        <div class='col-lg-12 form-group'>
                                            <label for="allowanceType">Less to Billing</label>
                                            <input name='less_to_billing' class='form-control form-control-sm' type='number'  step="0.01" required> 
                                        </div>
                                        <div class='col-lg-12 form-group'>
                                            <label for="allowanceType">Added on Payroll</label>
                                            <input name='deducted_on_payroll' class='form-control form-control-sm' type='number' step="0.01" required> 
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
		</div>
    </div>
@include('new_group')
@endsection
@section('js')
    <script src="{{ asset('vendors/select2/select2.min.js')}}"></script>
    <script src="{{ asset('js/select2.js')}}"></script>
@endsection

