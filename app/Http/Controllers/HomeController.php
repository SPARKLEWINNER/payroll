<?php

namespace App\Http\Controllers;
use App\User;
use App\Group;
use App\Store;
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
        $stores = Store::get();
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://sparkle-time-keep.herokuapp.com/api/users/company');
        $companies = json_decode((string) $response->getBody(), true);
        // dd($companies);

        return view('home',
        array(
            'users' => $users,
            'groups' => $groups,
            'stores' => $stores,
            'companies' => $companies
        )
    );
    }
}
