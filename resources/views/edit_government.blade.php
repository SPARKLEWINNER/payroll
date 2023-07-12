<div class="modal fade" id="editgov{{$payrollInfo->id}}" aria-labelledby="editGov" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" >Edit Government</h5>
        </div>
        
        <div class="modal-body">
        <form  method='POST' action='{{url("edit-government/".$payrollInfo->id)}}' onsubmit='show()' >
          @csrf
            <div class="row">
                <div class='col-md-12 form-group'>
                  Employee Name  : {{$payrollInfo->employee_name}}
              </div>
            </div>
         
          <div class="row">
            <div class='col-md-12 form-group'>
               SSS Contribution
              <input type="number" name='sss_contribution' class="form-control form-control-sm" value='{{$payrollInfo->sss_contribution}}' required>
            </div>
          </div>
          <div class="row">
            <div class='col-md-12 form-group'>
               Philhealth Contribution
              <input type="number" name='nhip_contribution' class="form-control form-control-sm" value='{{$payrollInfo->nhip_contribution}}' required>
            </div>
          </div>
          <div class="row">
            <div class='col-md-12 form-group'>
               Pagibig Contribution
              <input type="number" name='hdmf_contribution' class="form-control form-control-sm" value='{{$payrollInfo->hdmf_contribution}}' required>
            </div>
          </div>
         
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form> 
        
      </div>
      </div>
    </div>
  </div>