@extends('layouts.header_admin')
@section('css')
<link href="{{ asset('admin/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class='row'>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Company</th>
                                <th>Store</th>
                                <th>Generated By</th>
                                <th>Cut Off</th>
                                <th>Employee Count</th>
                                <th>Total Basic Pay</th>
                                <th>Contributions</th>
                            </tr>
                        </thead>
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
@endsection

