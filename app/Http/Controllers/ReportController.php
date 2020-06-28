<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Journal;

class ReportController extends Controller
{


    protected $total_expense, $total_revenue, $entries, $company;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('reports');
    }

    public function get_company($user_id)
    {
        $this->company = DB::table('companies')
            ->select('companies.*')
            ->where('companies.user_id', '=', $user_id)
            ->get();

        return $this->company;
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

    public function journal_entries($user_id)
    {
        $this->entries = DB::table('journals')
            ->join('accounts', "accounts.id", "=", "journals.p_r")
            ->select('journals.*', 'accounts.account_type', 'accounts.account_no')
            ->where('journals.user_id', "=", $user_id)
            ->orderBy('date', 'ASC')
            ->orderBy('journals.id', 'ASC')
            ->get();

        return $this->entries;
    }

    public function trial_balance(Request $request)
    {
        $user_id = $request->user()->id;

        $company_data = $this->get_company($user_id);

        $data = $this->get_trial_balance($this->get_journal($user_id));

        $last_date = DB::table('journals')->where('user_id', $user_id)->orderBy('date', 'desc')->first();

        if ($last_date != null) {
            $last_date = $last_date->date;
        } else {
            $last_date = null;
        }

        return view('trial_balance', compact('data', 'last_date', 'company_data'));
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

    public function income_statement(Request $request)
    {
        $user_id = $request->user()->id;

        $company_data = $this->get_company($user_id);
        $last_date = DB::table('journals')->where('user_id', $user_id)->orderBy('date', 'desc')->first();

        if ($last_date != null) {
            $last_date = $last_date->date;
        } else {
            $last_date = null;
        }

        $trial_balance = $this->get_trial_balance($this->get_journal($user_id));

        return view('income_statement', compact('trial_balance', 'last_date', 'company_data'));
    }

    public function statement_oe(Request $request)
    {
        $user_id = $request->user()->id;

        $company_data = $this->get_company($user_id);
        $journal = $this->get_journal($user_id);
        $net = "";
        $amount =  0;

        $data = $this->get_statement_oe($user_id);


        return view('statement_oe', compact('data', 'company_data'));
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


    public function balance_sheet(Request $request)
    {
        $user_id = $request->user()->id;

        $company_data = $this->get_company($user_id);
        $owners_equity = $this->get_statement_oe($user_id);
        $trial_balance = $this->get_trial_balance($this->get_journal($user_id));


        $non_current_assets = array();

        foreach ($trial_balance as $account => $value) {
            if ($this->containsWord($account, 'Equipment') || $this->containsWord($account, 'Building') || $this->containsWord($account, 'Land') || $this->containsWord($account, 'Furniture') || $this->containsWord($account, 'Mortgage') || $this->containsWord($account, 'Bonds')) {
                array_push($trial_balance[$account], 'non_current');
            } else {
                array_push($trial_balance[$account], 'current');
            }
        }

        return view('balance_sheet', compact('owners_equity', 'trial_balance', 'company_data'));
    }

    public function containsWord($str, $word)
    {
        return !!preg_match('#\\b' . preg_quote($word, '#') . '\\b#i', $str);
    }

    public function statement_cf(Request $request)
    {
        $user_id = $request->user()->id;

        $company_data = $this->get_company($user_id);

        $previousValue = '';
        $balance = 0;

        $operating = array();
        $investing = array();
        $financing = array();

        $journal = $this->journal_entries($user_id);

        foreach ($journal as $data) {

            if ($previousValue == 'Cash') {
                if ($this->containsWord($data->account_title, 'Capital') || $this->containsWord($data->account_title, 'Notes Payable')) {
                    $financing[$data->account_title] = $data->debit + $data->credit;
                } elseif ($this->containsWord($data->account_title, 'Revenues') || $this->containsWord($data->account_title, 'Services') || $this->containsWord($data->account_title, 'Service')) {
                    $operating[$data->account_title] = $data->debit + $data->credit;
                } elseif ($this->containsWord($data->account_title, 'Insurance')) {
                    $operating[$data->account_title] = $data->debit + $data->credit - ($data->debit + $data->credit) * 2;
                }
            } elseif ($data->account_title == 'Cash') {
                if ($this->containsWord($previousValue, 'Rent') || $this->containsWord($previousValue, 'Payable') || $this->containsWord($previousValue, 'Expense')) {
                    if ($data->debit > $data->credit) {
                        $operating[$previousValue] = $data->debit;
                    } else {
                        $operating[$previousValue] = $data->credit - ($data->credit) * 2;
                    }
                } elseif ($this->containsWord($previousValue, 'Vehicle') || $this->containsWord($previousValue, 'Equipment')) {
                    if ($data->debit > $data->credit) {
                        $investing[$previousValue] = $data->debit;
                    } else {
                        $investing[$previousValue] = $data->credit - ($data->credit) * 2;
                    }
                } elseif ($this->containsWord($previousValue, 'Withdrawal') || $this->containsWord($previousValue, 'Withdrawals')) {
                    $financing[$previousValue] = ($data->debit + $data->credit) - ($data->debit + $data->credit) * 2;
                }
            }

            $previousValue = $data->account_title;
            // $previousAmount = $data->debit+$data->credit;

        }

        return view('statement_cf', compact('operating', 'financing', 'investing', 'company_data'));
    }
}
