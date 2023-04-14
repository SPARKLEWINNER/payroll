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
                            <h4 class="card-title">Users</h4>
                            <p class="card-description">
                                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                                    <div class=row>
                                        <div class='col-md-12 col-lg-12 col-sm-12'>
                                            <div class="form-group row">
                                                <div class="col-sm-4 col-lg-4 col-md-4 p-0 m-3 ">
                                                    <select data-placeholder="Select Store" class="form-control form-control-sm required js-example-basic-single p-0 " style='width:100%;' name='store' required>
                                                        <option value="">-- Select store --</option>
                                                        @foreach($stores as $store)
                                                        <option value="{{$store->store}}" @if ($store->store == $storeData) selected @endif>{{$store->store}}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                </div>
                                                <div class="col-sm-4 col-lg-4 col-md-4 p-0" style="margin-left: 3px; margin-top: 18px">
                                                    <button type="button" class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"></path>
                                                        </svg>
                                                        Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </p>
                        </div>
                    </div>
                </div>
                @if(count($personnels) > 0)
                <div class='row'>
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                   
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <td colspan='27'>
                                                    {{$storeData}}
                                                </td>
                                            </tr>
                                            <tr>
                                              <th>Employee Name</th>
                                              <th>View Record</th>
                                            </tr>
                                          </thead>
                                        <tbody>
                                            @foreach($personnels as $key => $employee)
                                                <tr>
                                                    <td>{{$employee->displayName}}</td>
                                                    <td style="width: 20%"> 
                                                        <a href="#" class="btn btn-primary">
                                                            <i class="icon-eye-open icon-white"></i>
                                                            <span><strong>View</strong></span>          
                                                        </a>
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
                @endif
            </div>
  
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('vendors/select2/select2.min.js')}}"></script>
    <script src="{{ asset('js/select2.js')}}"></script>
@endsection

