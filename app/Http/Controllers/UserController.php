<?php

namespace App\Http\Controllers;
use App\Attendance;
use Illuminate\Http\Request;
use App\Store;
use App\User;
use RealRashid\SweetAlert\Facades\Alert;

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
    public function changepass(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $user = User::where('id',auth()->user()->id)->first();
        $user->password = bcrypt($request->password);
        $user->save();
        Alert::success('Successfully Change Password')->persistent('Dismiss');
        return back();
    }
}
