@extends('layouts.header_admin')

@section('content')
    <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="ibox float-e-margins">
              <div class="ibox-title">
                  <h5> SSS </h5> &nbsp;  <button class="btn btn-outline btn-primary btn-xs"  data-toggle="modal" data-target="#newSss" type="button"><i class="fa fa-plus"></i></button>
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
                            
                          <th>SSS From</th>
                          <th>SSS To</th>
                          <th>SSS ER</th>
                          <th>SSS EE</th>
                          <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($sss as $sss)
                        <tr>
                            <td>{{$sss->from_range}}</td>
                            <td>{{$sss->to_range}}</td>
                            <td>{{$sss->er}}</td>
                            <td>{{$sss->ee}}</td>
                            <td> 
                              <button type="button" class="btn btn-outline btn-primary dim btn-sm" href="#edit_sss{{$sss->id}}" data-toggle="modal" title='EDIT'>
                                <i class="fa fa-edit"></i>
                              </button>
                            </td>
                        </tr>
                        @include('sss.edit_sss')
                        @endforeach
                      </tbody>
                    </table>
                </div>
      
              </div>
       
            </div>
          </div>
    </div>
@include('sss.new_sss')
@endsection

@section('js')
<script src="{{ asset('admin/js/inspinia.js')}}"></script>
<script src="{{ asset('admin/js/plugins/pace/pace.min.js')}}"></script>
@endsection
