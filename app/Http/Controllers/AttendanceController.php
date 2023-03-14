<?php

namespace App\Http\Controllers;
use App\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //
    public function create(Request $request)
    {
        $attendance = new Attendance;
        $attendance->emp_id = $request->emp_id;
        $attendance->emp_name = $request->emp_name;
        $attendance->status = $request->status;
        $attendance->time = date('Y-m-d h:i:s',strtotime($request->time));
        $attendance->store = $request->store;
        $attendance->remarks = url('');
        $attendance->date = date('Y-m-d',strtotime($request->date));
        $attendance->record_id = $request->record_id;
        $attendance->save();

        return ['success',
                'data' => $attendance];

    }
}
