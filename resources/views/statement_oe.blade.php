@extends('layouts.master')

@section('title')
Statement of Owner's Equity
@endsection

@section('header')
Statement of Owner's Equity
@endsection

@section('reports')
active
@endsection

@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb shadow">
    <li class="breadcrumb-item"><a href="/reports">Reports</a></li>
    <li class="breadcrumb-item active" aria-current="page">Statement of Owner's Equity</li>
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
                <h5 class="card-title text-center">Statement of Owner's Equity</h5>
                <h5 class="card-title text-center">
                  @if($data['last_date'] != null)
                    {{date('F-d-Y', strtotime($data['last_date']))}}
                  @else 
                    No date
                  @endif
                  </h5>

                <div class="table-responsive">
                  <table class="table table-hover mt-4" id="table_data">
                    @if($data['first_date'] != null && $data['last_date'] != null)
                    <tr>
                      <td width="50%">{{$data['capital_title']. ', ' . date('F-d-Y', strtotime($data['first_date']))}}</td>
                      <td>@money($data['capital'])</td>
                    </tr>
                    <tr>
                      <td>Additional Investments</td>
                      <td>@money($data['additional_i'])</td>
                    </tr>
                    @if($data['net_income'] > $data['net_loss'])
                    <tr>
                      <td>Net Income</td>
                      <td>@money($data['net_income'])</td>
                    </tr>
                    @endif
                    <tr>
                      <td>Subtotal</td>
                      <td>@money($data['capital'] + $data['additional_i'] + $data['net_income'])</td>
                    </tr>
                    <tr>
                      <td>Withdrawals</td>
                      <td>@money($data['withdrawals'])</td>
                    </tr>
                    @if($data['net_loss'] > $data['net_income'])
                      <tr>
                        <td>Net Loss</td>
                        <td>@money($data['net_loss'])</td>
                      </tr>
                    @endif
                    <tr class="bg-light-custom">
                      <td>{{$data['capital_title']. ', ' . date('F-d-Y', strtotime($data['last_date']))}}</td>
                      <td class="final">@money($data['total'])</td>
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