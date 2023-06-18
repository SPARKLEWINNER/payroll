@extends('layouts.header_admin')
@section('css')
    <link href="{{ asset('admin/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
@endsection
@section('content')
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
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
                                <table id="myTable" class="table table-striped table-bordered table-hover dataTables-example" >
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
                                        @if(count($group->stores) > 0)
                                            <tr>
                                                <input type="hidden" value="{{$group->id}}">
                                                <td id="{{$group->id}}">{{$group->name}}</td>
                                                <td>{{count($group->stores)}}</td>
                                                <form method='GET' action="store-remove" onsubmit='show()' enctype="multipart/form-data">
                                                    <td>  
                                                        <select data-placeholder="Select Store" 
                                                            class="form-control form-control-sm required js-example-basic-single chosen-select"
                                                            id="store-name" 
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
                                                        <button type="submit"  id="remove-store-{{$store->id}}" class="btn btn-primary btn-icon-text btn-sm remove-store">
                                                            <i class="fa fa-trash"></i>
                                                            
                                                        </button>
                                                        <button type="button"  id="set-rates" class="btn btn-primary btn-icon-text btn-sm set-rates" data-toggle="modal" title='EDIT'>
                                                            <i class="fa fa-tasks" aria-hidden="true"></i> 
                                                        </button>
                                                    </td>
                                                </form>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        @endif
                                        {{-- New Laborer --}}
                                        <div class="modal fade" id="edit_rates" tabindex="-1" role="dialog" aria-labelledby="EditRatesData" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <div class='row'>
                                                            <div class='col-md-12'>
                                                                <h5 class="modal-title" id="EditHoldayData"></h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <form  method='POST' action='edit-rates' onsubmit='show()'>
                                                        <div class="modal-body">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="status" value="1" placeholder='status' class="form-control status" required>
                                                            <input type="hidden" name="rateid" placeholder='status' class="form-control rateid" required>
                                                            <label>Daily Rate:</label>
                                                            <input type="text" name="dailyRate" placeholder='Holiday Name'class="form-control dailyRate" required>
                                                            <label >Holiday Rate:</label>
                                                            <input type="text" name="holidayRate" placeholder='Holiday Name' class="form-control holidayRate" required>
                                                            <label >Holiday OT Rate:</label>
                                                            <input type="text" name="holidayot" placeholder='Holiday Name' class="form-control holidayot" required>
                                                            <label >Holiday Rest Day Rate:</label>
                                                            <input type="text" name="holidayrestday" placeholder='Holiday Name' class="form-control holidayRestDay" required>
                                                            <label >Holiday Rest Day OT Rate:</label>
                                                            <input type="text" name="holidayrestdayot" placeholder='Holiday Name' class="form-control holidayRestDayOt" required>
                                                            <label >Nightshift Rate:</label>
                                                            <input type="text" name="nightshift" placeholder='Holiday Name' class="form-control nightshift" required>
                                                            <label >Overtime Rate:</label>
                                                            <input type="text" name="overtime" placeholder='Holiday Name'class="form-control overtime" required>
                                                            <label >SSS Rate:</label>
                                                            <input type="text" name="sss" placeholder='Holiday Name' class="form-control sss" required>
                                                            <label >Pag-ibig Rate:</label>
                                                            <input type="text" name="pagibig" placeholder='Holiday Name' class="form-control pagibig" required>
                                                            <label >Philhealth Rate:</label>
                                                            <input type="text" name="philhealth" placeholder='Holiday Name' class="form-control philhealth" required>
                                                            <label >Rest Day Duty Rate:</label> 
                                                            <input type="text" name="restday" placeholder='Holiday Name' class="form-control restday" required>
                                                            <label >Rest Day OT Rate:</label>
                                                            <input type="text" name="restdayot" placeholder='Holiday Name' class="form-control restdayot" required>
                                                            <label >Special Holiday Rate:</label>
                                                            <input type="text" name="specialholiday" placeholder='Holiday Name' class="form-control specialholiday" required>
                                                            <label >Special Holiday Rate:</label>
                                                            <input type="text" name="specialholidayot" placeholder='Holiday Name' class="form-control specialholidayot" required><label >Special Holiday RD Rate:</label>
                                                            <input type="text" name="specialholidayrestday" placeholder='Holiday Name' class="form-control specialholidayrestday" required>
                                                            <label >Special Holiday RD OT Rate:</label>
                                                            <input type="text" name="specialholidayrestdayot" placeholder='Holiday Name' class="form-control specialholidayrestdayot" required>
                                                            <label >Allowance:</label>
                                                            <input type="text" name="allowance" placeholder='Allowance' class="form-control allowance" required>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" id='submit1' class="btn btn-primary">Save</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
            const buttons = document.querySelectorAll("#myTable .set-rates");
            buttons.forEach(function(button) {
                button.addEventListener("click", async function() {
                    document.getElementById("loader").style.display = "block"
                    const row = this.closest("tr");
                    const id = row.cells[0].id;
                    const name = row.cells[0].innerText;
                    const option = {
                        method: "GET",
                        headers: {
                          "Content-Type": "application/json",
                        },
                    }
                    const response = await fetch(`rates/${id}`, option)
                    const d = await response.json()
                    if (d.status === "success") {
                        $('.modal-title').text(name + " RATES")
                        $('.dailyRate').val(d.data.daily)
                        $('.rateid').val(id)
                        $('.holidayRate').val(d.data.holiday)
                        $('.holidayot').val(d.data.holidayot)
                        $('.holidayRestDay').val(d.data.holidayrestday)
                        $('.holidayRestDayOt').val(d.data.holidayrestdayot)
                        $('.nightshift').val(d.data.nightshift)
                        $('.overtime').val(d.data.overtime)
                        $('.sss').val(d.data.sss)
                        $('.pagibig').val(d.data.pagibig)
                        $('.philhealth').val(d.data.philhealth)
                        $('.restday').val(d.data.restday)
                        $('.restdayot').val(d.data.restdayot)
                        $('.specialholiday').val(d.data.specialholiday)
                        $('.specialholidayot').val(d.data.specialholidayot)
                        $('.specialholidayrestday').val(d.data.specialholidayrestday)
                        $('.specialholidayrestdayot').val(d.data.specialholidayrestdayot)
                        $('.allowance').val(d.data.allowance)
                        $(`#edit_rates`).modal().show();
                        document.getElementById("loader").style.display = "none";
                    }
                    else {
                        alert("Something went wrong please contact admin")
                        document.getElementById("loader").style.display = "none";
                    }
                    
                });
                
            });
    </script>
@endsection

