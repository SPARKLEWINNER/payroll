<?php

namespace App\Http\Controllers;
use App\User;
use App\Group;
use App\Store;
use App\Attendance;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::get();
        $groups = Group::get();
        $stores = Attendance::groupBy('store')->selectRaw('store')->where('store','!=',null)->get();
        $attendances = Attendance::orderBy('id','desc')->take(1000)->get();
        $employees = Attendance::groupBy('emp_id','emp_name','status')->selectRaw('emp_name')->where('store','!=',null)->where('date',date('Y-m-d'))->get();
        // dd($companies);

        return view('home',
        array(
            'users' => $users,
            'groups' => $groups,
            'stores' => $stores,
            'attendances' => $attendances,
            'employees' => $employees,
            
        )
    );
    }
}
