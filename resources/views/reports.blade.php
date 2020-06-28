@extends('layouts.master')

@section('title')
Reports
@endsection

@section('header')
Reports
@endsection

@section('reports')
active
@endsection

@section('content')
<div class="row top-buffer justify-content-center">

    <div class="col-xl-6 col-md-12 mb-4 reports-link">
        <a href="/ledger">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto mr-4">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                        <div class="col mr-2">
                            <div class="h5 font-weight-bold mb-1">General Ledger</div>
                            <div class="h6 mb-0 gray">A complete record of your transactions
                                to help you prepare financial statements.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-6 col-md-12 mb-4 reports-link">
        <a href="/trial_balance">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto mr-4">
                            <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
                        </div>
                        <div class="col mr-2">
                            <div class="h5 font-weight-bold mb-1">Trial Balance</div>
                            <div class="h6 mb-0 gray">
                                Shows the schedule of all balances to prove the equality of the debit and
                                credit.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-6 col-md-12 mb-4 reports-link">
        <a href="/balance_sheet">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto mr-4">
                            <i class="fas fa-landmark fa-2x text-gray-300"></i>
                        </div>
                        <div class="col mr-2">
                            <div class="h5 font-weight-bold mb-1">Balance Sheet</div>
                            <div class="h6 mb-0 gray">
                                Lists a company’s assets, liabilities and owner's equity at a specific point in time. 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-6 col-md-12 mb-4 reports-link">
        <a href="/income_statement">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto mr-4">
                            <i class="fas fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                        <div class="col mr-2">
                            <div class="h5 font-weight-bold mb-1">Income Statement</div>
                            <div class="h6 mb-0 gray">
                                Summarizes on the company’s revenues and expenses during a particular period.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-6 col-md-12 mb-4 reports-link">
        <a href="/statement_oe">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto mr-4">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                        <div class="col mr-2">
                            <div class="h5 font-weight-bold mb-1">Statement of Owner's Equity</div>
                            <div class="h6 mb-0 gray">
                                Shows changes in the capital balance of a business over a reporting period.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-6 col-md-12 mb-4 reports-link">
        <a href="/statement_cf">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto mr-4">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                        <div class="col mr-2">
                            <div class="h5 font-weight-bold mb-1">Statement of Cash Flows</div>
                            <div class="h6 mb-0 gray">
                                Summarizes the cash receipts and cash disbursements for the accounting period.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>


@endsection