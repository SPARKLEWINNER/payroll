<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends Controller
{
    //
    
    public function index()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://sparkle-time-keep.herokuapp.com/api/users/company');
        $stores = json_decode((string) $response->getBody(), true);
        $data = [];
        foreach($stores as $key => $store)
        {
            $employeesJson = $client->request('POST', 'https://sparkle-time-keep.herokuapp.com/api/store/personnel', [
                'json' => [
                    'store' => $store,
                ]
            ]);
            $employees = json_decode((string) $employeesJson->getBody(), true);
            $data[$key] = $employees;
         
        }
        dd($data);
    }
}
