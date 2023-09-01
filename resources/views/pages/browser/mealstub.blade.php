@extends('layouts.browser.master')

@section('title', 'BROWSER')



@section('content')  
<div class="page-title">
    <h4>Mealstubs</h4>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
                <div class="table-overflow">
                    <table id="items" class="table table-lg table-hover">
                        <thead>
                            <tr>  
                                <th>BRANCHID</th>
                                <th>PARTICULARS</th> 
                                <th>REFERENCE</th> 
                                <th>CREATED AT</th> 
                                <th>VALID UNTIL</th>
                                <th>USED?</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item) 
                            <tr>  
                                <td width="120">
                                    <div class="mrg-top-15">
                                        <span class="text-info"><b>{{ $item->BRANCHID }}</b></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="list-info mrg-top-10">
                                        <!-- <img class="thumb-img" src="/assets/images/avatars/thumb-1.jpg" alt=""> -->
                                        <div class="info pl-0">
                                            <span class="title">{{ $item->PARTICULARS }}</span>
                                            <span class="sub-title">{{ $item->SERIALNUMBER }}</span>
                                        </div>
                                    </div>
                                </td> 
                                <td>
                                    <div class="mrg-top-15">
                                        <b class="text-dark font-size-16">{{ $item->REFERENCE_ID }}</b>
                                    </div>
                                </td>
                                <td width="120">
                                    <div class="mrg-top-15">
                                        <span class="text-info"><b>{{ $item->DATECREATED }}</b></span>
                                    </div>
                                </td>
                                <td width="120">
                                    <div class="mrg-top-15">
                                        <span class="text-info"><b>{{ $item->DATEVALIDITY }}</b></span>
                                    </div>
                                </td>
                                <td width="120">
                                    <div class="mrg-top-15">
                                        <span class="text-info"><b>{{ $item->STATUS == 1? 'YES':'NO' }}</b></span>
                                    </div>
                                </td>
 
                            </tr> 
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection

@section('css')
<!-- page plugins css -->
<link rel="stylesheet" href="/bower_components/datatables/media/css/jquery.dataTables.css" />
@endsection

@section('js')
     <!-- page plugins js -->
     <script src="/bower_components/datatables/media/js/jquery.dataTables.js"></script>

    <script>
        (function ($) {
            // 'use strict';  

            $('#items').DataTable(); 

        })(jQuery);
    </script>
@endsection