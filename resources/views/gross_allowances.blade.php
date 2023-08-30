<div class="modal fade" id="AdditionalGrossIncome{{$payrollInfo->id}}" tabindex="-1" aria-labelledby="addIncome" aria-hidden="true">
    
    <form  method='POST' action='{{url("additional-income-gross/".$payrollInfo->id)}}' onsubmit='show()' enctype="multipart/form-data" >
              
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Additional Gross Income</h5>
                </div>
                    <div class="modal-body">             
                        @csrf
                        <div class="row">
                            <div class='col-md-12 form-group'>
                                Employee Name  : {{$payrollInfo->employee_name}}
                            </div>
                        </div>
                        <div class="row ">
                            <div class='col-md-12  form-group'>
                                <button class="btn btn-info btn-circle" onclick="add_income_gross({{$payrollInfo->id}})" type="button"><i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row ">
                            <div class='col-md-5  form-group'>
                                Allowance Name 
                            </div>
                            <div class='col-md-5  form-group'>
                                Amount
                            </div>
                            <div class='col-md-2 border form-group'>
                            Action
                            </div>
                        </div>
                        <div id='allowance-gross-{{$payrollInfo->id}}'>
                            @if(count($payrollInfo->payroll_gross_allowances) == 0)
                            
                                <div class="row " id='allowance-{{$payrollInfo->id}}-0'>
                                    <div class='col-md-5 border form-group'>
                                        <input name='allowance_name[]' type='text' placeholder='Meal Allowance' class='form-control form-control-sm' required>
                                    </div>
                                    <div class='col-md-5 border form-group'>
                                        <input name='allowance_amount[]' type='number' min='0' value=0 placeholder='1.00' class='form-control form-control-sm' required>
                                    </div>
                                    <div class='col-md-2 border form-group'>
                                        <button class='btn btn-danger btn-circle' onclick='remove_allowance_gross({{$payrollInfo->id}},0)' type='button'><i class='fa fa-minus'></i></button>
                                    </div>
                                </div>
                            @else
                                @foreach($payrollInfo->payroll_gross_allowances as $key => $allowance)
                                <div class="row " id='allowance-{{$payrollInfo->id}}-{{$key}}'>
                                    <div class='col-md-5 border form-group'>
                                        <input name='allowance_name[]' value='{{$allowance->name}}' type='text' placeholder='Meal Allowance' class='form-control form-control-sm' required>
                                    </div>
                                    <div class='col-md-5 border form-group'>
                                        <input name='allowance_amount[]' value='{{$allowance->amount}}' type='number'  placeholder='1.00' class='form-control form-control-sm' required>
                                    </div>
                                    <div class='col-md-2 border form-group'>
                                        <button class='btn btn-danger btn-circle' onclick='remove_allowance_gross({{$payrollInfo->id}},0)' type='button'><i class='fa fa-minus'></i></button>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        
                    </div> 
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
            </div>
        </div>
    
    </form>
</div>