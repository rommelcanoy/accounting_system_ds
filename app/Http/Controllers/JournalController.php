<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Journal;
use App\Account;
use Validator;
use DB;


class JournalController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get_company($user_id)
    {
        $this->company = DB::table('companies')
            ->select('companies.*')
            ->where('companies.user_id', '=', $user_id)
            ->get();

        return $this->company;
    }

    public function print(Request $request)
    {
        $user_id = $request->user()->id;

        $journal = DB::table('journals')
            ->join('accounts', "accounts.id", "=", "journals.p_r")
            ->select('journals.*', 'accounts.account_no')
            ->where('journals.user_id', "=", $user_id)
            ->orderBy('journals.date', 'asc')
            ->get();

        $company_data = $this->get_company($user_id);

        return view('print', compact('journal', 'company_data'));
    }

    public function index(Request $request)
    {

        $user_id = $request->user()->id;

        $accounts = DB::table('accounts')
            ->join('users', "users.id", "=", "accounts.user_id")
            ->select('accounts.*')
            ->where('user_id', "=", $user_id)
            // ->orderBy('online_requests.id', 'desc')
            ->get();


        if (request()->ajax()) {
            return datatables()->of(DB::table('journals')
                ->join('accounts', 'accounts.id', '=', 'journals.p_r')
                // ->join('users', 'users.id', '=', 'journals.user_id')
                ->select('journals.*', 'accounts.account_no')
                ->where('journals.user_id', '=', $user_id))
                ->editColumn('date', function ($user) {
                    return $user->date ? with(new Carbon($user->date))->format('M-d-Y') : '';
                })
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-custom btn-sm"><i class="fas fa-edit"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-custom btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('journal', compact('user_id', 'accounts'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $rules = array(
                'date'   =>  'required',
                'account_title.*'    =>  'required',
                'description'    =>  'required',
                'entry_type.*'    =>  'required',
                'amount.*'    =>  ['required', 'numeric', 'min:1', 'not_in:0'],
                'user_id.*'    =>  'required'
            );

            $error = Validator::make($request->all(), $rules);
            if ($error->fails()) {
                return response()->json([
                    'error'     =>  $error->errors()->all()
                ]);
            }

            $date = $request->date;
            $account_title = $request->account_title;
            $description = $request->description;
            $entry_type = $request->entry_type;
            $amount = $request->amount;
            // $user_id = $request->user_id;
            $user_id = $request->user()->id;

            $total_debit = 0;
            $total_credit = 0;
            $p_r = array();

            for ($count = 0; $count < count($account_title); $count++) {

                $debit[$count] = 0;
                $credit[$count] = 0;

                if ($entry_type[$count] == "0") {
                    $debit[$count] = $amount[$count];
                    $total_debit += $amount[$count];
                } else {
                    $credit[$count] = $amount[$count];
                    $total_credit += $amount[$count];
                }

                $account = DB::table('accounts')
                    ->select('accounts.*')
                    ->join('users', 'users.id', '=', 'accounts.user_id')
                    ->where('accounts.user_id', $user_id)
                    ->where('accounts.account_title', $account_title[$count])
                    ->get();


                foreach ($account as $acc) {
                    array_push($p_r, $acc->id);
                }

                // dd($p_r[$count]);

                $data = array(
                    'date'    =>   $date,
                    'account_title'     =>   $account_title[$count],
                    'description'     =>   $description,
                    'p_r'     =>   $p_r[$count],
                    'debit'     =>   $debit[$count],
                    'credit'     =>   $credit[$count],
                    'user_id'     =>   $user_id
                );

                $insert_data[] = $data;
            }

            if ($total_debit != $total_credit) {
                return response()->json([
                    'unbalance'     =>  'Not balance'
                ]);
            } else {
                Journal::insert($insert_data);
                return response()->json([
                    'success'       =>  'Data added successfully'
                ]);
            }
        }
    }

    public function edit($id)
    {
        if (request()->ajax()) {
            $data = Journal::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    public function update(Request $request)
    {

        $rules = array(
            'date'    =>  'required',
            'account_title'     =>  'required',
            'description'     =>  'required',
            'debit'     =>  'required',
            'credit'     =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $p_r = '';
        // $account = DB::table('accounts')
        //     ->select('id')
        //     ->where('account_title', $request->account_title)
        //     ->get();

        $account = DB::table('accounts')
            ->select('accounts.*')
            ->join('users', 'users.id', '=', 'accounts.user_id')
            ->where('accounts.account_title', $request->account_title)
            ->get();

        foreach ($account as $acc) {
            $p_r = $acc->id;
        }

        $form_data = array(
            'date'       =>   $request->date,
            'account_title'       =>   $request->account_title,
            'description'       =>   $request->description,
            'p_r'       =>      $p_r,
            'debit'       =>   $request->debit,
            'credit'        =>   $request->credit
        );

        Journal::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    public function destroy($id)
    {
        $data = Journal::findOrFail($id);
        $data->delete();
    }
}
