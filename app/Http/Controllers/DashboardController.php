<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\User;
use App\Company;
use DB;

class DashboardController extends Controller
{
    protected $total_expense, $total_revenue, $entries, $company;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function debit_credit($debit, $credit)
    {
        $balance_type = '';

        if ($debit > $credit) {
            $balance_type = 'debit';
        } else {
            $balance_type = 'credit';
        }

        return $balance_type;
    }

    public function get_trial_balance($entries)
    {

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

                    $balance_type = $this->debit_credit($debit_total, $credit_total);

                    $balance += $entry->debit;
                } else {
                    $debit_total += $entry->debit;
                    $credit_total += $entry->credit;

                    $balance_type = $this->debit_credit($debit_total, $credit_total);

                    $balance -= $entry->credit;
                }
                array_push($values, $balance);
                array_push($values, $balance_type, $entry->account_type);
                $data[$entry->account_title] = $values;
            } else {
                $values = array();
                if ($entry->debit > $entry->credit) {
                    $debit_total += $entry->debit;
                    $credit_total += $entry->credit;

                    $balance_type = $this->debit_credit($debit_total, $credit_total);

                    $balance -= $entry->debit;
                } else {
                    $debit_total += $entry->debit;
                    $credit_total += $entry->credit;

                    $balance_type = $this->debit_credit($debit_total, $credit_total);

                    $balance += $entry->credit;
                }

                array_push($values, $balance);
                array_push($values, $balance_type, $entry->account_type);
                $data[$entry->account_title] = $values;
            }
        }

        return $data;
    }

    public function get_journal($user_id)
    {
        $this->entries = DB::table('journals')
            ->join('accounts', "accounts.id", "=", "journals.p_r")
            ->select('journals.*', 'accounts.account_type', 'accounts.account_no')
            ->where('journals.user_id', "=", $user_id)
            ->orderBy('accounts.account_no', 'ASC')
            ->orderBy('date', 'ASC')
            ->get();

        return $this->entries;
    }

    public function get_statement_oe($user_id)
    {
        $journal = $this->get_journal($user_id);

        $net = "";
        $amount =  0;

        foreach ($journal as $data) {
            if ($data->account_type == 'Income') {
                if ($data->debit > $data->credit) {
                    $this->total_revenue += $data->debit;
                } else {
                    $this->total_revenue += $data->credit;
                }
            } elseif ($data->account_type == 'Expense') {
                if ($data->debit > $data->credit) {
                    $this->total_expense += $data->debit;
                } else {
                    $this->total_expense += $data->credit;
                }
            }
        }

        $amount = $this->total_revenue - $this->total_expense;

        if ($amount > 0) {
            $net = 'Net Income';
        } else {
            $net = 'Net Loss';
        }

        $capital = $this->entries = DB::table('journals')
            ->join('accounts', "accounts.id", "=", "journals.p_r")
            ->select('journals.*', 'accounts.account_type')
            ->where('journals.user_id', "=", $user_id)
            ->where('accounts.account_type', '=', 'Capital')
            ->orderBy('accounts.account_no', 'ASC')
            ->orderBy('date', 'ASC')
            ->first();

        $additional_investments = $this->entries = DB::table('journals')
            ->join('accounts', "accounts.id", "=", "journals.p_r")
            ->select('journals.credit')
            ->where('journals.user_id', "=", $user_id)
            ->where('accounts.account_type', '=', 'Capital')
            ->orderBy('accounts.account_no', 'ASC')
            ->orderBy('date', 'ASC')
            ->get();

        $a_i_total = 0;

        foreach ($additional_investments as $data) {
            $a_i_total += $data->credit;
        }

        if ($a_i_total > 0) {
            $a_i_total -= $capital->credit;
        }

        $withdrawal = $this->entries = DB::table('journals')
            ->join('accounts', "accounts.id", "=", "journals.p_r")
            ->select('journals.*', 'accounts.account_type')
            ->where('journals.user_id', "=", $user_id)
            ->where('accounts.account_type', '=', 'Withdrawal')
            // ->orderBy('accounts.account_no', 'ASC')
            ->orderBy('date', 'ASC')
            ->get();


        $total_withdrawal = 0;

        $withdrawals = array();
        $test1 = '';

        foreach ($withdrawal as $data) {
            if ($data->account_type == "Withdrawal") {
                $test1 = $data->account_title;
            }
            $total_withdrawal += $data->debit;
        }

        array_push($withdrawals, $test1);
        array_push($withdrawals, $total_withdrawal);

        // dd($withdrawals);
        $amount = abs($amount);

        $owners_equity = array();

        $last_date = DB::table('journals')->where('user_id', $user_id)->orderBy('date', 'desc')->first();

        if ($last_date != null) {
            $last_date = $last_date->date;
        } else {
            $last_date = null;
        }


        $total = 0;
        $net_income = 0;
        $net_loss = 0;
        $capital_begin = 0;

        // debug
        if (empty($capital)) {
            $capital_begin = 0;
        } else {
            $capital_begin = $capital->credit;
        }

        if ($net == 'Net Income') {
            $net_income = $amount;
        } else {
            $net_loss = $amount;
        }

        $owners_equity['net'] = $net;
        if (empty($capital)) {
            $owners_equity['first_date'] = null;
            $owners_equity['capital'] = null;
            $owners_equity['capital_title'] = null;
        } else {
            $owners_equity['first_date'] = $capital->date;
            $owners_equity['capital'] = $capital->credit;
            $owners_equity['capital_title'] = $capital->account_title;
        }
        $owners_equity['last_date'] = $last_date;


        $owners_equity['additional_i'] = $a_i_total;
        $owners_equity['net_income'] = $net_income;
        $owners_equity['net_loss'] = $net_loss;

        $total = $capital_begin + $net_income + $a_i_total;

        $total -=  $withdrawals[1];
        $total -= $net_loss;

        $owners_equity['withdrawals'] = $withdrawals[1];
        $owners_equity['total'] = $total;

        return $owners_equity;
    }

    public function index(Request $request)
    {
        //
        $user_id = $request->user()->id;
        $company = DB::table('companies')
            ->select('companies.*')
            ->where('user_id', '=', $user_id)
            ->get();

        $total_assets = 0;
        $total_liabilities = 0;
        $total_oe = 0;

        $trial_balance = $this->get_trial_balance($this->get_journal($user_id));

        foreach($trial_balance as $account => $value){
            if($value[2] == "Assets"){
                $total_assets += $value[0];
            }
            if($value[2] == "Liabilities"){
                $total_liabilities += $value[0];
            }
        }

        $total_oe = ($this->get_statement_oe($user_id));

        $total_oe = $total_oe['total'];

        return view('dashboard', compact('user_id', 'company', 'total_assets', 'total_liabilities', 'total_oe'));
    }

    public function getCompany($id = 0, Request $request)
    {
        $user_id = $request->user()->id;

        if ($id != 0) {
            $company = Company::orderby('id', 'asc')->select('*')->where('user_id', '=', $id)->get();
        } 
        // else {
        //     $company = Company::select('*')->where('id', $id)->where('user_id', '=', $user_id)->get();
        // }
        // Fetch all records
        $companyData['data'] = $company;

        echo json_encode($companyData);
        exit;
    }

    public function store(Request $request)
    {
        $rules = array(
            'company_name' => 'required',
            'company_owner' => 'required',
            'business_org' => 'required',
            'business_type' => 'required',
            'user_id' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'company_name' => $request->company_name,
            'company_owner' => $request->company_owner,
            'business_org' => $request->business_org,
            'business_type' => $request->business_type,
            'user_id' => $request->user_id
        );

        Company::create($form_data);

        return response()->json(['success' => 'Data successfully added.']);
        // return redirect('/dashboard')->with('status', 'Data updated successfully');
    }

    public function edit(Request $request, $id)
    {
        $data = Company::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request)
    {
        $rules = array(
            'company_name' => 'required',
            'company_owner' => 'required',
            'business_org' => 'required',
            'business_type' => 'required',
            'user_id' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'company_name' => $request->company_name,
            'company_owner' => $request->company_owner,
            'business_org' => $request->business_org,
            'business_type' => $request->business_type,
            'user_id' => $request->user_id
        );

        Company::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data successfully updated.']);
    }
}
