@extends('layouts.master')

@section('title')
Statement of Cash Flows
@endsection

@section('header')
Statement of Cash Flows
@endsection

@section('reports')
active
@endsection

@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb shadow">
    <li class="breadcrumb-item"><a href="/reports">Reports</a></li>
    <li class="breadcrumb-item active" aria-current="page">Statement of Cash Flows</li>
    <button class="btn btn-sm btn-custom ml-auto print"><i class="fas fa-print mr-2"></i>Print</button>
  </ol>
</nav>
<div class="row top-buffer justify-content-center">
    <div class="col-xl-12 col-md-12 mb-4">
        {{-- <span id="result1"></span> --}}
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title text-center">Company Name</h5>
                <h5 class="card-title text-center">Statement of Cash Flows</h5>
                <h5 class="card-title text-center">No Date</td>
                <div class="table-responsive">
                    <table class="table table-bordered mt-4" id="table_data">
                        <tr>
                            <th class="text-center">This report is not available.</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection