@extends('layouts.master')

@section('title')
Journal Entries
@endsection

@section('header')
Journal Entries
@endsection

@section('journal_entries')
active
@endsection

@section('content')
<span id="result1"></span>
<div class="row top-buffer">
    <div class="col-xl-12 col-md-12 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <div class="mb-3">
                    <button class="btn btn-custom" data-toggle="modal" data-target="#journal_entry"><i
                        class="fas fa-plus"></i> Add entry</button>
                    <a href="/print" class="btn btn-custom float-right"><i class="fas fa-print mr-2"></i>Print</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="table_data">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Account Title</th>
                                <th>Description</th>
                                <th>P.R.</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>{{--shows all data --}}


<div class="modal fade" id="journal_entry" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Entry</h4>
                {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" id="journal_form">
                <div class="modal-body">
                    <span id="result"></span>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Explanation</th>
                                </tr>
                            </thead>
                            <tr>
                               <td><input type="date" name="date" class="form-control"></td>
                               <td><input type="text" name="description" class="form-control" placeholder="Enter explanation"></td>
                                
                            </tr>
                        </table>
                        <table class="table table-bordered table-hover" id="journal_table">
                            <thead>
                                <tr>
                                    {{-- <th>Date</th> --}}
                                    <th>Account Title</th>
                                    {{-- <th width="25%">Description</th> --}}
                                    <th>Entry Type</th>
                                    <th>Amount</th>
                                    <th>Action</th>
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
                    <button type="submit" name="save" id="save" class="btn btn-custom" value="Add">Add</button>
                </div>
            </form>
        </div>
    </div>
</div> <!-- Journal Entry Modal -->

<div class="modal fade" id="update_journal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Entry</h4>
                {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" id="update_journal_form">
                <div class="modal-body">
                    <span id="result2"></span>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="journal_table">
                            <thead>
                                <tr>
                                    <th width="15%">Date</th>
                                    <th width="25%">Account Title</th>
                                    <th width="25%">Description</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <tr>
                                    <td><input id="date" type="date" name="date" class="form-control"></td>
                                    <td>
                                        <select class="form-control" name="account_title" id="account_title">
                                            <option value="">Select here</option>
                                            @foreach($accounts as $acc)
                                            <option value="{{$acc->account_title}}">{{$acc->account_title}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="description" class="form-control" id="description">
                                    </td>
                                    <td><input type="number" name="debit" class="form-control" id="debit"></td>
                                    <td><input type="number" name="credit" class="form-control" id="credit"></td>
                                    <input type="hidden" name="user_id" class="form-control" value="{{$user_id}}">
                                    <input type="hidden" name="hidden_id" id="hidden_id" class="form-control">
                                </tr>
                            </tbody>
                            <tfoot>
                                @csrf
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="update" id="update" class="btn btn-custom" value="Update">
                </div>
            </form>
        </div>
    </div>
</div> <!-- Update Journal Modal -->

<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to remove this data?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>{{-- Confirmation Modal --}}
@endsection


@section('scripts')
<script>
    $(document).ready(function () {

        table = $('#table_data').DataTable({
            // 'bPaginate': false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('journal.index') }}",
            },
            columns: [
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'account_title',
                    name: 'account_title'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'account_no',
                    name: 'accounts.account_no'
                },
                {
                    data: 'debit',
                    name: 'debit'
                },
                {
                    data: 'credit',
                    name: 'credit'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        })

        // $('.print').on('click', function(){
        //     // $('#table_data').DataTable({
        //     //     "paging":false
        //     // });
        //     window.print();
        // });




        var count = 1;
        // var debit = 0;
        // var credit = 0;

        journal_field(count);

        function journal_field(number) {
            var html = '<tr>';
            // html += '<td><input type="date" name="date[]" class="form-control"></td>';
            html += '<td> <select class="form-control" name="account_title[]"> <option value="">Select here</option> <optgroup label="Assets"> @foreach($accounts as $acc) @if($acc->account_type == "Assets") <option value="{{$acc->account_title}}">{{$acc->account_title}}</option> @endif @endforeach </optgroup> <optgroup label="Liabilities"> @foreach($accounts as $acc) @if($acc->account_type == "Liabilities") <option value="{{$acc->account_title}}">{{$acc->account_title}}</option> @endif @endforeach </optgroup> <optgroup label="Owners Equity"> @foreach($accounts as $acc) @if($acc->account_type == "Income" || $acc->account_type == "Expense" || $acc->account_type == "Withdrawal" || $acc->account_type == "Capital") <option value="{{$acc->account_title}}">{{$acc->account_title}}</option> @endif @endforeach </optgroup> </select> </td>';
            // html += '<td><input type="text" name="description[]" class="form-control"></td>';
            html += '<td><select class="form-control" name="entry_type[]"><option value="">Select here</option><option value="0">Debit</option><option value="1">Credit</option></select></td>';
            html += '<td><input type="number" name="amount[]" class="form-control" placeholder="Enter amount"></td>';
            html += '<input type="hidden" name="user_id[]" class="form-control" value="{{$user_id}}">';
            if (number > 1) {
                html += '<td><button type="button" name="remove" id="" class="btn btn-sm btn-danger remove"><i class="fas fa-times"></i></button></td></tr>';
                $('#tbody').append(html);
            }
            else {
                html += '<td><button type="button" name="add" id="add" class="btn btn-sm btn-custom"><i class="fas fa-plus"></i></button></td></tr>';
                $('#tbody').html(html);
            }

        }

        $(document).on('click', '#add', function () {
            count++;
            journal_field(count);
        })

        $(document).on('click', '.remove', function () {
            count--;
            $(this).closest("tr").remove();
        });

        $('#journal_form').on('submit', function () {
            event.preventDefault();
            $.ajax({
                url: '{{ route("journal.store") }}',
                method: 'post',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $('#save').attr('disabled', 'disabled');
                    $(".loader").show();
                },
                success: function (data) {
                    if (data.error) {
                        var error_html = '';
                        for (var count = 0; count < data.error.length; count++) {
                            error_html += '<p>' + data.error[count] + '</p>'
                        }
                        $('#result').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + error_html + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }
                    else if (data.unbalance) {
                        $('#result').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.unbalance + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }
                    else {
                        $('#journal_form')[0].reset();
                        journal_field(1);
                        // $('#result').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + data.success + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                        $('.alert').hide();
                        toast_message('success', 'Entry successfully added.');
                        $('#table_data').DataTable().ajax.reload();

                    }

                    $('#save').attr('disabled', false);
                },
                complete:function(data){
                    $(".loader").hide();
                }
            })
        });

        $('#update_journal_form').on('submit', function (event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('journal.update') }}",
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
                        $('#update_journal_form')[0].reset();
                        $('#table_data').DataTable().ajax.reload();
                        $('#update_journal').modal('hide');
                        // $('#result1').html(html);
                        $('.alert').hide();
                        toast_message('success', 'Entry successfully updated.');
                    }
                },
                complete:function(data){
                    $(".loader").hide();
                }
            })
        });

        $(document).on('click', '.edit', function () {
            var id = $(this).attr('id');
            $('.result').html('');
            $.ajax({
                url: "/journal/" + id + "/edit",
                dataType: "json",
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (html) {
                    $('#date').val(html.data.date);
                    $('#account_title').val(html.data.account_title);
                    $('#description').val(html.data.description);
                    $('#entry_type').val(html.data.entry_type);
                    $('#debit').val(html.data.debit);
                    $('#credit').val(html.data.credit);
                    // $('#account_type').val(html.data.account_type);
                    $('#hidden_id').val(html.data.id);
                    // // $('.modal-title').text("Edit New Record");
                    // // $('#action_button').val("Edit");
                    // // $('#action').val("Edit");
                    $('#update_journal').modal('show');
                },
                complete:function(data){
                    $(".loader").hide();
                }
            })
        });

        $(document).on('click', '.delete', function () {
            user_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });

        $('#ok_button').click(function () {

            $.ajax({
                url: "journal/destroy/" + user_id,
                beforeSend: function () {
                    $(".loader").show();
                    $('#ok_button').text('Deleting...');
                },
                success: function (data) {
                    $('#table_data').DataTable().ajax.reload();
                    $('#confirmModal').modal('hide');
                    $('#ok_button').text('Ok');
                    // $('#result1').html('<div class="alert alert-success alert-dismissible fade show" role="alert">Data successfully deleted.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    toast_message('success', 'Entry successfully deleted.');
                },
                complete: function(data){
                    $(".loader").hide();
                }
            })
        });

    });
</script>
@endsection