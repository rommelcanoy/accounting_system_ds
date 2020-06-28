<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use Validator;
use DB;

class AccountController extends Controller
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

        $accounts = DB::table('accounts')
            ->select('accounts.*')
            ->where('user_id', "=", $user_id)
            ->orderBy('account_no', 'asc')
            ->get();

        $company_data = $this->get_company($user_id);

        return view('print_accounts', compact('accounts', 'company_data'));
    }

    public function index(Request $request)
    {

        $user_id = $request->user()->id;

        if (request()->ajax()) {
            return datatables()->of(DB::table('accounts')->where('user_id', $user_id))
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-custom btn-sm"><i class="fas fa-edit"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-custom btn-sm"><i class="fas fa-trash-alt"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('accounts', compact('user_id'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $rules = array(
                'account_no.*'   =>  'required|integer|min:1|not_in:0',
                // 'account_no'   =>  'unique:accounts',
                'account_title.*'   =>  'required',
                // 'account_title' => 'unique:accounts',
                'account_type.*'    =>  'required',
                'user_id.*'    =>  'required'
            );
            $error = Validator::make($request->all(), $rules);
            if ($error->fails()) {
                return response()->json([
                    'error'     =>  $error->errors()->all()
                ]);
            }

            $account_no = $request->account_no;
            $account_title = $request->account_title;
            $account_type = $request->account_type;
            // $user_id = $request->user_id;

            $user_id1 = $request->user()->id;



            // $account_no_validate = false;
            // $account_title_validate = false;
            $account_validate = array();
            $account_validate['account_no'] = false;
            $account_validate['account_title'] = false;

            $account = DB::table('accounts')
                ->select('accounts.*')
                // ->join('users', 'users.id', '=', 'accounts.user_id')
                ->where('accounts.user_id', $user_id1)
                ->get();

            for ($count = 0; $count < count($account_title); $count++) {

                $account_validate['account_no'] = false;
                $account_validate['account_title'] = false;

                foreach ($account as $acc) {

                    if (strcasecmp(trim($acc->account_title), trim($account_title[$count])) == 0) {
                        $account_validate['account_title'] = true;
                    }
                    if (strcasecmp(trim($acc->account_no), trim($account_no[$count])) == 0) {
                        $account_validate['account_no'] = true;
                    }
                }

                $data = array(
                    'account_no'    =>   $account_no[$count],
                    'account_title'    =>   $account_title[$count],
                    'account_type'     =>   $account_type[$count],
                    'user_id'     =>   $user_id1
                );
                $insert_data[] = $data;
            }

            // $json_account_validate = json_encode($account_validate);

            if ($account_validate['account_title'] == true) {
                return response()->json([
                    'account_title_error'     =>   'Account title already exists.'
                ]);
            }
            if ($account_validate['account_no'] == true) {
                return response()->json([
                    'account_no_error'     =>   'Account number already exists.'
                ]);
            }

            Account::insert($insert_data);
            return response()->json([
                'success'       =>  'Data added succesfully.'
            ]);
        }
    }


    public function edit($id)
    {
        if (request()->ajax()) {
            $data = Account::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    public function update(Request $request)
    {

        $rules = array(
            'account_no'    =>  'required',
            'account_title'    =>  'required',
            'account_type'     =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'account_no'       =>   $request->account_no,
            'account_title'       =>   $request->account_title,
            'account_type'        =>   $request->account_type
        );
        Account::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    public function destroy($id)
    {
        $data = Account::findOrFail($id);
        $data->delete();
    }
}
