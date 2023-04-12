@extends('layouts.header_admin')

@section('content')
    <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="ibox float-e-margins">
              <div class="ibox-title">
                  <h5> Holidays </h5> &nbsp;  <button class="btn btn-outline btn-primary btn-xs"  data-toggle="modal" data-target="#newHoliday" type="button"><i class="fa fa-plus"></i></button>
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
                            
                          <th>Holiday Date</th>
                          <th>Holiday Name</th>
                          <th>Holiday Type</th>
                          <th>Holiday Status</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($holidays as $holiday)
                        <tr>
                            
                            <td>{{date('Y.').date('m.d',strtotime($holiday->holiday_date))}}</td>
                            <td>{{$holiday->holiday_name}}</td>
                            <td>{{$holiday->holiday_type}}</td>
                            <td>
                                @if($holiday->status == "Permanent") 
                                    {{$holiday->status}}
                                @else
                                    <button type="button" class="btn btn-outline btn-primary dim btn-sm" href="#edit_holiday{{$holiday->id}}" data-toggle="modal" title='EDIT'>
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a href="delete-holiday/{{$holiday->id}}">
                                        <button  title='DELETE' onclick="return confirm('Are you sure you want to delete this holiday?')" class="btn btn-outline btn-danger dim btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @include('holidays.edit_holiday')
                        @endforeach
                      </tbody>
                    </table>
                </div>
      
              </div>
       
            </div>
          </div>
    </div>
@include('holidays.new_holiday')
@endsection

@section('js')
<script src="{{ asset('admin/js/inspinia.js')}}"></script>
<script src="{{ asset('admin/js/plugins/pace/pace.min.js')}}"></script>
@endsection
