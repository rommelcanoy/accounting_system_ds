@extends('layouts.master')

@section('title')
Balance Sheet
@endsection

@section('header')
Balance Sheet
@endsection

@section('reports')
active
@endsection

@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb shadow">
    <li class="breadcrumb-item"><a href="/reports">Reports</a></li>
    <li class="breadcrumb-item active" aria-current="page">Balance Sheet</li>
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
        <h5 class="card-title text-center">Balance Sheet</h5>
        <h5 class="card-title text-center">
          @if($owners_equity['last_date'] != null)
            {{date('F-d-Y', strtotime($owners_equity['last_date']))}}
          
          @else 
            No date
          @endif
        </h5>

        <div class="table-responsive">
          <table class="table table-hover mt-4" id="table_data">
            @if(!empty($trial_balance))
            <?php $total_ca = 0; $total_nca = 0; $total_cl = 0; $total_ncl = 0; $total_oe = 0;?>
            <tr class="text-center">
              <td colspan="3">Assets</td>
            </tr>
            <tr>
              <td>Current Assets</td>
              <td></td>
            </tr>
            @foreach($trial_balance as $account => $value)
            @if($value[2] == 'Assets' && $value[3] == 'current')
            <tr>
              <td style="padding-left: 80px;">{{$account}}</td>
              <td>@money($value[0])</td>
              <?php $total_ca += $value[0]; ?>
            </tr>
            @endif
            @endforeach
            <tr class="bg-light-custom">
              <td>Total Current Assets</td>
              <td>@money($total_ca)</td>
            </tr>
            <tr>
              <td>Non-current Assets</td>
              <td></td>
            </tr>
            @foreach($trial_balance as $account => $value)
            @if($value[2] == 'Assets' && $value[3] == 'non_current')
            <tr>
              <td style="padding-left: 80px;">{{$account}}</td>
              <td>@money($value[0])</td>
              <?php $total_nca += $value[0]; ?>
            </tr>
            @endif
            @endforeach
            <tr class="bg-light-custom">
              <td>Total Non-current Assets</td>
              <td>@money($total_nca)</td>
            </tr>
            <tr class="bg-light-custom">
              <td>Total Assets</td>
              <td class="final">@money($total_ca + $total_nca)</td>
            </tr>
            <tr>
              <td colspan="3" class="text-center">Liabilities</td>
            </tr>
            <tr>
              <td>Current Liabilities</td>
              <td></td>
            </tr>
            @foreach($trial_balance as $account => $value)
            @if($value[2] == 'Liabilities' && $value[3] == 'current')
            <tr>
              <td style="padding-left: 80px;">{{$account}}</td>
              <td>@money($value[0])</td>
              <?php $total_cl += $value[0]; ?>
            </tr>
            @endif
            @endforeach
            <tr class="bg-light-custom">
              <td>Total Current Liabilities</td>
              <td>@money($total_cl)</td>
            </tr>
            <tr>
              <td>Non-current Liabilities</td>
              <td></td>
            </tr>
            @foreach($trial_balance as $account => $value)
            @if($value[2] == 'Liabilities' && $value[3] == 'non_current')
            <tr>
              <td style="padding-left: 80px;">{{$account}}</td>
              <td>@money($value[0])</td>
              <?php $total_ncl += $value[0]; ?>
            </tr>
            @endif
            @endforeach
            <tr class="bg-light-custom">
              <td>Total Non-current Liabilities</td>
              <td>@money($total_ncl)</td>
            </tr>
            <tr class="bg-light-custom">
              <td>Total Liabilities</td>
              <td>@money($total_cl + $total_ncl)</td>
            </tr>
            <tr>
              <td colspan="3" class="text-center">Owner's Equity</td>
            </tr>
            <tr class="bg-light-custom">
              <td>Total Owner's Equity</td>
              @if(!empty($owners_equity))
              <?php $total_oe = $owners_equity['total'];  ?>
              <td>@money($total_oe)</td>
              @else 
              <td>@money($total_oe)</td>
              @endif
            </tr>
            <tr class="bg-light-custom">
              <td>Total Liabilities and Owner's Equity</td>
              <td class="final">@money($total_oe + $total_cl + $total_ncl)</td>
            </tr>
            @else
              <td class="text-center">No data available</td>
            @endif
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



@endsection