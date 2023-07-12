
<div class="modal inmodal" id="change_pass" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                {{-- <i class="fa fa-laptop modal-icon"></i> --}}
                <h4 class="modal-title">Change Password</h4>
            </div>
            <form method='post' action='change-pass' onsubmit='show();'  enctype="multipart/form-data" >
                <div class="modal-body">
                    {{ csrf_field() }}
                    
                        <div class="form-group">
                            <label> New Password :  </label>
                            <input type="password" class="form-control-sm form-control "   name="password" required/>
                        </div>
                        <div class="form-group">
                            <label> Confirmed Password :  </label>
                        <input type="password" class="form-control-sm form-control "   name="password_confirmation" required/>
                     </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
