<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\PaymentTransaction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Auth;

class SplitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('split_access'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 
        return view('admin.master.splits.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('split_create'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 
        $request->validate([
            'from_date' => 'required|date|before_or_equal:'.now()->format('d-m-Y'),
        ]);
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $splitBalance = PaymentTransaction::selectRaw('customer_id')
                                          ->selectRaw("SUM(CASE WHEN payment_type='debit' THEN amount ELSE 0 END) AS total_debit")
                                          ->selectRaw("SUM(CASE WHEN payment_type='credit' THEN amount ELSE 0 END) AS total_credit")
                                          ->whereDate('entry_date','<=',date('Y-m-d', strtotime($from_date)))
                                          ->groupBy('customer_id')
                                          ->get();
        foreach ($splitBalance as $key => $split) {
            $openingBalance = (float)$split->total_credit - (float)$split->total_debit;
            $splitData = PaymentTransaction::where('customer_id',$split->customer_id)->whereDate('entry_date','<=',date('Y-m-d', strtotime($from_date)));
            $splitData->update(['is_split' => 1]);
            $splitData->delete();

            PaymentTransaction::create([
                'customer_id' => $split->customer_id,
                'payment_type' => 'credit',
                'payment_way' => 'by_split',
                'remark' => 'Opening balance',
                'amount' => (!empty($openingBalance) && !is_null($openingBalance)) ? $openingBalance : '0.00',
                'entry_date' => Carbon::now()->format('Y-m-d'),
                'created_by'=> Auth::id()
            ]);
        }

        // select customer_id, SUM(CASE WHEN payment_type='debit' THEN amount ELSE 0 END) AS Debited, SUM(CASE WHEN payment_type='credit' THEN amount ELSE 0 END) AS Credited FROM `payment_transactions`  where entry_date <='2023-04-13' group by customer_id;
        return redirect()->route('admin.master.split.index')->with('success', 'Split create successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
