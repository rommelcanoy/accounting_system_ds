@extends('layouts.master')

@section('title')
Company Information
@endsection

@section('header')
Company Information
@endsection

@section('company_info')
active
@endsection

@section('content')
<div class="row top-buffer justify-content-center">

    <div class="col-xl-12 col-md-12 mb-4">
        <div class="card shadow h-100 py-2">
            <div class="card-body">
                <button class="btn btn-custom" data-toggle="modal" data-target="#company_info">Add
            </div>
        </div>
    </div>
</div>

<!-- Company Modal -->
<div class="modal fade" id="company_info" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" class="ml-4 mt-2">
                <div class="modal-header">
                    <h4 class="modal-title">Company Information</h4>
                    {{-- <button type="button" class="close" data-dismiss="modal">&times;</button>  --}}
                    <button class="btn btn-sm btn-custom" type="button" class="close"
                        data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <span id="result"></span>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Company name</label>
                            <input type="text" id="company_name" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Company Owner</label>
                            <input type="text" id="company_name" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Business Organization</label>
                            <select class="form-control">
                                <option>Sole Proprietorship</option>
                                <option>Partnership</option>
                                <option>Corporation</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Types of Business</label>
                            <select class="form-control">
                                <option>Service Business</option>
                                <option>Merchandising Business</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                    <input type="hidden" name="button_action" id="button_action" value="insert">
                    <input type="submit" name="submit" id="action" class="btn btn-sm btn-custom" value="Edit">
                </div>
            </form>
        </div>
    </div>
</div> <!-- Company Modal -->
@endsection