<?php

namespace App\Http\Controllers;
use App\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    //
    public function create(Request $request)
    {
        $schedule = new Schedule;
        $schedule->emp_id = $request->emp_id;
        $schedule->time_in = date('Y-m-d H:i:s',strtotime($request->time_in));
        $schedule->time_out = date('Y-m-d H:i:s',strtotime($request->time_out));
        $schedule->total_hours = floatval($request->total_hours);
        $schedule->date = date('Y-m-d',strtotime($request->date));
        $schedule->save();

        return ['status' => 'success',
                'data' => $schedule];

    }
}
