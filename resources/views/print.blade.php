@extends('layouts.master')

@section('title')
Journal
@endsection

@section('header')
Journal
@endsection

@section('journal_entries')
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
                <h5 class="card-title text-center">General Journal</h5>
                <div class="table-responsive">
                    <table class="table table-bordered mt-4" id="table_data">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Account Title</th>
                                <th>Description</th>
                                <th>P.R.</th>
                                <th>Debit</th>
                                <th>Credit</th>
                            </tr>
                            <tr>
                                @if(count($journal) > 0)
                                    @foreach($journal as $data)
                                        <tr>
                                            <td>{{ date('M-d-Y', strtotime($data->date)) }}</td>
                                            <td>{{$data->account_title}}</td>
                                            <td>{{$data->description}}</td>
                                            <td>{{$data->account_no}}</td>
                                            <td>{{$data->debit}}</td>
                                            <td>{{$data->credit}}</td>
                                        </tr>
                                    @endforeach
                                @else 
    
                                @endif
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>{{--shows all data --}}

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            window.print();
        }); 

        var mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function(mql) {
                if (mql.matches) {
                    // window.location.href = "http://127.0.0.1:8000/journal";
                } else {
                    window.location.href = "/journal";
                }
        });         

    </script>
@endsection