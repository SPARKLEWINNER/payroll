<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Holiday;
use App\Payroll;
use App\PayrollInfo;
use App\SssTable;
use App\Store;
use App\Group;
use App\PayrollLog;
use App\Rates;
use PDF;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $payroll_last = "";
        $printReport = new StoreController;
        $storeData = $request->store;
        $from = $request->from;
        $to = $request->to;
        $start_month = date('2019-m-d', strtotime($from));
        $end_month = date('2019-m-d', strtotime($to));
        $holidays = [];
        $date_range =  $printReport->dateRange($from, $to);
        $stores = Attendance::groupBy('store')->with('payroll')->selectRaw('store')->where('store', '!=', null)->get();
        $employees = [];
        $schedulesData = [];
        $rate = 0;
        $sssTable = SssTable::orderBy('id', 'desc')->get();
        if ($request->store) {
            $payroll = Payroll::where('store', $request->store)->orderBy('payroll_from', 'desc')->first();
            if ($payroll != null) {
                $payroll_last = $payroll->payroll_to;
            }
            $holidays = Holiday::where(function ($query) use ($start_month, $end_month) {
                $query->where('status', 'Permanent')->whereBetween('holiday_date', [$start_month, $end_month]);
            })
                ->orWhere(function ($query) use ($from, $to) {
                    $query->where('status', null)->whereBetween('holiday_date', [$from, $to]);
                })
                ->orderBy('holiday_date', 'asc')->get();
            $employees = Attendance::with(['attendances' => function ($q) use ($from, $to) {
                $q->whereBetween('date', [$from, $to]);
            }])->with(['schedules' => function ($q) use ($from, $to) {
                $q->whereBetween('date', [$from, $to])->orderBy('id', 'desc');
            }])->groupBy('emp_id', 'emp_name')->with('rate')->select('emp_id', 'emp_name')->where('store', $request->store)->orderBy('emp_name', 'asc')->get();
            $rateStore = Rates::where('store', $request->store)->first();
            if ($rateStore == null) {
                $group_id = Store::where('store', $request->store)->first();
                if ($group_id == null) {
                    $rate = null;
                } else {
                    $rate = Rates::where('uid', $group_id->group_id)->first();
                    if ($rate != null) {
                        $rate = $rate->daily;
                    }
                }
            } else {
                $rate = $rateStore->daily;
            }
        }
        return view(
            'generate-payroll',
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
                'payroll_last' => $payroll_last,
            )
        );
    }
    public function payrolls()
    {
        $stores = Attendance::groupBy('store')->with('payroll')->selectRaw('store')->where('store', '!=', null)->get();
        $payrolls = Payroll::with('informations', 'user')->orderBy('payroll_to', 'desc')->get();
        return view(
            'payrolls',
            array(
                'payrolls' => $payrolls,
                'stores' => $stores,

            )
        );
    }
    public function payroll($id)
    {
        $payroll = Payroll::with('informations', 'user')->where('id', $id)->first();
        $pdf = PDF::loadView('payroll_pdf', array(
            'payroll' => $payroll,
        ))->setPaper('legal', 'landscape');
        return $pdf->stream(date('mm-dd-yyyy') . '-payroll-' . $payroll->store . '.pdf');
    }
    public function billing($id)
    {
        $payroll = Payroll::with('informations', 'user')->where('id', $id)->first();
        $pdf = PDF::loadView('billing', array(
            'payroll' => $payroll,
        ))->setPaper('legal', 'landscape');
        return $pdf->stream(date('m-d-Y') . '-billing-' . $payroll->store . '.pdf');
    }

    public function test()
    {
        $pdf = PDF::loadView('test', array())->setPaper('legal', 'landscape');
        return $pdf->stream(date('m-d-Y') . '.pdf');
    }

    public function getRates($id)
    {
        $findRates = Rates::where('uid', $id)->first();
        if (empty($findRates)) {
            $findRates = Rates::where('uid', 1)->first();
        } else {
            $findRates = Rates::where('uid', $id)->first();
        }
        return [
            'status' => 'success',
            'data' => $findRates
        ];
    }
    public function getRatesStore(Request $request)
    {
        $findRates = Rates::where('store', $request->store)->first();
        if (empty($findRates)) {
            $findRates = Rates::where('uid', 1)->first();
        } else {
            $findRates = Rates::where('store', $request->store)->first();
        }
        return [
            'status' => 'success',
            'data' => $findRates
        ];
    }

    public function setRates(Request $request)
    {
        /*dd($request->rateid);*/
        $new_rates = Rates::updateOrCreate(
            ['uid' => $request->rateid],
            [
                'uid' => $request->rateid,
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
        $new_rates = Rates::updateOrCreate(
            ['store' => $request->store],
            [
                'uid' => $request->rateid,
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
    public function save(Request $request)
    {
        $payrollExist = Payroll::where('payroll_from', $request->from)->where('payroll_to', $request->to)->where('store', $request->store)->first();
        if ($payrollExist) {
            Alert::warning('Please Go to Generated Payrolls, it already save.')->persistent('Dismiss');
            return redirect('/payrolls');
        }

        $payroll = new Payroll;
        $payroll->date_generated = date('Y-m-d');
        $payroll->generated_by = auth()->user()->id;
        $payroll->payroll_from = $request->from;
        $payroll->payroll_to = $request->to;
        $payroll->store = $request->store;
        $payroll->save();

        foreach ($request->emp_id as $key => $emp_id) {
            $payroll_info = new PayrollInfo;
            $payroll_info->payroll_id = $payroll->id;
            $payroll_info->employee_id = $emp_id;
            if (isset($request->emp_name[$key])) {
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
                $payroll_info->total_deductions = $request->total_deduction[$key];
                $payroll_info->other_deductions = $request->other_deduction[$key];
                $payroll_info->net_pay = $request->net[$key];
                $payroll_info->sss_er = $request->sss_er[$key];
                $payroll_info->save();
            }
        }
        Alert::success('Successfully Save to Payroll')->persistent('Dismiss');
        return redirect('/payrolls');
    }
    public function editPayroll(Request $request, $id)
    {
        $payroll = Payroll::where('id', $id)->with('informations', 'user')->first();
        return view(
            'editPayroll',
            array(
                'payroll' => $payroll,
            )
        );
    }
    public function saveEditPayroll(Request $request, $id)
    {
        $sss = 0;
        $philhealth = 0;
        $pagibig = 0;
        $sss_er = 0;
        $payroll = PayrollInfo::where('id', $id)->first();
        $old_data = $payroll;
        $days_work = $request->days_work;
        $daily_rate = $request->daily_rate;
        $hour_rate = $request->hour_rate;
        $basic_pay = $hour_rate * $request->hours_work;
        $tardy_amount = ($hour_rate / 60) * $request->hours_tardy;
        $special_holiday_amount = $request->special_holiday * 1.3;
        $legal_holiday_amount = $request->legal_holiday * 2;
        $overtime_amount = ($hour_rate * 1.25) * $request->overtime;
        $nightdiff_amount = ($hour_rate * .1) * $request->night_diff;
        $gross_pay = $basic_pay - $tardy_amount + $overtime_amount + $nightdiff_amount + $special_holiday_amount + $legal_holiday_amount;
        $other_income_non_tax = $request->other_income_non_taxable;
        $other_deduction = $request->other_deduction;
        $sssTable = SssTable::where('from_range', '<', $gross_pay)->first();

        if ($basic_pay >= 1) {

            $sssData = $sssTable->where('from_range', '<', $gross_pay)->first();
            if ($sssData != null) {
                $sss = $sssData->ee;
                $sss_er = $sssData->er;
            }
            $philhealth = ((($daily_rate * 313 * .04) / 12) / 2);
            $pagibig = 100.00;
        }
        $total_deduction = $sss + $philhealth + $pagibig + $other_deduction;
        $net = $gross_pay - $total_deduction + $other_income_non_tax;
        $payroll->daily_rate = $daily_rate;
        $payroll->hour_rate = $hour_rate;
        $payroll->days_work = $days_work;
        $payroll->hours_work = $request->hours_work;
        $payroll->basic_pay = $basic_pay;
        $payroll->hours_tardy = $request->hours_tardy;
        $payroll->hours_tardy_basic = $tardy_amount;
        $payroll->overtime = $request->overtime;
        $payroll->amount_overtime = $overtime_amount;
        $payroll->special_holiday = $request->special_holiday;
        $payroll->amount_special_holiday = $special_holiday_amount;
        $payroll->legal_holiday = $request->legal_holiday;
        $payroll->amount_legal_holiday = $legal_holiday_amount;
        $payroll->night_diff = $request->night_diff;
        $payroll->amount_night_diff = $nightdiff_amount;
        $payroll->gross_pay = $gross_pay;
        $payroll->other_income_non_taxable = $other_income_non_tax;
        $payroll->sss_contribution = $sss;
        $payroll->nhip_contribution = $philhealth;
        $payroll->hdmf_contribution = $pagibig;
        $payroll->total_deductions = $total_deduction;
        $payroll->other_deductions = $other_deduction;
        $payroll->net_pay = $net;
        $payroll->save();
        $newdata = $payroll;
        $log = new PayrollLog;
        $log->table = "payroll_infos";
        $log->action = "Update";
        $log->table_id = $id;
        $log->data_from = $old_data;
        $log->data_to = $newdata;
        $log->edit_by = auth()->user()->id;
        $log->save();
        Alert::success('Successfully Update')->persistent('Dismiss');
        return back();
    }
}
