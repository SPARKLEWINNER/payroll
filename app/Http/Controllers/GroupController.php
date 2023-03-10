<?php

namespace App\Http\Controllers;
use App\Group;
use App\Store;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
class GroupController extends Controller
{
    //

    public function index()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://sparkle-time-keep.herokuapp.com/api/users/company');
        $stores = json_decode((string) $response->getBody(), true);
        $Nstores = Store::get()->pluck('store')->toArray();
        // dd($Nstores);
        $stores = \array_diff($stores,$Nstores);
        // dd($array);
        $groups = Group::with('stores')->get();

        return view(
            'groups',
            array(
                'groups' => $groups,
                'stores' => $stores,
                'Nstores' => $Nstores,

            )
        );
    }

    public function new(Request $request)
    {
        $group = new Group;
        $group->name = $request->group;
        $group->user_id = auth()->user()->id;
        $group->save();
        // dd($group->id);
        foreach ($request->stores as $store) {
                $store_save = new Store;
                $store_save->group_id = $group->id;
                $store_save->store = $store;
                $store_save->user_id = auth()->user()->id;
                $store_save->save();
        }
        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
    

}
