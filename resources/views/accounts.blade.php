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
<div class="row top-buffer justify-content-center">
    <div class="col-xl-12 col-md-12 mb-4">
        <span id="result1"></span>
        <div class="card shadow h-100">
            <div class="card-body">
                <div class="mb-3">
                    <button class="btn btn-custom" data-toggle="modal" data-target="#add_account"><i
                            class="fas fa-plus"></i> Add Account</button>
                    <a href="/print_accounts" class="btn btn-custom float-right"><i
                            class="fas fa-print mr-2"></i>Print</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="table_data">
                        <thead>
                            <tr>
                                <th>Account No.</th>
                                <th>Account Title</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- Add Account Modal -->
<div class="modal fade" id="add_account" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Account</h4>
                {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" id="account_form">
                <div class="modal-body">
                    <span id="result"></span>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="account_table">
                            <thead>
                                <tr>
                                    <th width="20%">Account No.</th>
                                    <th width="35%">Account Title</th>
                                    <th width="35%">Account Type</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                            </tbody>
                            <tfoot>
                                @csrf
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="save" id="save" class="btn btn-custom" value="Add">
                </div>
            </form>
        </div>
    </div>
</div> <!-- Add Account Modal -->

<div class="modal fade" id="update_account" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Account</h4>
                {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" id="update_form">
                @csrf
                <div class="modal-body">
                    <span id="result2"></span>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="account_table">
                            <thead>
                                <tr>
                                    <th>Account No.</th>
                                    <th>Account Title</th>
                                    <th>Account Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="account_no" id="account_no" class="form-control">
                                    </td>
                                    <td><input type="text" name="account_title" id="account_title" class="form-control">
                                    </td>
                                    <td>
                                        <select class="form-control" name="account_type" id="account_type">
                                            <option value="">Select here</option>
                                            <option value="Assets">Assets</option>
                                            <option value="Liabilities">Liabilities</option>
                                            <option value="Capital">Capital</option>
                                            <option value="Withdrawal">Withdrawal</option>
                                            <option value="Income">Income</option>
                                            <option value="Expense">Expense</option>
                                        </select>
                                    </td>
                                    <input type="hidden" name="hidden_id" id="hidden_id" value="">
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="update" id="update" class="btn btn-custom" value="Update">
                </div>
            </form>
        </div>
    </div>
</div> <!-- Update Account Modal -->

<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Account</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to remove this data?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Ok</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>{{-- Confirmation Modal --}}

@endsection

@section('scripts')
<script>

    $(document).ready(function () {

        $('#table_data').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('account.index') }}",
            },
            columns: [
                {
                    data: 'account_no',
                    name: 'account_no'
                },
                {
                    data: 'account_title',
                    name: 'account_title'
                },
                {
                    data: 'account_type',
                    name: 'account_type'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        })

        var count = 1;

        account_field(count);

        function account_field(number) {
            var html = '<tr>';
            html += '<td><input type="number" name="account_no[]" class="form-control" placeholder="Enter no."></td>';
            html += '<td><input type="text" name="account_title[]" class="form-control" placeholder="Enter account"></td>';
            html += '<td><select class="form-control" name="account_type[]"><option value="">Select here</option><option value="Assets">Assets</option><option value="Liabilities">Liabilities</option><option value="Capital">Capital</option><option value="Withdrawal">Withdrawal</option><option value="Income">Income</option><option value="Expense">Expense</option></select></td>';
            html += '<input type="hidden" name="user_id[]" class="form-control" value="{{$user_id}}">';
            if (number > 1) {
                html += '<td><button type="button" name="remove" id="" class="btn btn-sm btn-danger remove"><i class="fas fa-times"></i></button></td></tr>';
                $('#tbody').append(html);
            }
            else {
                html += '<td><button type="button" name="add" id="add" class="btn btn-sm btn-custom"><i class="fas fa-plus"></i></button></td></tr>';
                $('#tbody').html(html);
            }

            console.log(count);
        }

        $(document).on('click', '#add', function () {
            count++;
            account_field(count);
        })

        $(document).on('click', '.remove', function () {
            count--;
            $(this).closest("tr").remove();
        });

        $('#account_form').on('submit', function () {
            event.preventDefault();
            $.ajax({
                url: '{{ route("account.store") }}',
                method: 'post',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $(".loader").show();
                    $('#save').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if (data.error) {
                        var error_html = '';
                        for (var count = 0; count < data.error.length; count++) {
                            error_html += '<p>' + data.error[count] + '</p>'
                        }
                        $('#result').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + error_html + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }
                    else if (data.account_title_error) {
                        $('#result').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.account_title_error + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }
                    else if (data.account_no_error) {
                        $('#result').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.account_no_error + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }
                    else {
                        account_field(1);
                        // $('#result').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + data.success + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                        // toast_message('Data successfully added312312', 'fas fa-check-circle text-success', '4px solid #38C172;');
                        $('.alert').hide();
                        toast_message('success', 'Data successfully added.');
                        $('#table_data').DataTable().ajax.reload();

                    }

                    $('#save').attr('disabled', false);
                },
                complete: function (data) {
                    $(".loader").hide();
                }
            })
        });

        $('#update_form').on('submit', function (event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('account.update') }}",
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
                        $('#result2').html(html);

                    }
                    if (data.success) {
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                        html += data.success + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        $('#update_form')[0].reset();
                        $('#table_data').DataTable().ajax.reload();
                        $('#update_account').modal('hide');
                        // $('#result1').html(html);
                        toast_message('success', 'Data successfully updated.');
                    }
                },
                complete: function (data) {
                    $(".loader").hide();
                }
            })
        });

        $(document).on('click', '.edit', function () {
            var id = $(this).attr('id');
            $('.result').html('');
            $.ajax({
                url: "/account/" + id + "/edit",
                dataType: "json",
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (html) {
                    $('#account_no').val(html.data.account_no);
                    $('#account_title').val(html.data.account_title);
                    $('#account_type').val(html.data.account_type);
                    $('#hidden_id').val(html.data.id);
                    // $('.modal-title').text("Edit New Record");
                    // $('#action_button').val("Edit");
                    // $('#action').val("Edit");
                    $('#update_account').modal('show');
                },
                complete: function (data) {
                    $(".loader").hide();
                }
            })
        });

        $(document).on('click', '.delete', function () {
            user_id = $(this).attr('id');
            $('#confirmModal').modal('show');
            // $(".toast").toast('show');
        });

        $('#ok_button').click(function () {

            $.ajax({
                url: "account/destroy/" + user_id,
                beforeSend: function () {
                    $('#ok_button').text('Deleting...');
                },
                success: function (data) {
                    $('#table_data').DataTable().ajax.reload();
                    $('#confirmModal').modal('hide');
                    $('#ok_button').text('Ok');
                    // $('#result1').html('<div class="alert alert-success alert-dismissible fade show" role="alert">Data successfully deleted.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    toast_message('success', 'Data successfully deleted.');
                },
                // complete: function(data){
                // }
            })
        });


    });
</script>
@endsection