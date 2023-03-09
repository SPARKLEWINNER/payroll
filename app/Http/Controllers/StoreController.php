<?php

namespace App\Http\Controllers;

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
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://sparkle-time-keep.herokuapp.com/api/users/company');
        $stores = json_decode((string) $response->getBody(), true);
        $employees = [];
        $schedulesData = [];
        if($request->store)
        {
            $employeesJson = $client->request('POST', 'https://sparkle-time-keep.herokuapp.com/api/store/personnel', [
                        'json' => [
                            'store' => $request->store,
                        ]
            ]);
            $employees = json_decode((string) $employeesJson->getBody(), true);
            $employees = collect($employees)->take(5);
            foreach($employees as $key => $emp)
            {
                $schedulesJson = $client->request('POST', 'https://sparkle-time-keep.herokuapp.com/api/range/schedule/', [
                    'json' => [
                                'id' => $emp['_id'],
                                "from" => $from,
                                "to" => $to
                            ]
                    ]);
                $schedules = json_decode((string) $schedulesJson->getBody(), true);
                // dd($schedules);
                $schedulesData[$key] = $schedules;
               
            }
        }
        
        return view('stores',
            array(
                'stores' => $stores,
                'storeData' => $storeData,
                'from' => $from,
                'to' => $to,
                'date_range' => $date_range,
                'employees' => $employees,
                'schedulesData' => $schedulesData,
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
