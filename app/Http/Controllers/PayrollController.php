<?php

namespace App\Http\Controllers;
use App\Attendance;
use App\Holiday;
use App\Payroll;
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
            }])->groupBy('emp_id','emp_name')->select('emp_id','emp_name')->where('store',$request->store)->orderBy('emp_name','asc')->get();
         
        }
        // dd($employees);
        return view('generate-payroll',
            array(
                'stores' => $stores,
                'holidays' => $holidays,
                'storeData' => $storeData,
                'from' => $from,
                'to' => $to,
                'date_range' => $date_range,
                'employees' => $employees,
                'schedulesData' => $schedulesData,
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
}
