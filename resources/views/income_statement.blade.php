@extends('layouts.master')

@section('title')
Income Statement
@endsection

@section('header')
Income Statement
@endsection

@section('reports')
active
@endsection

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb shadow">
        <li class="breadcrumb-item"><a href="/reports">Reports</a></li>
        <li class="breadcrumb-item active" aria-current="page">Income Statement</li>
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
                <h5 class="card-title text-center">Income Statement</h5>
                <h5 class="card-title text-center">
                    @if($last_date != null)
                        {{date('F-d-Y', strtotime($last_date))}}
                    @else
                        No date
                    @endif
                </h5>

                <div class="table-responsive">
                    <table class="table table-hover mt-4" id="table_data">
                        @if(!empty($trial_balance))
                        <?php $total_expense = 0; $total_revenue = 0; $net = 0;?>
    
                        {{-- Revenue --}}
                        <tr>
                            <td colspan="3">Revenues</td>
                        </tr>
                            @foreach($trial_balance as $account => $value)
                            <tr>
                            @if($value[2] == 'Income')
                                <td style="padding-left: 80px;">{{$account}}</td>
                                <td>@money($value[0])</td>
                                <td></td>
                                <?php $total_revenue += $value[0]; ?>
                            @endif
                            </tr>
                            @endforeach
                        <tr>
                            <td>Total Revenues</td>
                            <td></td>
                            <td>@money($total_revenue)</td>
                        </tr>
    
                        {{-- Expense --}}
                        <tr>
                            <td colspan="3">Expenses</td>
                        </tr>
                            @foreach($trial_balance as $account => $value)
                            <tr>
                            @if($value[2] == 'Expense')
                                <td style="padding-left: 80px;">{{$account}}</td>
                                <td>@money($value[0])</td>
                                <td></td>
                                <?php $total_expense += $value[0]; ?>
                            @endif
                            </tr>
                            @endforeach
                        <tr>
                            <td>Total Expenses</td>
                            <td></td>
                            <td>@money($total_expense)</td>
                        </tr>
                        <tr class="bg-light-custom">
                            <?php 
                                $net = $total_revenue - $total_expense;
                            ?>
                            @if($net >= 0)
                                <td>Net Income</td>
                                <td></td>
                                <td class="final">@money($net)</td>
                            @else
                                <td>Net Loss</td>
                                <td></td>
                                <td class="final">@money($net)</td>
                            @endif
                        </tr>
                        @else 
                        <tr>
                            <td class="text-center">No data available</td>
                        </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>



@endsection