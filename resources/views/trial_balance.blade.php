@extends('layouts.master')

@section('title')
Trial Balance
@endsection

@section('header')
Trial Balance
@endsection

@section('reports')
active
@endsection

@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb shadow">
    <li class="breadcrumb-item"><a href="/reports">Reports</a></li>
    <li class="breadcrumb-item active" aria-current="page">Trial Balance</li>
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
                <h5 class="card-title text-center">Trial Balance</h5>
                <h5 class="card-title text-center">
                    @if($last_date != null)
                        {{date('F-d-Y', strtotime($last_date))}}
                    @else
                        No date
                    @endif
                </h5>

                <div class="table-responsive">
                    <table class="table table-hover mt-4" id="table_data">
                        <tr>
                            <td>Account Title</td>
                            <td>Debit</td>
                            <td width="2px"></td>
                            <td>Credit</td>
                        </tr>
    
                        <?php 
    
                        $total_debit = 0;
                        $total_credit = 0;
                        
                        ?>
    
                        @if(count($data) > 0)
                        @foreach($data as $account => $value)
                        <tr>
                            <td>{{$account}}</td>
                            @if($value[1] == 'debit')
                            <td>@money($value[0])</td>
                            <td></td>
                            <td></td>
    
                            <?php $total_debit += $value[0]; ?>
                            @else
                            <td></td>
                            <td></td>
                            <td>@money($value[0])</td>
                            <?php $total_credit += $value[0]; ?>
                            @endif
                        </tr>
                        @endforeach
                        <tr class="bg-light-custom">
                            <td>Total</td>
                            <td class="total_debit">@money($total_debit)</td>
                            <td></td>
                            <td class="total_credit">@money($total_credit)</td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="4" class="text-center">No data available</td>
                        </tr>   
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection