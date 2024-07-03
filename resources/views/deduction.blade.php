<div class="modal fade" id="DeductionIncome{{$payrollInfo->id}}" tabindex="-1" aria-labelledby="addIncome" aria-hidden="true">
    <form  method='POST' action='{{url("deduction-income/".$payrollInfo->id)}}' onsubmit='show()' enctype="multipart/form-data" >

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Deduction </h5>
                </div>
                    <div class="modal-body">             
                        @csrf
                        <div class="row">
                            <div class='col-md-12 form-group'>
                                Employee Name  : {{$payrollInfo->employee_name}}
                            </div>
                        </div>
  <!--                      <div class="row ">
                            <div class='col-md-12  form-group'>
                                <button class="btn btn-info btn-circle" onclick="add_deduction({{$payrollInfo->id}})" type="button"><i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>-->
                        <div class="row ">
                            <div class='col-md-5  form-group'>
                               Deduction Name 
                            </div>
                            <div class='col-md-5  form-group'>
                                Amount
                            </div>
<!--                            <div class='col-md-2 border form-group'>
                            Action
                            </div>-->
                        </div>
                        
                        <div id='deduction-{{$payrollInfo->id}}'>
                            <div class="row " id='deduction-{{$payrollInfo->id}}-0'>
                                <div class='col-md-5 border form-group'>
                                    <input name='deduction_name[]' type='text' placeholder='Company Loan' class='form-control form-control-sm' required>
                                </div>
                                <div class='col-md-5 border form-group'>
                                    <input name='deduction_amount[]' type='number' min='0' step='any' value=0 placeholder='1.00' class='form-control form-control-sm' required>
                                </div>
<!--                                <div class='col-md-2 border form-group'>
                                    <button class='btn btn-danger btn-circle' onclick='remove_deduction({{$payrollInfo->id}},0)' type='button'><i class='fa fa-minus'></i></button>
                                </div>-->
                            </div>
                            @if(count($payrollInfo->deductions) > 0)
                            <div class="row ">
                                <div class='col-md-12  form-group'>
                                   <hr>Current Deductions</hr>
                                </div>
                            </div>
                            @foreach($payrollInfo->deductions as $key => $deduction)
                                <div class="row" id='deduction-{{$deduction->id}}'>
                                    <div class='col-md-5 border form-group'>
                                        <input name='deduction_name[]' value='{{$deduction->name}}' type='text' placeholder='Company Loan' class='form-control form-control-sm' disabled>
                                    </div>
                                    <div class='col-md-5 border form-group'>
                                        <input name='deduction_amount[]' value='{{$deduction->amount}}' type='number' placeholder='1.00' class='form-control form-control-sm' disabled>
                                    </div>
                                    <div class='col-md-2 border form-group'>
                                        <button class='btn btn-danger btn-circle' onclick='deleteDeduction({{$deduction->id}})' type='button'><i class='fa fa-minus'></i></button>
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
        </div>
    
    </form>
</div>

<script>
function deleteDeduction(deductionId) {
    if (confirm('Are you sure you want to delete this deduction?')) {
        show();
        fetch(`{{ url('deduction-income/delete') }}/${deductionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`deduction-${deductionId}`).remove();
                location.reload();
            } else {
                alert('Failed to delete deduction');
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    }
}
</script>