<div class="modal fade" id="transfer{{$payrollInfo->id}}" tabindex="-1" role="dialog" aria-labelledby="transferPayroll" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" >Transfer Payroll</h5>
        </div>
        <form  method='POST' action='{{url('transfer-payroll/'.$payrollInfo->id)}}' onsubmit='show()' >
          @csrf
          <div class="modal-body">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-right ">Select Store</label>
                <div class="col-sm-8">
                    <select 
                        data-placeholder="Select Store" 
                        class="form-control form-control-sm required chosen-select col-lg-8 col-sm-8" 
                        style='width:70%; margin-bottom: 10px; margin-right: 10px;'
                        name='store'
                        required >
                        <option value="">-- Select store --</option>
                        @foreach($payrolls as $pay)    
                            <option value="{{$pay->id}}">{{$pay->store}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
          </div>
         
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Transfer</button>
          </div>
        </form> 
      </div>
    </div>
  </div>