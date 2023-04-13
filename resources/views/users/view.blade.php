@extends('layouts.header_admin')
@section('css')
    <link href="{{ asset('admin/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="ibox float-e-margins">
              	<div class="ibox-title">
                  <form method='GET' onsubmit='show();' enctype="multipart/form-data">
                    <div class='row'>
                      <div class="col-lg-3">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label text-right">Select Store</label>
                          <div class="col-sm-8">
                              <select 
                                data-placeholder="Select Store" 
                                class="form-control form-control-sm required chosen-select col-lg-8 col-sm-8" 
                                style='width:70%; margin-bottom: 10px; margin-right: 10px;'
                                name='store'
                                required>
                                <option value="">-- Select store --</option>
                                @foreach($stores as $store)    
                                    <option value="{{$store->store}}" @if ($store->store == $storeData) selected @endif>{{$store->store}}</option>
                                  @endforeach
                              </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label text-right">From</label>
                          <div class="col-sm-8">
                              <input type="date" value='{{$from}}' class="form-control" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required />
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label text-right">To</label>
                          <div class="col-sm-8">
                              <input type="date" value='{{$to}}' class="form-control" name="to" id='to' max='{{date('Y-m-d')}}' required />
                          </div>
                      </div>
                      </div>
                      <div class="col-lg-3">
                        <button type="submit" class="btn btn-primary col-sm-3 col-lg-3 col-md-3">View</button>
                      </div>
                    </div>
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
                                    <button type="button" class="btn btn-outline btn-primary dim btn-sm" data-target="#viewRecord{{$employee->_id}}" data-toggle="modal" title='RECORD'>
                                        View Record
                                    </button>
                                    <button href="#" class="btn btn-outline btn-info dim btn-sm">
                                        <i class="icon-eye-open icon-white"></i>
                                        <span><strong>  Set Rates </strong></span>          
                                    </button>
                                </td>
                            </tr>
                         
                        @endforeach
                      </tbody>
                      @endif
                    </table>
                </div>
      
              </div>
       
            </div>
          </div>
    </div>
    @if(count($personnels) > 0)
      @foreach($personnels as $key => $employee)
        @include('users.view_record')
      @endforeach
    @endif
    @php
    function get_working_hours($timeout,$timein)
    {
        return round((((strtotime($timeout) - strtotime($timein)))/3600),2);
    }
    function get_late($schedule,$timein)
    {
        $late = (((strtotime($schedule->time_in) - strtotime($timein)))/3600);
        // dd($late);
        if($late < 0)
        {
            $late_data = $late;
        }
        else {
            $late_data = 0;
        
        }
        return round($late_data*-1,2);
    }

    function night_difference($start_work,$end_work)
    {
        $start_night = mktime('22','00','00',date('m',$start_work),date('d',$start_work),date('Y',$start_work));
        $end_night   = mktime('06','00','00',date('m',$start_work),date('d',$start_work) + 1,date('Y',$start_work));

        if($start_work >= $start_night && $start_work <= $end_night)
        {
            if($end_work >= $end_night)
            {
                return ($end_night - $start_work) / 3600;
            }
            else
            {
                return ($end_work - $start_work) / 3600;
            }
        }
        elseif($end_work >= $start_night && $end_work <= $end_night)
        {
            if($start_work <= $start_night)
            {
                return ($end_work - $start_night) / 3600;
            }
            else
            {
                return ($end_work - $start_work) / 3600;
            }
        }
        else
        {
            if($start_work < $start_night && $end_work > $end_night)
            {
                return ($end_night - $start_night) / 3600;
            }
            return 0;
        }
    }
@endphp
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