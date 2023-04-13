@extends('layouts.header_admin')

@section('content')
    <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="ibox float-e-margins">
              	<div class="ibox-title col-lg-12 form-group mb-3 row">
              		<label for="employee">Select Store</label>
              		<form method='GET' onsubmit='show();' enctype="multipart/form-data">
	                  	<select 
	                  		data-placeholder="Select Store" 
	                     	class="form-control form-control-sm required js-example-basic-single chosen-select col-lg-8 col-sm-8" 
	                     	style='width:70%; margin-bottom: 10px; margin-right: 10px;'
	                     	name='store'
	                     	required
	                     	
	                  	>
	                  		<option value="">-- Select store --</option>
	                     	@foreach($stores as $store)    
	                        	<option value="{{$store->store}}" @if ($store->store == $storeData) selected @endif>{{$store->store}}</option>
	                      	@endforeach
	                  	</select>
	                  	<button type="submit" class="btn btn-primary col-sm-3 col-lg-3 col-md-3">View</button>
	                </form> 
              	</div>
              	<div class="ibox-content">
      
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                      <thead>
                        <tr>
                            
                          <th>Employee Name</th>
                          <th>Action</th>
                      </tr>
                      </thead>
                      @if(count($personnels) > 0)
                      <tbody>
                        @foreach($personnels as $key => $employee)
                            <tr>
                                <td>{{$employee->displayName}}</td>
                                <td style="width: 20%"> 
                                    <button type="button" class="btn btn-outline btn-primary dim btn-sm" data-target="#exampleModal" data-toggle="modal" title='RECORD'>
                                        View Record
                                    </button>
                                    <button href="#" class="btn btn-outline btn-info dim btn-sm">
                                        <i class="icon-eye-open icon-white"></i>
                                        <span><strong>  Set Rates </strong></span>          
                                    </button>
                                </td>
                            </tr>
                        @include('users.view_record')
                        @endforeach
                      </tbody>
                      @endif
                    </table>
                </div>
      
              </div>
       
            </div>
          </div>
    </div>

@endsection

@section('js')
<script src="{{ asset('admin/js/inspinia.js')}}"></script>
<script src="{{ asset('admin/js/plugins/pace/pace.min.js')}}"></script>
@endsection
