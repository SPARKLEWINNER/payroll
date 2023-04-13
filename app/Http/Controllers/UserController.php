<?php

namespace App\Http\Controllers;
use App\Attendance;
use Illuminate\Http\Request;
use App\Store;
use App\User;
use App\Schedule;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    //

    public function index(Request $request)
    {
        $storeController = new StoreController;
        $from = $request->from;
        $to = $request->to;
        $date_range = $storeController->dateRange($from,$to);
        // dd($date_range);
        $client = new \GuzzleHttp\Client();
        $personnel = [];
        $response = Attendance::groupBy('store')->selectRaw('store')->where('store','!=',null)->where('store','!=','undefined')->get();
        $attendances = [];
        $schedules = [];
        //dd($request->store); 
        if ($request->store != null) {
            $personnelRequest = $client->post('https://sparkle-time-keep.herokuapp.com/api/store/personnel', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode([
                    'store' => $request->store,
                ])
            ]);
            $personnel = json_decode($personnelRequest->getBody());
            // dd($personnel);
            $personnelData= collect($personnel)->pluck('_id')->toArray();
            $attendances = Attendance::whereIn('emp_id',$personnelData)->whereBetween('date',[$from,$to])->get();
            $schedules = Schedule::whereIn('emp_id',$personnelData)->whereBetween('date',[$from,$to])->get();

        }
        $storeData = $request->store;
        return view(
            'users.view',
            array(
                'stores' => $response,
                'storeData' => $storeData,
                'personnels' => $personnel,
                'to' => $to,
                'from' => $from,
                'attendances' => $attendances,
                'date_range' => $date_range,
                'schedules' => $schedules,
            )
        );
    }
    public function changepass(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $user = User::where('id',auth()->user()->id)->first();
        $user->password = bcrypt($request->password);
        $user->save();
        Alert::success('Successfully Change Password')->persistent('Dismiss');
        return back();
    }

    public function getrecord(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|confirmed',
        ]);
        $id = $request->id;
        $from = $request->from;
        $to = $request->to;
        $date_range =  $this->dateRange($from,$to);
        $userAttendance = Attendance::where('emp_id',$id)->whereBetween('date',[$from,$to])->orderBy('date','desc')->get();
        $userSchedule = Schedules::where('emp_id',$id)->whereBetween('date',[$from,$to])->orderBy('date','desc')->get();
        return view('users.view',
            array(
                'attendance' => $userAttendance,
                'schedule' => $userSchedule,
                'from' => $from,
                'to' => $to,
                'date_range' => $date_range,
            )
        );
    }

    public function dateRange( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
        $dates = [];
        $current = strtotime( $first );
        $last = strtotime( $last );
    
        while( $current <= $last ) {
    
            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
        }
    
        return $dates;
    }
}
