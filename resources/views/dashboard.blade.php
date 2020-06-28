@extends('layouts.master')

@section('title')
Dashboard
@endsection

@section('header')
Dashboard
@endsection

@section('dashboard')
active
@endsection

@section('content')
{{-- @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>
@endif --}}
<div class="row top-buffer">
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card shadow h-100 py-2 sales">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Assets</div>
                        <div class="h5 mb-0 font-weight-bold gray">@money($total_assets)</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-coins fa-2x text-gray-300 dashboard_text"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card shadow h-100 py-2 sales">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Liabilities</div>
                        <div class="h5 mb-0 font-weight-bold gray">@money($total_liabilities)</span></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-receipt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card shadow h-100 py-2 sales">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Owner's Equity</div>
                        <div class="h5 mb-0 font-weight-bold gray">@money($total_oe)</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 col-lg-12">
        <div class="class shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="h6 mb-1">Company Information</h6>
                <button class="btn btn-custom company_button" value="add"></button>

                {{-- <div class="dropdown no-arrow show">
                    <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="true">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink"
                        x-placement="bottom-end"
                        style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-147px, 17px, 0px);">
                        <a href="#" class="dropdown-item company_button" value="add">Edit</a>
                    </div>
                </div> --}}
            </div>
            <div class="card-body body-custom">
                {{-- <div class="table-wrapper-scroll-y my-custom-scrollbar"> --}}
                <div>
                    {{-- <table class="table table-hover table-bordered">
                        @if(count($company) > 0)  
                            @foreach($company as $comp)
                                <tr>
                                    <th width="50%">Company Name</th>
                                    <td>{{$comp->company_name}}</td>
                    <input type="hidden" id="company_id" value="{{$comp->id}}">
                    </tr>
                    <tr>
                        <th>Company Owner</th>
                        <td>{{$comp->company_owner}}</td>
                    </tr>
                    <tr>
                        <th>Business Organization</th>
                        @if($comp->business_org == 1)
                        <td>Sole Proprietorship</td>
                        @elseif($comp->business_org == 2)
                        <td>Partnership</td>
                        @else
                        <td>Corporation</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Business Type</th>
                        @if($comp->business_type == 1)
                        <td>Service Business</td>
                        @else
                        <td>Merchandise Business</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2"><button class="btn btn-custom btn-sm float-right company_button">Edit</button>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    </table> --}}
                    <div class="table-responsive">
                        <table id='userTable' class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Company Name</th>
                                    <th>Company Owner</th>
                                    <th>Business Organization</th>
                                    <th>Business Type</th>
                                <input type="hidden" id="company_id" value="">
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>   
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Company Modal -->
<div class="modal fade" id="company_info" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Company Information</h4>
                {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" id="company_form">
                @csrf
                <div class="modal-body">
                    <span id="result"></span>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Company name</label>
                            <input type="text" id="company_name" class="form-control" name="company_name" value="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Company Owner</label>
                            <input type="text" id="company_owner" class="form-control" name="company_owner">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Business Organization</label>
                            <select class="form-control" name="business_org" id="business_org">
                                <option value="">Select here</option>
                                <option value="1">Sole Proprietorship</option>
                                <option value="2">Partnership</option>
                                <option value="3">Corporation</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Types of Business</label>
                            <select class="form-control" name="business_type" id="business_type">
                                <option value="">Select here</option>
                                <option value="1">Service Business</option>
                                <option value="2">Merchandising Business</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id" id="user_id" value="{{$user_id}}">
                    <input type="hidden" name="hidden_id" id="hidden_id" value="">
                    <input type="hidden" name="action" id="action" value="add">
                    <input type="submit" name="submit" id="action_button" class="btn btn-custom" value="Edit">
                </div>
            </form>
        </div>
    </div>
</div> <!-- Company Modal -->
@endsection



@section('scripts')
<script>

    $(document).ready(function () {
        var user_id = '{{$user_id}}';
        fetchRecords(user_id);

        $('.company_button').click(function () {

            // var company = '{{count($company)}}';
            $('#hidden_id').val($('#company_id').val());

            if ($('.company_button').val() == 'edit') {
                $('#result').html('');
                $.ajax({
                    url: "/dashboard/" + $('#company_id').val() + '/edit',
                    dataType: "json",
                    beforeSend: function () {
                        $(".loader").show();
                    },
                    success: function (html) {
                        $('#company_name').val(html.data.company_name);
                        $('#company_owner').val(html.data.company_owner);
                        $('#business_org').val(html.data.business_org);
                        $('#business_type').val(html.data.business_type);
                        // $('#hidden_id').val(html.data.id);
                        $('#action_button').val('Edit');
                        $('#action').val('edit');
                        $('#company_info').modal('show');
                    },
                    complete:function(data){
                        $(".loader").hide();
                    }
                })
            }
            else {
                $('#company_form')[0].reset();
                $('#result').html('');
                $('#action').val('add');
                $('#action_button').val('Add');
                $('#company_info').modal('show');
            }
        });

        $('#company_form').on('submit', function (event) {
            event.preventDefault();

            if ($('#action').val() == 'add') {
                $.ajax({
                    url: "{{route('dashboard.store') }}",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        $(".loader").show();
                    },
                    success: function (data) {
                        var html = '';
                        if (data.errors) {
                            html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                            for (var count = 0; count < data.errors.length; count++) {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }
                        if (data.success) {
                            html = '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                            html += data.success + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            $('#company_form')[0].reset();
                            toast_message('success', 'Data successfully updated.');
                            $('#company_info').modal('hide');
                            
                        }
                        $('#result').html(html);
                        fetchRecords(user_id);
                    },
                    complete:function(data){
                        $(".loader").hide();
                    }
                })
            }
            if ($('#action').val() == 'edit') {
                $.ajax({
                    url: "{{route('dashboard.update') }}",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        $(".loader").show();
                    },
                    success: function (data) {
                        if (data.errors) {
                            html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                            for (var count = 0; count < data.errors.length; count++) {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }
                        if (data.success) {
                            html = '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                            html += data.success + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            $('#company_form')[0].reset();
                            toast_message('success', 'Data successfully updated.');
                            $('#company_info').modal('hide');
                        }
                        $('#result').html(html);
                        fetchRecords(user_id);

                    },
                    complete:function(data){
                        $(".loader").hide();
                    }
                })
            }
        });

    });


    function fetchRecords(id) {
        $.ajax({
            url: 'getCompany/' + id,
            type: 'get',
            dataType: 'json',
            beforeSend: function () {
                $(".loader").show();
            },
            success: function (response) {

                var len = 0;
                $('#userTable tbody').empty(); // Empty <tbody>
                if (response['data'] != null) {
                    len = response['data'].length;
                }

                if (len > 0) {
                    $('.company_button').text('Update Company Information');
                    $('.company_button').val('edit');
                    for (var i = 0; i < len; i++) {
                        var company_id = response['data'][i].id;
                        var company_name = response['data'][i].company_name;
                        var company_owner = response['data'][i].company_owner;
                        var business_org = response['data'][i].business_org;
                        var business_type = response['data'][i].business_type;

                        if(business_org == 1){
                            business_org = 'Sole Proprietorship';
                        }
                        else if(business_org == 2){
                            business_org = 'Partnership';
                        }
                        else {
                            business_org = 'Corporation';
                        }

                        if(business_type == 1){
                            business_type = 'Service Business';
                        }
                        else {
                            business_type = 'Merchandise Business';
                        }

                        var tr_str = "<tr>" +
                            "<td align='center'>" + company_name + "</td>" +
                            "<td align='center'>" + company_owner + "</td>" +
                            "<td align='center'>" + business_org + "</td>" +
                            "<td align='center'>" + business_type + "</td>" +
                            "</tr>";

                        $('#company_id').val(company_id);   

                        $("#userTable tbody").append(tr_str);
                    }
                } else {
                    $('.company_button').val('add');
                    $('.company_button').text('Add Company Information');

                    var tr_str = "<tr>" +
                        "<td align='center' colspan='4'>No record found.</td>" +
                        "</tr>";

                    $("#userTable tbody").append(tr_str);
                }

            },
            complete:function(data){
               $(".loader").hide();
            }
        });
    }
</script>
@endsection