@extends('layouts.master')

@section('title','New Orderslip')

@section('content')
    <div class="col-md-12">
        <form action="/orderslip/create" method="POST" id="orderslip-create">
            @csrf
            <div class="form-group text-center text-semibold pl-4 pr-4">
                <label for="code">Enter Headcount</label>
                <input value="" required min="1" name="headcount" type="number" class="form-control text-center input-lg" oninput="validity.valid||(value='');">
                <br>
                <!-- <label for="code">Enter OS Number</label>
                <input  name="os_number" type="text" class="form-control text-center input-lg">
                <br> -->
                <button type="submit" class="mt-3 btn btn-inverse btn-primary">CREATE ORDERSLIP</button>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/bower_components/jquery-validation/dist/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#orderslip-create").validate();
        });
    </script>
@endsection