@extends('layouts.master')

@section('title')
Chart of Accounts
@endsection

@section('header')
Chart of Accounts
@endsection

@section('manage_accounts')
active
@endsection

@section('content')
<div class="row top-buffer" onafterprint="test()">
    <div class="col-xl-12 col-md-12 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title text-center">
                    @if(count($company_data) > 0)
                    {{$company_data[0]->company_name}}
                    @else
                    Add Company Name
                    @endif
                </h5>
                <h5 class="card-title text-center">Chart of Accounts</h5>
                <div class="table-responsive">
                    <table class="table table-bordered mt-4" id="table_data">
                        @if(count($accounts) > 0)
                        <tr>
                            <td colspan="2">ASSETS</td>
                        </tr>
                        @foreach($accounts as $data)
                            @if($data->account_type == 'Assets')
                                <tr>
                                <td width="20%">{{$data->account_no}}</td>
                                <td width="80%">{{$data->account_title}}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="2">LIABILITIES</td>
                        </tr>
                        @foreach($accounts as $data)
                            @if($data->account_type == 'Liabilities')
                                <tr>
                                <td width="20%">{{$data->account_no}}</td>
                                <td width="80%">{{$data->account_title}}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="2">OWNER'S EQUITY</td>
                        </tr>
                        @foreach($accounts as $data)
                            @if($data->account_type == 'Capital' || $data->account_type == 'Withdrawal')
                                <tr>
                                <td width="20%">{{$data->account_no}}</td>
                                <td width="80%">{{$data->account_title}}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="2">INCOME/REVENUE</td>
                        </tr>
                        @foreach($accounts as $data)
                            @if($data->account_type == 'Income')
                                <tr>
                                <td width="20%">{{$data->account_no}}</td>
                                <td width="80%">{{$data->account_title}}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="2">EXPENSE</td>
                        </tr>
                        @foreach($accounts as $data)
                            @if($data->account_type == 'Expense')
                                <tr>
                                <td width="20%">{{$data->account_no}}</td>
                                <td width="80%">{{$data->account_title}}</td>
                                </tr>
                            @endif
                        @endforeach
                        @else
                            <tr>
                                <td colspan="2">No data found</td>
                            </tr>
                        @endif
                        {{-- <tr>
                                <th>Account No.</th>
                                <th>Account Title</th>
                                <th>Account Type</th>
                            </tr>
                            <tr>
                                @if(count($accounts) > 0)
                                    @foreach($accounts as $data)
                                        <tr>
                                            <td>{{$data->account_no}}</td>
                        <td>{{$data->account_title}}</td>
                        <td>{{$data->account_type}}</td>
                        </tr>
                        @endforeach
                        @else

                        @endif
                        </tr> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>{{--shows all data --}}

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        window.print();
    });

    var mediaQueryList = window.matchMedia('print');
    mediaQueryList.addListener(function (mql) {
        if (mql.matches) {
            // window.location.href = "http://127.0.0.1:8000/journal";
        } else {
            window.location.href = "/account";
        }
    });

</script>
@endsection