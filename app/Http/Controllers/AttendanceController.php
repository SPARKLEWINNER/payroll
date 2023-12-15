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
        $attendance->time = date('Y-m-d H:i:s', strtotime($request->time));
        $attendance->store = $request->store;
        $attendance->remarks = url('');
        $attendance->date = date('Y-m-d', strtotime($request->date));
        $attendance->record_id = $request->record_id;
        $attendance->save();

        return [
            'status' => 'success',
            'data' => $attendance
        ];
    }

    public function get(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://time-in-production-api.onrender.com/api/user/recordsv2/' . $request->store_id . '/' . $request->from . '/' . $request->to);
        $employees = json_decode((string) $response->getBody(), true);
        foreach ($employees as $emp) {
            if (!empty($emp['reports'])) {
                dd($emp['Employee']);
                foreach ($emp['reports']['0']['record'] as $record) {
                    $attendance = new Attendance;
                    $attendance->emp_id = $emp['Employee']['_id'];
                    $attendance->emp_name = $emp['Employee']['displayName'];
                    $attendance->status = $record['status'];
                    $attendance->time = date('Y-m-d H:i:s', strtotime(str_replace("Z", " ", $record['dateTime'])));
                    $attendance->store = "Syzygy Staffing_JB Naga Almeda Highway";
                    $attendance->remarks = url('');
                    $attendance->date = $emp['date'];
                    $attendance->save();
                }
            }
        }

        return 'success';
    }
    public function sample()
    {
        return view('sample');
    }
}
