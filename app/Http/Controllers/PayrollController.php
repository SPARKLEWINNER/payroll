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
use App\PayrollAllowance;
use App\PayrollDeduction;
use App\PayrollGrossAllowance;
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
        $rates = [];
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
                        $rates = $rate;
                        $rate = $rate->daily;
                    }
                }
            } else {
                $rates = $rateStore;
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
                'rates' => $rates,
                'payroll_last' => $payroll_last,
            )
        );
    }
    public function payrolls()
    {
        $stores = Attendance::groupBy('store')->with('payroll')->selectRaw('store')->where('store', '!=', null)->get();
        $payrolls = Payroll::with('informations', 'user')->orderBy('payroll_to', 'desc')->where('status', NULL)->get();
        return view(
            'payrolls',
            array(
                'payrolls' => $payrolls,
                'stores' => $stores,

            )
        );
    }
    public function savePayrolls()
    {
        $stores = Attendance::groupBy('store')->with('payroll')->selectRaw('store')->where('store', '!=', null)->get();
        $payrolls = Payroll::with('informations', 'user')->orderBy('payroll_to', 'desc')->where('status', "Save")->get();
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
                'status' => $request->status,
                'allowance' => $request->allowance
            ]
        );

        return redirect()->back()->with('message', 'Save successful!');
    }
    public function setStoreRates(Request $request)
    {
        $late = $request->late;
        $undertime = $request->undertime;
        if ($request->late == "on") {
            $late = null;
        } else {
            $late = 1;
        }
        if ($request->undertime == "on") {
            $undertime = null;
        } else {
            $undertime = 1;
        }
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
                'store' => $request->store,
                'allowance' => $request->allowance,
                'late' => $late,
                'undertime' => $undertime,
            ]
        );
        return redirect()->back()->with('message', 'Save successful!');
    }
    public function save(Request $request)
    {
        $formattedRequest = $request->input();

        $payrollFrom = null;
        $payrollTo = null;
        $store = null;
        
        $generatedBy = $request->id ?? $request->generatedby ?? null;
        $generatedByName = $request->generatedbyname ?? null;        

        // Extract from, to, and store from one of the employees if not provided directly
        if (!isset($request->from) || !isset($request->to) || !isset($request->store)) {
            foreach ($formattedRequest as $key => $detail) {
                if (is_array($detail) && isset($detail['from']) && isset($detail['to']) && isset($detail['store'])) {
                    $payrollFrom = date('Y-m-d', strtotime($detail['from']));
                    $payrollTo = date('Y-m-d', strtotime($detail['to']));
                    $store = $detail['store'];
        
                    $generatedBy = $generatedBy ?? $detail['id'] ?? null;
                    $generatedByName = $generatedByName ?? $detail['generatedbyname'] ?? null;
                    break;
                }
            }
        } else {
            $payrollFrom = date('Y-m-d', strtotime($request->from));
            $payrollTo = date('Y-m-d', strtotime($request->to));
            $store = $request->store;
        }

        if (!$payrollFrom || !$payrollTo || !$store) {
            return response()->json(['message' => 'Missing payroll date or store information'], 400);
        }

        $payrollExist = Payroll::where('payroll_from', $payrollFrom)
            ->where('payroll_to', $payrollTo)
            ->where('store', $store)
            ->first();

        if ($payrollExist) {
            $payroll = $payrollExist;
            if (is_numeric($generatedBy)) {
                $payroll->generated_by = $generatedBy;
            } elseif ($generatedByName) {
                $payroll->generated_by_name = $generatedByName;
            }
            $payroll->save();

            PayrollInfo::where('payroll_id', $payroll->id)->delete();
        } else {
            $payroll = new Payroll;
            $payroll->date_generated = date('Y-m-d');
            if (is_numeric($generatedBy)) {
                $payroll->generated_by = $generatedBy;
            } elseif ($generatedByName) {
                $payroll->generated_by_name = $generatedByName;
            }
            $payroll->payroll_from = $payrollFrom;
            $payroll->payroll_to = $payrollTo;
            $payroll->store = $store;
            $payroll->save();
        }

        // Detect if the 'details' key exists or if the structure is different
        if (isset($formattedRequest['details']) && is_array($formattedRequest['details'])) {
            $details = $formattedRequest['details'];
        } else {
            $details = [];
            foreach ($formattedRequest as $key => $detail) {
                if (is_array($detail) && isset($detail['emp_id'])) {
                    $details[] = [
                        'employeeid' => $detail['emp_id'],
                        'employeename' => $detail['emp_name'],
                        'rate' => $detail['rate'],
                        'hour_rate' => $detail['daily_rate'],
                        'days_work' => $detail['days_work'],
                        'hours_work' => $detail['working_hours'],
                        'basic_pay' => $detail['basic_pay'],
                        'hours_tardy' => $detail['hours_tardy'],
                        'tardy_amount' => $detail['tardy_amount'],
                        'overtime' => $detail['overtime'],
                        'overtime_amount' => $detail['overtime_amount'],
                        'special_holiday' => $detail['special_holiday'],
                        'special_holiday_amount' => $detail['special_holiday_amount'],
                        'legal_holiday' => $detail['legal_holiday'],
                        'legal_holiday_amount' => $detail['legal_holiday_amount'],
                        'night_diff' => $detail['night_diff'],
                        'nightdiff_amount' => $detail['nightdiff_amount'],
                        'gross_pay' => $detail['gross_pay'],
                        'other_income_non_tax' => $detail['other_income_non_tax'],
                        'sss' => $detail['sss'],
                        'philhealth' => $detail['philhealth'],
                        'pagibig' => $detail['pagibig'],
                        'total_deduction' => $detail['total_deduction'],
                        'other_deduction' => $detail['other_deduction'],
                        'net' => $detail['net'],
                        'sss_er' => $detail['sss_er']
                    ];
                }
            }
        }

        foreach ($details as $detail) {
            $payroll_info = new PayrollInfo;
            $payroll_info->payroll_id = $payroll->id;
            $payroll_info->employee_id = $detail['employeeid'];
            $payroll_info->employee_name = $detail['employeename'];
            $payroll_info->daily_rate = $detail['rate'];
            $payroll_info->hour_rate = $detail['hour_rate'];
            $payroll_info->days_work = $detail['days_work'];
            $payroll_info->hours_work = $detail['hours_work'];
            $payroll_info->basic_pay = $detail['basic_pay'];
            $payroll_info->hours_tardy = $detail['hours_tardy'];
            $payroll_info->hours_tardy_basic = $detail['tardy_amount'];
            $payroll_info->overtime = $detail['overtime'];
            $payroll_info->amount_overtime = $detail['overtime_amount'];
            $payroll_info->special_holiday = $detail['special_holiday'];
            $payroll_info->amount_special_holiday = $detail['special_holiday_amount'];
            $payroll_info->legal_holiday = $detail['legal_holiday'];
            $payroll_info->amount_legal_holiday = $detail['legal_holiday_amount'];
            $payroll_info->night_diff = $detail['night_diff'];
            $payroll_info->amount_night_diff = $detail['nightdiff_amount'];
            $payroll_info->gross_pay = $detail['gross_pay'];
            $payroll_info->other_income_non_taxable = $detail['other_income_non_tax'];
            $payroll_info->sss_contribution = $detail['sss'];
            $payroll_info->nhip_contribution = $detail['philhealth'];
            $payroll_info->hdmf_contribution = $detail['pagibig'];
            $payroll_info->total_deductions = $detail['total_deduction'];
            $payroll_info->other_deductions = $detail['other_deduction'];
            $payroll_info->net_pay = $detail['net'];
            $payroll_info->sss_er = $detail['sss_er'];
            $payroll_info->save();
        }
        return response()->json(['message' => 'success'], 200);
    }
    public function editPayroll(Request $request, $id)
    {
        $payroll = Payroll::where('id', $id)->with('informations.payroll_allowances', 'user')->first();
        $payrolls = Payroll::where('payroll_from', $payroll->payroll_from)->where('id', '!=', $id)->get();
        return view(
            'editPayroll',
            array(
                'payroll' => $payroll,
                'payrolls' => $payrolls,
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
        $other_income = $request->other_income_non_taxable;
        $other_deduction = $request->other_deduction;
        $daily_rate = $request->daily_rate;
        $hour_rate = $request->hour_rate;
        $basic_pay = $hour_rate * $request->hours_work;
        $tardy_amount = ($hour_rate / 60) * $request->hours_tardy;
        $special_holiday_amount = $request->special_holiday * .3 * $daily_rate;
        $legal_holiday_amount = $request->legal_holiday * $daily_rate;
        $overtime_amount = ($hour_rate * 1.25) * $request->overtime;
        $nightdiff_amount = ($hour_rate * .1) * $request->night_diff;
        $gross_pay = $basic_pay - $tardy_amount + $overtime_amount + $nightdiff_amount + $special_holiday_amount + $legal_holiday_amount + $other_income;
        $other_income_non_tax = $request->other_income_non_taxable;
        $other_deduction = $request->other_deduction;
/*        $sssTable = SssTable::where('from_range', '<=', $gross_pay)->where('to_range', '>=', $gross_pay)->orderBy('id', 'desc')->first();
*/
        if ($gross_pay >= 1) {
            $sssData = SssTable::where('from_range', '<=', $gross_pay)->where('to_range', '>=', $gross_pay)->first();
            if ($sssData != null) {
                $sss = $sssData->ee;
                $sss_er = $sssData->er;
            }
            else {
                $sss = 0;
            }
            $philhealth = ((($daily_rate * 313 * .05) / 12) / 2);
        }
        else {
            $sss = 0;
        }
        $total_deduction = $sss + $payroll->nhip_contribution + $payroll->hdmf_contribution + $other_deduction;
        $net = $gross_pay - $total_deduction;
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
         $payroll->sss_contribution = $sss;
        $payroll->gross_pay = $gross_pay;
        $payroll->other_income_non_taxable = $other_income_non_tax;
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
    public function transferPayroll(Request $request, $id)
    {
        // dd($id);
        $payroll = PayrollInfo::where('id', $id)->first();
        $old_data = $payroll;
        $payroll->payroll_id = $request->store;
        $payroll->save();
        $log = new PayrollLog;
        $log->table = "payroll_infos";
        $log->action = "Update";
        $log->table_id = $id;
        $log->data_from = $old_data;
        $log->data_to = $payroll;
        $log->edit_by = auth()->user()->id;
        $log->save();
        Alert::success('Successfully Transfered')->persistent('Dismiss');
        return back();
    }
    public function removePayroll(Request $request)
    {
        // dd($id);
        $payroll = PayrollInfo::where('id', $request->id)->first();
        $log = new PayrollLog;
        $log->table = "payroll_infos";
        $log->action = "Delete";
        $log->table_id = $request->id;
        $log->data_from = $payroll;
        $log->data_to = "";
        $log->edit_by = auth()->user()->id;
        $log->save();

        $payroll->delete();
        return "success";
    }
    public function savePayroll(Request $request)
    {
        // dd($id);
        $payroll = Payroll::where('id', $request->id)->first();
        $old_data = $payroll;
        $payroll->status = "Save";
        $payroll->save();
        $log = new PayrollLog;
        $log->table = "payrolls";
        $log->action = "Update";
        $log->table_id = $request->id;
        $log->data_from = $old_data;
        $log->data_to = $payroll;
        $log->edit_by = auth()->user()->id;
        $log->save();

        return "success";
    }
    public function editGovernment(Request $request, $id)
    {
        $payroll = PayrollInfo::where('id', $id)->first();
        $old_data = $payroll;
        $payroll->sss_contribution = $request->sss_contribution;
        $payroll->nhip_contribution = $request->nhip_contribution;
        $payroll->hdmf_contribution = $request->hdmf_contribution;
        $payroll->total_deductions = $request->sss_contribution + $request->nhip_contribution + $request->hdmf_contribution + $payroll->other_deductions;
        $payroll->net_pay = $payroll->gross_pay - $payroll->total_deductions;
        $payroll->save();
        $log = new PayrollLog;
        $log->table = "payroll_infos";
        $log->action = "Update";
        $log->table_id = $id;
        $log->data_from = $old_data;
        $log->data_to = $payroll;
        $log->edit_by = auth()->user()->id;
        $log->save();
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function deletePayroll(Request $request)
    {
        $payroll = Payroll::where('id', $request->id)->first();
        $log = new PayrollLog;
        $log->table = "payroll_infos";
        $log->action = "Delete";
        $log->table_id = $request->id;
        $log->data_from = $payroll;
        $log->data_to = "";
        $log->edit_by = auth()->user()->id;
        $log->save();

        $payroll->delete();
        return "success";
    }
    public function payslips(Request $request)
    {
        $cutoffs = Payroll::groupBy('payroll_from', 'payroll_to')->select('payroll_from', 'payroll_to')->where('status', '!=', null)->orderBy('payroll_to', 'desc')->get();
        $stores = Payroll::groupBy('store')->select('store')->where('status', '!=', null)->get();
        $payrollsInfo = [];
        if ($request->store) {
            $payroll = Payroll::with('informations')->where('store', $request->store)->where('payroll_from', $request->from)->first();
            if ($payroll != null) {
                $payrollsInfo = $payroll->informations;
            }
        }

        return view(
            'payslips',
            array(
                'stores' => $stores,
                'cutoffs' => $cutoffs,
                'payrollsInfo' => $payrollsInfo,
                'payrolldate' => $request->from,
                'storeData' => $request->store,
            )
        );
    }
    public function payslip(Request $request, $id)
    {

        $payroll = PayrollInfo::with('payroll', 'payroll_allowances')->where('id', $id)->first();
        $customPaper = array(0, 0, 360, 400);
        $pdf = PDF::loadView('payslip', array(
            'payroll' => $payroll,
        ))->setPaper($customPaper);
        return $pdf->stream(date('mm-dd-yyyy') . '-payslip-' . $payroll->employee_name . '.pdf');
    }
    public function payslips_all(Request $request)
    {
        // dd($request->all());
        $payrollsInfo = [];
        if ($request->store) {
            $payroll = Payroll::with('informations')->where('store', $request->store)->where('payroll_from', $request->from)->first();
            if ($payroll != null) {
                $payrollsInfo = $payroll->informations;
            }
        }
        $customPaper = array(0, 0, 360, 400);
        $pdf = PDF::loadView('payslips_all', array(
            'payrollsInfo' => $payrollsInfo,
        ))->setPaper($customPaper);
        return $pdf->stream(date('mm-dd-yyyy') . '-payslips.pdf');
    }
    public function deductionIncome(Request $request, $id)
    {
        $payroll_info = PayrollInfo::findOrfail($id);
        $other_deduc = 0;
        if($request->deduction_name != null)
        {
        foreach ($request->deduction_name as $key => $name) {
            $allowance = new PayrollDeduction;
            $allowance->name = $name;
            $allowance->amount = $request->deduction_amount[$key];
            $allowance->payroll_info_id = $id;
            $allowance->save();
            $other_deduc = $other_deduc + $request->deduction_amount[0];
            $log = new PayrollLog;
            $log->table = "payroll_deductions";
            $log->action = "Create";
            $log->table_id = $allowance->id;
            $log->data_from = "";
            $log->data_to = $allowance;
            $log->edit_by = auth()->user()->id;
            $log->save();
        }
    }
        $other_deductions = $payroll_info->other_deductions;
        $payroll_info->other_deductions = $payroll_info->other_deductions+$other_deduc;
        $payroll_info->total_deductions = $payroll_info->total_deductions + $other_deduc;
        $payroll_info->net_pay = $payroll_info->net_pay - ($other_deduc);
        $payroll_info->save();
        Alert::success('Successfully Add Deduction')->persistent('Dismiss');
        return back();
    }
    public function deleteDeduction($deductionId)
    {
        $deduction = PayrollDeduction::findOrFail($deductionId);
        $payrollInfoId = $deduction->payroll_info_id;
        $deductionAmount = $deduction->amount;

        $deduction->delete();

        $payroll_info = PayrollInfo::findOrFail($payrollInfoId);
        $payroll_info->other_deductions -= $deductionAmount;
        $payroll_info->total_deductions -= $deductionAmount;
        $payroll_info->net_pay += $deductionAmount;
        $payroll_info->save();

        $log = new PayrollLog;
        $log->table = "payroll_deductions";
        $log->action = "Delete";
        $log->table_id = $deductionId;
        $log->data_from = $deduction;
        $log->data_to = "";
        $log->edit_by = auth()->user()->id;
        $log->save();

        Alert::success('Deduction deleted')->persistent('Dismiss');
        return response()->json(['success' => true]);
    }

    public function additionaIncome(Request $request, $id)
    {
        $payroll_info = PayrollInfo::findOrfail($id);
        $other_income = 0;
        if($request->allowance_name != null)
        {
        foreach ($request->allowance_name as $key => $name) {
            $allowance = new PayrollAllowance;
            $allowance->name = $name;
            $allowance->amount = $request->allowance_amount[$key];
            $allowance->payroll_info_id = $id;
            $allowance->save();
            $other_income = $request->allowance_amount[0];
            $log = new PayrollLog;
            $log->table = "payroll_allowances";
            $log->action = "Create";
            $log->table_id = $allowance->id;
            $log->data_from = "";
            $log->data_to = $allowance;
            $log->edit_by = auth()->user()->id;
            $log->save();
        }
        }
        $other_income_non_taxable = $payroll_info->other_income_non_taxable;
        $payroll_info->gross_pay = $payroll_info->gross_pay + $other_income;
        $payroll_info->other_income_non_taxable = $payroll_info->other_income_non_taxable+$other_income;
        $payroll_info->net_pay = $payroll_info->net_pay + $other_income;
        $payroll_info->save();
        Alert::success('Successfully Add Allowance')->persistent('Dismiss');
        return back();
    }

    public function deleteAllowance($allowanceId)
    {
        $allowance = PayrollAllowance::findOrFail($allowanceId);
        $payrollInfoId = $allowance->payroll_info_id;
        $allowanceAmount = $allowance->amount;

        $allowance->delete();

        $payroll_info = PayrollInfo::findOrFail($payrollInfoId);
        $payroll_info->gross_pay -= $allowanceAmount;
        $payroll_info->other_income_non_taxable -= $allowanceAmount;
        $payroll_info->net_pay -= $allowanceAmount;
        $payroll_info->save();

        $log = new PayrollLog;
        $log->table = "payroll_allowances";
        $log->action = "Delete";
        $log->table_id = $allowanceId;
        $log->data_from = $allowance;
        $log->data_to = "";
        $log->edit_by = auth()->user()->id;
        $log->save();

        Alert::success('Allowance deleted')->persistent('Dismiss'); // Alert message for deletion

        return response()->json(['success' => true]);
    }

    public function additionaGrossIncome(Request $request, $id)
    {

        $payroll_allowances = PayrollGrossAllowance::where('payroll_info_id', $id)->delete();
        $payroll_info = PayrollInfo::findOrfail($id);
        $other_income = 0;
        if($request->allowance_name != null)
        {
        foreach ($request->allowance_name as $key => $name) {
            $allowance = new PayrollGrossAllowance;
            $allowance->name = $name;
            $allowance->amount = $request->allowance_amount[$key];
            $allowance->payroll_info_id = $id;
            $allowance->save();
            $other_income = $other_income + $request->allowance_amount[$key];
            $log = new PayrollLog;
            $log->table = "payroll_gross_allowances";
            $log->action = "Create";
            $log->table_id = $allowance->id;
            $log->data_from = "";
            $log->data_to = $allowance;
            $log->edit_by = auth()->user()->id;
            $log->save();
        }
        }
        $other_income_taxable = $payroll_info->other_income_taxable;
        $payroll_info->other_income_taxable = $other_income;
        $gross_pay = $payroll_info->gross_pay+$other_income_taxable;
        $payroll_info->net_pay = $payroll_info->net_pay + $other_income - $other_income_taxable;
        $payroll_info->save();
        Alert::success('Successfully Add Allowance')->persistent('Dismiss');
        return back();
    }
    public function additionalIncome(Request $request, $id)
    {
        $payroll_info = PayrollInfo::where('payroll_id', $id)->where('employee_id', $request->emp_id)->first();
        $other_income = $request->income;
        $other_income_non_taxable = $payroll_info->other_income_non_taxable;
        $payroll_info->gross_pay = $payroll_info->gross_pay + $request->income;
        $payroll_info->other_income_non_taxable = $other_income;
        $payroll_info->net_pay = $payroll_info->net_pay + $other_income + $other_income_non_taxable;

        $payroll_info->save();
        return 'success';
    }
    public function additionalRemarks(Request $request, $id)
    {
        $payroll = PayrollInfo::where('payroll_id', $id)->where('employee_id', $request->emp_id)->first();
        $payroll->income_remarks = $request->remarks;
        $payroll->save();
        return 'success';  
    }
    public function additionalDeduction(Request $request, $id)
    {
        $payroll_info = PayrollInfo::where('payroll_id', $id)->where('employee_id', $request->emp_id)->first();
        $other_deduc = 0;
        $other_deductions = $payroll_info->other_deductions;
        $payroll_info->other_deductions = $request->deduction;
        $payroll_info->total_deductions = $payroll_info->total_deductions + $request->deduction;
        $payroll_info->net_pay = $payroll_info->net_pay - $request->deduction;
        $payroll_info->save();
        return 'success';  
    }
    public function deductionRemarks(Request $request, $id)
    {
        $payroll = PayrollInfo::where('payroll_id', $id)->where('employee_id', $request->emp_id)->first();
        $payroll->deduction_remarks = $request->remarks;
        $payroll->save();
        return 'success';  
    }
    public function getPayrollInfo(Request $request, $id)
    {
        $payrolls = Payroll::with('informations', 'user')->whereHas('informations', function ($query) use ($id) {
            $query->where('employee_id', $id)->where('display', 1)->where('status', 'Save');
        })->orderBy('payroll_to', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $payrolls,
        ]);
    }
    public function payslipAPI($empid, $id)
    {
        $payroll = PayrollInfo::with('payroll', 'payroll_allowances')->where('payroll_id', $id)->where('employee_id', $empid)->first();
        $customPaper = array(0, 0, 360, 400);
        $pdf = PDF::loadView('payslip', array(
            'payroll' => $payroll,
        ))->setPaper($customPaper);
        return $pdf->stream(date('mm-dd-yyyy') . '-payslip-' . $payroll->employee_name . '.pdf');
    }
    public function updateCompanyAPI()
    {
        $ids = [
            "63c74d095c034a002f2f55dc",
            "64fe7881df5d41002d2c6116",
            "64ffc7d9a95fb6002d76c7f2",
            "6147e9c4a4bda00016e74c1d",
            "6454ce3e7f255d002d3765be",
        ];
        foreach ($ids as $key => $value) {
            $payroll = Attendance::where('emp_id', $value)->update(['store' => 'Starjobs Executive Search Corporation']);;
            
        }
        return "success";
    }
    public function display($id)
    {
        try {
            $payroll_info = Payroll::where('id', $id)->first();
            //dd($payroll_info->display);
            if ($payroll_info->display == 0) {
                $payroll_info->display = 1;
                $payroll_info->save();
                Alert::success('Payslip information integrated with timekeeping.')->persistent('Dismiss');
                return back();           
            }
            else {
                $payroll_info->display = 0;
                $payroll_info->save();
                Alert::success('Payslip removed from timekeeping.')->persistent('Dismiss');
                return back();
            }
            
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            throw $e;
        }
    }
    public function sss_get()
    {
        try {
            $sssTable = SssTable::orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $sssTable,
            ]);
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            throw $e;
        }
    }
    public function sss_post(Request $request)
    {
        try {
            $sssTable = new SssTable;
            $sssTable->from_range = $request->from;
            $sssTable->to_range = $request->to;
            $sssTable->er = $request->er;
            $sssTable->ee = $request->ee;
            $sssTable->save();
            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            throw $e;
        }
    }
    public function sss_update(Request $request, $id)
    {
        try {
            $sssTable = SssTable::where('id', $id)->first();
            $sssTable->from_range = $request->from;
            $sssTable->to_range = $request->to;
            $sssTable->er = $request->er;
            $sssTable->ee = $request->ee;
            $sssTable->save();
            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            throw $e;
        }
    }
}
