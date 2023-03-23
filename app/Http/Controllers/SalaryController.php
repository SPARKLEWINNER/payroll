<?php

namespace App\Http\Controllers;
use App\Salary;
use App\Attendance;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SalaryController extends Controller
{
    //
    public function index(Request $request){

        $client = new \GuzzleHttp\Client();
        $Nstores = Salary::get()->pluck('store')->toArray();
        $stores = Attendance::groupBy('store')->selectRaw('store')->where('store','!=',null)->whereNotIn('store',$Nstores )->get();
        // dd($array);
        $salaries = Salary::get();

        
        return view(
            'salaries',
            array(
                'salaries' => $salaries,
                'stores' => $stores,
                'Nstores' => $Nstores,

            )
        );
    }
    public function create (Request $request)
    {

        $salary = new Salary;
        $salary->store = $request->stores;
        $salary->rate = $request->rate;
        $salary->amount = $request->allowance_amount;
        $salary->less_to_billing = $request->less_to_billing;
        $salary->deducted_on_payroll = $request->deducted_on_payroll;
        $salary->user_id = auth()->user()->id;
        $salary->save();

        Alert::success('Successfully Save Salary')->persistent('Dismiss');
        return back();
    }
}
