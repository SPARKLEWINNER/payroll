<?php

namespace App\Http\Controllers;
use App\Attendance;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    //
    
    public function index(Request $request)
    {
       
        $storeData = $request->store;
        $from = $request->from;
        $to = $request->to;
        $date_range =  $this->dateRange($from,$to);
        $stores = Attendance::groupBy('store')->selectRaw('store')->where('store','!=',null)->get();
        $employees = [];
        if($request->store)
        {
            $employees = Attendance::with(['attendances' => function($q) use ($from,$to)
            {
                $q->whereBetween('date',[$from,$to]);
            }])->with(['schedules' => function($q) use ($from,$to)
            {
                $q->whereBetween('date',[$from,$to])->orderBy('id','desc');
            }])
            ->groupBy('emp_id','emp_name')->select('emp_id','emp_name')->where('store',$request->store)->orderBy('emp_name','asc')->get();
        }
        
        return view('stores',
            array(
                'stores' => $stores,
                'storeData' => $storeData,
                'from' => $from,
                'to' => $to,
                'date_range' => $date_range,
                'employees' => $employees,
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
