<?php

namespace App\Http\Controllers;
use App\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    //
    public function index(Request $request){

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://sparkle-time-keep.herokuapp.com/api/users/company');
        $stores = json_decode((string) $response->getBody(), true);
        $Nstores = Salary::get()->pluck('store')->toArray();
        $stores = \array_diff($stores,$Nstores);
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
}
