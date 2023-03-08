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
                            <h4 class="card-title">Groups</h4>
                          

                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Group</th>
                                            <th>Store Count</th>
                                            <th>Action</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groups as $group)
                                        <tr>
                                            <td>{{$group->name}}</td>
                                            <td>{{count($group->stores)}}</td>
                                            <td></td>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">New Group</h4>
                            <form method='POST' action='new-group' onsubmit='show()'>
                                @csrf
                                    <div class="row">
                                        <div class='col-lg-12 form-group'>
                                            <label for="allowanceType">Group</label>
                                            <input name='group' class='form-control form-control-sm' type='text' required> 
                                        </div>
                                        <div class="col-lg-12 form-group">
                                            <label for="employee">Stores</label>
                                            <select data-placeholder="Select Store"
                                                class="form-control form-control-sm required js-example-basic-multiple w-100" multiple="multiple" style='width:100%;' name='stores[]' required>
                                                <option value="">--Select Store--</option>
                                                @foreach ($stores as $store)
                                                    <option value="{{$store}}">{{$store}}</option>
                                                @endforeach
                                            </select>
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

