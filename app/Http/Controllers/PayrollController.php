<?php

namespace App\Http\Controllers;
use App\Attendance;
use App\Holiday;
use App\Payroll;
use App\PayrollInfo;
use App\SssTable;
use App\Store;
use App\Group;
use App\Rates;
use PDF;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PayrollController extends Controller
{
    //
    public function index(Request $request)
    {

        $printReport = new StoreController;
        $storeData = $request->store;
        $from = $request->from;
        $to = $request->to;
        $start_month = date('2019-m-d',strtotime($from));
        $end_month = date('2019-m-d',strtotime($to));
        $holidays = [];
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
    public function save (Request $request)
    {
        // dd($request->all());
        $payroll = new Payroll;
        $payroll->date_generated = date('Y-m-d');
        $payroll->generated_by = auth()->user()->id;
        $payroll->payroll_from = $request->from;
        $payroll->payroll_to = $request->to;
        $payroll->store = $request->store;
        $payroll->save();

        foreach($request->emp_id as $key => $emp_id)
        {
            $payroll_info = new PayrollInfo;
            $payroll_info->payroll_id = $payroll->id;
            $payroll_info->employee_id = $emp_id;
            $payroll_info->employee_name = $request->emp_name[$key];
            $payroll_info->daily_rate = $request->rate[$key];
            $payroll_info->hour_rate = $request->daily_rate[$key];
            $payroll_info->days_work = $request->day_works[$key];
            $payroll_info->hours_work = $request->working_hours[$key];
            $payroll_info->basic_pay = $request->basic_pay[$key];
            $payroll_info->hours_tardy = $request->hours_tardy[$key];
            $payroll_info->hours_tardy_basic = $request->tardy_amount[$key];
            $payroll_info->overtime = $request->overtime[$key];
            $payroll_info->amount_overtime = $request->overtime_amount[$key];
            $payroll_info->special_holiday = $request->special_holiday[$key];
            $payroll_info->amount_special_holiday = $request->special_holiday_amount[$key];
            $payroll_info->legal_holiday = $request->legal_holiday[$key];
            $payroll_info->amount_legal_holiday = $request->legal_holiday_amount[$key];
            $payroll_info->night_diff = $request->night_diff[$key];
            $payroll_info->amount_night_diff = $request->nightdiff_amount[$key];
            $payroll_info->gross_pay = $request->gross_pay[$key];
            $payroll_info->other_income_non_taxable = $request->other_income_non_tax[$key];
            $payroll_info->sss_contribution = $request->sss[$key];
            $payroll_info->nhip_contribution = $request->philhealth[$key];
            $payroll_info->hdmf_contribution = $request->pagibig[$key];
            $payroll_info->tax = 0.00;
            $payroll_info->total_deductions = $request->total_deduction[$key];
            $payroll_info->other_deductions = $request->other_deduction[$key];
            $payroll_info->net_pay = $request->net[$key];
            $payroll_info->sss_er = $request->sss_er[$key];
            $payroll_info->save();
        }
        Alert::success('Successfully Save to Payroll')->persistent('Dismiss');
        return back();
    }
}
