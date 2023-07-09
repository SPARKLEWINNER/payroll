{{-- New Laborer --}}
<div class="modal " id="viewRecord{{$employee->_id}}" tabindex="-1" role="dialog" aria-labelledby="ViewRecordData" aria-hidden="true">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="exampleModalLabel">Record - {{$employee->_id}}</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
	      		{{-- <form method='get' enctype="multipart/form-data">
	      			<div class="row">
		      			<div class='col-md-4 col-lg-4'>			
				      		<div class="form-group row">
			  		            <label for="recipient-name" class="col-form-label col-md-3">From:</label>
			  		            <input type="date" class="form-control col-m-3" name="from" max='{{date('Y-m-d')}}' onchange='get_min(this.value);' required />
			  		       	</div>
			  		    </div>
			  		    <div class='col-md-4'>
			  		        <div class="form-group row">
			  		            <label for="message-text" class="col-form-label col-sm-3">To:</label>
			  		            <input type="date" class="form-control col-sm-8" name="to" id='to' max='{{date('Y-m-d')}}' required />
			  		        </div>
			  		    </div>
	  		       	</div>
	  		       	<button type="submit" class="btn btn-outline btn-primary dim btn-sm" style="margin-right: 20px;">View</button>
		      	</form> --}}
		      	<table class="table table-hover table-bordered">
		      	    <thead>
		      	        <tr>
		      	            <td colspan='11'>{{$employee->_id}} - {{strtoupper($employee->displayName)}}</td>
		      	          </tr>
		      	        <tr>
		      	          <th>Date</th>
		      	          <th>Schedule</th>
		      	          <th>Time In</th>
		      	          <th>Time Out</th>
		      	          <th>Working Hrs </th>
		      	          <th>Lates </th>
		      	          <th>Undertime</th>
		      	          <th>Overtime</th>
		      	          <th>Night Diff</th>
		      	        </tr>
		      	      </thead>
						<tbody>
							@php
								$attendance = $attendances->where('emp_id',$employee->_id);
							@endphp
							@foreach($date_range as $date)
							@php
								$time_in = ($attendance->where('status','time-in')->where('date',$date))->first();
								$time_out = ($attendance->where('status','time-out')->where('date',$date))->first();
								$schedule = (($schedules)->where('emp_id',$employee->_id)->where('date',$date))->first();
							@endphp
								<tr>
									<td>{{date('M d, Y - l',strtotime($date))}}</td>
									<td>{{($schedule != null) ? date('h:i a', strtotime($schedule->time_in)) . " to " . date('h:i a', strtotime($schedule->time_out)) : "No Schedule"}}</td>
									<td>{{($time_in != null) ? date('h:i a',strtotime($time_in->time)) : ""}}</td>
									<td>{{($time_out != null) ? date('h:i a',strtotime($time_out->time)) : ""}}</td>
									<td>{{(($time_in != null) && ($time_out != null) ) ? get_working_hours($time_out->time,$time_in->time)." hrs" : "0.00 hrs" }}  </td>
									<td>{{((($time_in != null) && ($time_out != null) && ($schedule != null)) ) ? get_late($schedule,$time_in->time)." hrs" : "0.00 hrs" }} </td>
									<td>Undertime</td>
									<td>Overtime</td>
									<td>{{((($time_in != null) && ($time_out != null) && ($schedule != null)) ) ? night_difference(strtotime($time_in->time),strtotime($time_out->time))." hrs" : "0.00 hrs"}}</td>
								</tr>
							@endforeach
						</tbody>
		      	</table>
      		</div>
      		<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>
