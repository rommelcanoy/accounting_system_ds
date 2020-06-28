@extends('layouts.master')

@section('title')
General Ledger
@endsection

@section('header')
General Ledger
@endsection

@section('reports')
active
@endsection

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="/reports">Reports</a></li>
        <li class="breadcrumb-item active" aria-current="page">General Ledger</li>
        <button class="btn btn-sm btn-custom ml-auto print"><i class="fas fa-print mr-2"></i>Print</button>
    </ol>
</nav>
<div class="row top-buffer justify-content-center">
    <div class="col-xl-12 col-md-12 mb-4">
        {{-- <span id="result1"></span> --}}
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title text-center">
                    @if(count($company_data) > 0)
                    {{$company_data[0]->company_name}}
                    @else
                    Add Company Name
                    @endif
                </h5>
                <h5 class="card-title text-center">General Ledger</h5>
                {{-- <button class="btn btn-custom mb-4" data-toggle="modal" data-target="#add_account">Add
                    Account</button> --}}


                <?php 
                    $previousValue = '';
                    $debit_balance = 0;
                    $credit_balance = 0;
                    $balance = 0;

                ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mt-4" id="table_data">
                        @if(count($entries) > 0)
    
                        @foreach($entries as $entry)
    
                        @if($previousValue != $entry->account_title)
                        <?php $balance = 0; ?>
                        <tr class="bg-light-custom">
                            <td colspan="6">{{$entry->account_title}}</td>
                        </tr>
                        <tr>
                            <td width="15%">Date</td>
                            <td width="30%">Explanation</td>
                            <td width="5%">J.R.</td>
                            <td width="15%">Debit</td>
                            <td width="15%">Credit</td>
                            <td>Balance</td>
                        </tr>
                        @endif
    
                        <?php $previousValue = $entry->account_title ?>
                        <tr>
                            <td>{{ date('M-d-Y', strtotime($entry->date)) }}</td>
                            <td width="30%">{{$entry->description}}</td>
                            <td width="5%">{{$entry->account_no}}</td>
                            <td width="15%">{{$entry->debit}}</td>
                            <td width="15%">{{$entry->credit}}</td>
    
                            @if($entry->account_type == 'Assets' && $entry->debit > $entry->credit)
                            <td>{{$balance += $entry->debit}}</td>
                            @elseif($entry->account_type == 'Assets' && $entry->debit < $entry->credit)
                                <td>{{$balance -= $entry->credit}}</td>
                                @elseif($entry->account_type == 'Liabilities' && $entry->debit > $entry->credit)
                                <td>{{$balance -= $entry->debit}}</td>
                                @elseif($entry->account_type == 'Liabilities' && $entry->debit < $entry->credit)
                                    <td>{{$balance += $entry->credit}}</td>
                                    @elseif($entry->account_type == 'Capital' && $entry->debit > $entry->credit)
                                    <td>{{$balance -= $entry->debit}}</td>
                                    @elseif($entry->account_type == 'Capital' && $entry->debit < $entry->credit)
                                        <td>{{$balance += $entry->credit}}</td>
                                        @elseif($entry->account_type == 'Withdrawal' && $entry->debit > $entry->credit)
                                        <td>{{$balance += $entry->debit}}</td>
                                        @elseif($entry->account_type == 'Withdrawal' && $entry->debit < $entry->credit)
                                            <td>{{$balance -= $entry->credit}}</td>
                                            @elseif($entry->account_type == 'Income' && $entry->debit > $entry->credit)
                                            <td>{{$balance -= $entry->debit}}</td>
                                            @elseif($entry->account_type == 'Income' && $entry->debit < $entry->credit)
                                                <td>{{$balance += $entry->credit}}</td>
                                                @elseif($entry->account_type == 'Expense' && $entry->debit > $entry->credit)
                                                <td>{{$balance += $entry->debit}}</td>
                                                @elseif($entry->account_type == 'Expense' && $entry->debit < $entry->credit)
                                                    <td>{{$balance -= $entry->credit}}</td>
                                                    @endif
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td  class="text-center">No data available</td>
                        </tr>
                        @endif
                    </table>
                </div>
                {{-- diri kung walay data --}}
            </div>
        </div>
    </div>
</div>



@endsection