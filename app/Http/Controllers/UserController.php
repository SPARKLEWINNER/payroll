<?php

namespace App\Http\Controllers;
use App\Attendance;
use Illuminate\Http\Request;
use App\Store;

class UserController extends Controller
{
    //

    public function index(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $personnel = [];
        $response = Attendance::groupBy('store')->selectRaw('store')->where('store','!=',null)->get();
        $personnelRequest = $client->post('https://sparkle-time-keep.herokuapp.com/api/store/personnel', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode([
                'store' => $request->store,
            ])
        ]);
        $personnel = json_decode($personnelRequest->getBody());
        $storeData = $request->store;
        return view(
            'users',
            array(
                'stores' => $response,
                'storeData' => $storeData,
                'personnels' => $personnel
            )
        );
    }
}
