<div class="modal fade" id="newSss" tabindex="-1" role="dialog" aria-labelledby="newHolidaylabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newHolidaylabel">New SSS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form  method='POST' action='new-sss' onsubmit='show()' >
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class='col-md-12 form-group'>
               SSS From 
              <input type="number" name='from' step='any' class="form-control form-control-sm" required>
          </div>
          </div>
          <div class="row">
            <div class='col-md-12 form-group'>
               SSS To  
              <input type="number" name='to' step='any' class="form-control form-control-sm" required>
            </div>
          </div>
          <div class="row">
            <div class='col-md-12 form-group'>
               SSS ER  
              <input type="number" name='er' step='any' class="form-control form-control-sm" required>
            </div>
          </div>
          <div class="row">
            <div class='col-md-12 form-group'>
               SSS EE  
              <input type="number" name='ee' step='any' class="form-control form-control-sm" required>
            </div>
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