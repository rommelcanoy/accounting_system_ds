<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Journal;
use DB;

class GeneralLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->user()->id;

        $company_data = $this->get_company($user_id);

        $entries = DB::table('journals')
            ->join('accounts', "accounts.id", "=", "journals.p_r")
            ->select('journals.*', 'accounts.account_type', 'accounts.account_no')
            ->where('journals.user_id', "=", $user_id)
            ->orderBy('accounts.account_no', 'ASC')
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        return view('ledger', compact('entries', 'company_data'));
        //
    }

    public function get_company($user_id)
    {
        $this->company = DB::table('companies')
            ->select('companies.*')
            ->where('companies.user_id', '=', $user_id)
            ->get();

        return $this->company;
    }

}
