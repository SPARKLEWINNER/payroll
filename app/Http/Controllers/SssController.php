<?php

namespace App\Http\Controllers;
use App\SssTable;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
class SssController extends Controller
{
    //
    public function index()
    {
        $sssTable = SssTable::orderBy('id', 'asc')->get();

        return view('sss.view',
        array(
            'header' => 'settings',
            'sss' => $sssTable,
            
        ));
    }

    public function create(Request $request)
    {
       try {
            $sssTable = new SssTable;
            $sssTable->from_range = $request->from;
            $sssTable->to_range = $request->to;
            $sssTable->er = $request->er;
            $sssTable->ee = $request->ee;
            $sssTable->save();
            Alert::success('Successfully Save')->persistent('Dismiss');
            return back();
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            throw $e;
        }
    }

    public function edit(Request $request,$id)
    {
        try {
            $sssTable = SssTable::where('id', $id)->first();
            $sssTable->from_range = $request->from;
            $sssTable->to_range = $request->to;
            $sssTable->er = $request->er;
            $sssTable->ee = $request->ee;
            $sssTable->save();
            Alert::success('Successfully Save')->persistent('Dismiss');
            return back();
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            throw $e;
        }
    }
}
