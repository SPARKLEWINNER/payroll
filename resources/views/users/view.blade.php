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
    <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="ibox float-e-margins">
              	<div class="ibox-title">
                  <form method='GET' onsubmit='show();' enctype="multipart/form-data">
                    <div class='row'>
                      <div class="col-lg-3">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label text-right ">Select Store</label>
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
                    <table id="myTable" class="table table-striped table-bordered table-hover dataTables-example" >
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
                                <td id="{{$employee->_id}}">{{strtoupper($employee->displayName)}}</td>
                                <td style="width: 20%"> 
                                    <button type="button" class="btn btn-outline btn-primary dim btn-sm" data-target="#viewRecord{{$employee->_id}}" data-toggle="modal" title='RECORD'>
                                        View Record
                                    </button>
                                    <button type="button" id="set-rates" class="btn btn-outline btn-info dim btn-sm set-rates" data-toggle="modal" title='EDIT'>
                                        <i class="icon-eye-open icon-white"></i>
                                        <span><strong>  Set Rates </strong></span>          
                                    </button>
                                    <button type="button" id="delete-rates" class="btn btn-outline btn-danger dim btn-sm delete-rates" data-toggle="tooltip" title="Delete Rates">
                                        <span><strong>  Delete Rates </strong></span>
                                    </button>
                                </td>
                            </tr>
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
                                                <input type="hidden" name="status" value="3" placeholder='status' class="form-control status" required>
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
                                                <input type="text" name="allowance" placeholder='Holiday Name' class="form-control allowance" required>
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
          const buttons = document.querySelectorAll("#myTable #set-rates");
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
                    $('.modal-title').text(name.toUpperCase() + "- RATES")
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
             })
          })
          const deleteRateButtons = document.querySelectorAll("#myTable #delete-rates");
            deleteRateButtons.forEach(function(button) {
                button.addEventListener("click", async function() {
                    if (confirm('Are you sure you want to delete the rates?')) {
                        document.getElementById("loader").style.display = "block";
                        const row = this.closest("tr");
                        const id = row.cells[0].id;
                        const option = {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ rateid: id })
                        }
                        const response = await fetch('{{ route('delete-rates') }}', option);
                        const d = await response.json();
                        if (d.message) {
                            alert(d.message);
                            location.reload();
                        } else {
                            alert(d.error || "Something went wrong please contact admin");
                            document.getElementById("loader").style.display = "none";
                        }
                    }
                })
            });
    </script>
@endsection