<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TrialBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user_id = $request->user()->id;

        $entries = DB::table('journals')
            ->join('accounts', "accounts.account_no", "=", "journals.p_r")
            ->select('journals.*', 'accounts.account_type')
            ->where('journals.user_id', "=", $user_id)
            ->orderBy('journals.p_r', 'ASC')
            ->orderBy('date', 'ASC')
            ->get();

        $entry_accounts = DB::table('journals')
            ->select('account_title', 'p_r')
            ->groupBy('account_title')
            ->orderBy('p_r', 'asc')
            ->get();

        $previousAccount = '';
        $debit_total = 0;
        $credit_total = 0;
        $data = array();
        $counter = 0;
        $balance = 0;
        $balance_type = '';

        foreach ($entries as $entry) {
            if ($previousAccount != $entry->account_title) {
                $debit_total = 0;
                $credit_total = 0;
                $balance = 0;
                $counter++;
            }

            $previousAccount = $entry->account_title;

            if ($entry->account_type == 'Assets' || $entry->account_type == 'Withdrawal' || $entry->account_type == 'Expense') {
                $values = array();

                if ($entry->debit > $entry->credit) {
                    $debit_total += $entry->debit;
                    $credit_total += $entry->credit;

                    if ($debit_total > $credit_total) {
                        $balance_type = 'debit';
                    } else {
                        $balance_type = 'credit';
                    }

                    $balance += $entry->debit;
                    
                } else {
                    $debit_total += $entry->debit;
                    $credit_total += $entry->credit;

                    if ($debit_total > $credit_total) {
                        $balance_type = 'debit';
                    } else {
                        $balance_type = 'credit';
                    }

                    $balance -= $entry->credit;
                }
                array_push($values, $balance);
                // $credit_total += $entry->debit;
                array_push($values, $balance_type);
                $data[$entry->account_title] = $values;

            } else {
                $values = array();
                if ($entry->debit > $entry->credit) {
                    $debit_total += $entry->debit;
                    $credit_total += $entry->credit;

                    if ($debit_total > $credit_total) {
                        $balance_type = 'debit';
                    } else {
                        $balance_type = 'credit';
                    }

                    $balance -= $entry->debit;
                    // array_push($values, $balance);
                    // array_push($values, $balance_type);


                    // $data[$entry->account_title] = $values;
                } else {
                    $debit_total += $entry->debit;
                    $credit_total += $entry->credit;

                    if ($debit_total > $credit_total) {
                        $balance_type = 'debit';
                    } else {
                        $balance_type = 'credit';
                    }

                    $balance += $entry->credit;
                }
                array_push($values, $balance);
                // $credit_total += $entry->debit;
                array_push($values, $balance_type);
                $data[$entry->account_title] = $values;
            }
        }

        // $array = array();

        // foreach($entries as $entry){
        //     $values = array();

        //     array_push($values, $entry->account_title);
        //     array_push($values, $entry->p_r);
        //     array_push($array, $values);

        // }


        // dd($data);

        return view('trial_balance', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
