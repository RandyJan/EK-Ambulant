@extends('layouts.browser.master')

@section('title', 'BROWSER')



@section('content') 
<div class="page-title">
    <h4>User Devices</h4>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
                <div class="table-overflow">
                    <table id="user-devices" class="table table-lg table-hover">
                        <thead>
                            <tr>  
                                <th>NAME</th>
                                <th>USERNAME</th>
                                <th>PASSWORD</th>
                                <th>DEVICEID</th>
                                <th>OUTLETID</th>
                                <th>BRANCHID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)

                            <tr> 
                                <td>
                                    <div class="list-info mrg-top-10">
                                        <!-- <img class="thumb-img" src="/assets/images/avatars/thumb-1.jpg" alt=""> -->
                                        <div class="info pl-0">
                                            <span class="title">{{ $item->NAME }}</span>
                                            <span class="sub-title">ID {{ $item->ID }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="relative mrg-top-15"> 
                                        <span class="pdd-left-20">{{ $item->NUMBER }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="relative mrg-top-15"> 
                                        <span class="pdd-left-20">{{ $item->PW }}</span>
                                    </div>
                                </td> 
                                <td>
                                    <div class="list-info mrg-top-15"> 
                                        <div class="info pl-0">
                                            <span class="title">{{ strtoupper($item->device['DESC']) }}</span>
                                            <span class="sub-title text-info">ID {{ $item->DEVICEID }}</span>
                                        </div>  
                                    </div>
                                </td>
                                <td> 

                                    <div class="list-info mrg-top-15"> 
                                        <div class="info pl-0">
                                            <span class="title">{{ $item->outlet->DESCRIPTION }} </span>
                                            <span class="sub-title text-info">ID {{ $item->OUTLETID }}</span>
                                        </div>  
                                    </div>
                                </td> 
                                <td>
                                    <div class="mrg-top-15">
                                        <span class="text-info"><b>{{ $item->BRANCHID }}</b></span>
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

            $('#user-devices').DataTable(); 

        })(jQuery);
    </script>
@endsection