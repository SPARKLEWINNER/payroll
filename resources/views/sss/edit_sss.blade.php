{{-- New Laborer --}}
<div class="modal fade" id="edit_sss{{$sss->id}}" tabindex="-1" role="dialog" aria-labelledby="EditHoldayData" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='row'>
                    <div class='col-md-12'>
                        <h5 class="modal-title" id="EditHoldayData">Edit SSS</h5>
                    </div>
                </div>
            </div>
            <form  method='POST' action='edit-sss/{{$sss->id}}' onsubmit='show()' >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <label>From:</label>
                    <input type="number" name="from" placeholder='From Range' value='{{$sss->from_range}}' class="form-control" required>
                    <label >To:</label>
                    <input type="number" name="to" placeholder='To Range' value='{{$sss->to_range}}' class="form-control" required>
                    <label >ER:</label>
                    <input type="number" name="er" placeholder='ER' value='{{$sss->er}}' class="form-control" required>
                    <label >EE:</label>
                    <input type="number" name="ee" placeholder='EE' value='{{$sss->ee}}' class="form-control" required>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id='submit1' class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>