@extends('layouts.header_admin')
@section('css')
    <link href="{{ asset('admin/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
@endsection
@section('content')
            <div class='row'>
                <div class="col-lg-8 grid-margin stretch-card">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5> Groups </h5> 
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                              
                            </div>
                        </div>
                        <div class="ibox-content">
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" >
                                    <thead>
                                        <tr>
                                            <th>Group</th>
                                            <th>Store Count</th>
                                            <th>Store</th>
                                            <th>Action</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                <tbody>
                                    @foreach($groups as $group)
                                    <tr>
                                        <td>{{$group->name}}</td>
                                        <td>{{count($group->stores)}}</td>
                                        <form method='GET' action="store-remove" onsubmit='show()' enctype="multipart/form-data">
                                            <td>  
                                                <select data-placeholder="Select Store" 
                                                    class="form-control form-control-sm required js-example-basic-single chosen-select" 
                                                    style='width:100%;'
                                                    name='store' 
                                                    required
                                                >
                                                    @foreach($group->stores as $store)    
                                                        <option value="{{$store->id}}">{{$store->store}}</option>
                                                    @endforeach
                                                </select>  
                                            </td>
                                           
                                            <td>
                                                
                                                    <button type="submit" class="btn btn-primary btn-icon-text btn-sm ">
                                                        <i class="fa fa-trash"></i>
                                                        
                                                    </button>

                                            </td>
                                        </form>
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
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5> New Group </h5> 
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                              
                            </div>
                        </div>
                        <div class="ibox-content">
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
                                                class="form-control form-control-sm required js-example-basic-multiple w-100 chosen-select" multiple="multiple" style='width:100%;' name='stores[]' required>
                                                <option value="">--Select Store--</option>
                                                @foreach ($stores as $store)
                                                    <option value="{{$store->store}}">{{$store->store}}</option>
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
@include('new_group')
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

