<?php

namespace App\Http\Controllers;
use App\Attendance;
use App\Holiday;
use App\Payroll;
use App\SssTable;
use App\Store;
use App\Group;
use App\Rates;
use PDF;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    //
    public function index(Request $request)
    {

      
        // dd($holidays);
        $printReport = new StoreController;
        $storeData = $request->store;
        $from = $request->from;
        $to = $request->to;
        $start_month = date('2019-m-d',strtotime($from));
        $end_month = date('2019-m-d',strtotime($to));
        $holidays = [];
        // dd($holidays);
        $date_range =  $printReport->dateRange($from,$to);
        $stores = Attendance::groupBy('store')->selectRaw('store')->where('store','!=',null)->get();
        $employees = [];
        $schedulesData = [];
        $rate = 0;
        $sssTable = SssTable::orderBy('id','desc')->get();
        if($request->store)
        {
            $holidays = Holiday::where(function ($query) use ($start_month,$end_month)
            {
                $query->where('status','Permanent')->whereBetween('holiday_date',[$start_month, $end_month]);
            })
            ->orWhere(function ($query) use ($from,$to)
            {
                $query->where('status',null)->whereBetween('holiday_date',[$from, $to]);
            })
            ->orderBy('holiday_date','asc')->get();
            $employees = Attendance::with(['attendances' => function($q) use ($from,$to)
            {
                $q->whereBetween('date',[$from,$to]);
            }])->with(['schedules' => function($q) use ($from,$to)
            {
                $q->whereBetween('date',[$from,$to])->orderBy('id','desc');
            }])->groupBy('emp_id','emp_name')->with('rate')->select('emp_id','emp_name')->where('store',$request->store)->orderBy('emp_name','asc')->get();
            $rateStore = Rates::where('store',$request->store)->first();
            if($rateStore == null)
            {
                $group_id = Store::where('store',$request->store)->first();
                if($group_id == null)
                {
                    $rate = [];
                }
                else
                {
                    $rate = Rates::where('uid',$group_id->group_id)->first();
                    $rate = $rate->daily;
                }
            }
            else
            {
                $rate = $rateStore->daily;
            }
         
        }
        // dd($employees);
        return view('generate-payroll',
            array(
                'stores' => $stores,
                'sssTable' => $sssTable,
                'holidays' => $holidays,
                'storeData' => $storeData,
                'from' => $from,
                'to' => $to,
                'date_range' => $date_range,
                'employees' => $employees,
                'schedulesData' => $schedulesData,
                'rate' => $rate,
            )
        );
    }
    public function payrolls()
    {
        $payrolls = Payroll::with('informations','user')->get();
        return view(
            'payrolls',
            array(
                'payrolls' => $payrolls,

            )
        );
    }
    public function payroll($id)
    {
        $payroll = Payroll::with('informations','user')->where('id',$id)->first();
        $pdf = PDF::loadView('payroll_pdf',array(
            'payroll' => $payroll,
        ))->setPaper('legal', 'landscape');
        return $pdf->stream(date('mm-dd-yyyy').'-payroll-'.$payroll->store.'.pdf');
    }
    public function billing($id)
    {
        $payroll = Payroll::with('informations','user')->where('id',$id)->first();
        $pdf = PDF::loadView('billing',array(
            'payroll' => $payroll,
        ))->setPaper('legal', 'landscape');
        return $pdf->stream(date('m-d-Y').'-billing-'.$payroll->store.'.pdf');
    }

    public function test()
    {
        $pdf = PDF::loadView('test',array(
        ))->setPaper('legal', 'landscape');
        return $pdf->stream(date('m-d-Y').'.pdf');
    }

    public function getRates($id)
    {
        $findRates = Rates::where('uid', $id)->first();
        if (empty($findRates)) {
            $findRates = Rates::where('uid', 1)->first();
        }
        else {
            $findRates = Rates::where('uid', $id)->first();
        }
        return ['status' => 'success',
                'data' => $findRates];
    }
    public function getRatesStore(Request $request)
    {
        $findRates = Rates::where('store', $request->store)->first();
        if (empty($findRates)) {
            $findRates = Rates::where('uid', 1)->first();
        }
        else {
            $findRates = Rates::where('store', $request->store)->first();
        }
        return ['status' => 'success',
                'data' => $findRates];
    }

    public function setRates(Request $request)
    {
        /*dd($request->rateid);*/
        $new_rates = Rates::updateOrCreate(['uid' => $request->rateid],
            [
                'uid' =>$request->rateid,
                'daily' => $request->dailyRate, 
                'nightshift' => $request->nightshift,
                'restday' => $request->restday,
                'restdayot' => $request->restdayot, 
                'holiday' => $request->holidayRate,
                'holidayot' => $request->holidayot,
                'holidayrestday' => $request->holidayrestday,
                'holidayrestdayot' => $request->holidayrestdayot,
                'specialholiday' => $request->specialholiday,
                'specialholidayot' => $request->specialholidayot,
                'specialholidayrestday' => $request->specialholidayrestday,
                'specialholidayrestdayot' => $request->specialholidayrestdayot,
                'sss' => $request->sss,
                'philhealth' => $request->philhealth,
                'pagibig' => $request->pagibig,
                'overtime' => $request->overtime,
                'status' => $request->status
            ]
        );     
        return redirect()->back()->with('message', 'Save successful!');
    }
    public function setStoreRates(Request $request)
    {
        $new_rates = Rates::updateOrCreate(['store' => $request->store],
            [
                'uid' =>$request->rateid,
                'daily' => $request->dailyRate, 
                'nightshift' => $request->nightshift,
                'restday' => $request->restday,
                'restdayot' => $request->restdayot, 
                'holiday' => $request->holidayRate,
                'holidayot' => $request->holidayot,
                'holidayrestday' => $request->holidayrestday,
                'holidayrestdayot' => $request->holidayrestdayot,
                'specialholiday' => $request->specialholiday,
                'specialholidayot' => $request->specialholidayot,
                'specialholidayrestday' => $request->specialholidayrestday,
                'specialholidayrestdayot' => $request->specialholidayrestdayot,
                'sss' => $request->sss,
                'philhealth' => $request->philhealth,
                'pagibig' => $request->pagibig,
                'overtime' => $request->overtime,
                'status' => $request->status,
                'store' => $request->store
            ]
        );     
        return redirect()->back()->with('message', 'Save successful!');
    }
}
