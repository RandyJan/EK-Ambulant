@extends('layouts.browser.master')

@section('title', 'BROWSER')



@section('content') 
<div class="page-title">
    <h4>Terminals</h4>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
                <div class="table-overflow">
                    <table id="items" class="table table-lg table-hover">
                        <thead>
                            <tr>  
                                <th>ID</th> 
                                <th>NAME</th>
                                <th>POS ID</th>
                                <th>BRANCH</th>
                                <th>OUTLET</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item) 
                            <tr>  

                                <td width="120">
                                    <div class="mrg-top-15">
                                        <span class="text-info"><b>{{ $item->ID }}</b></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="mrg-top-15">
                                        <span>{{ $item->DESC }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="mrg-top-15">
                                        <b class="text-dark font-size-16">{{ $item->TERMNO }}</b>
                                    </div>
                                </td>
                                <td>
                                    <div class="list-info mrg-top-10">
                                        <!-- <img class="thumb-img" src="/assets/images/avatars/thumb-1.jpg" alt=""> -->
                                        <div class="info pl-0">
                                            <span class="title">{{ $item->branch->BRANCHNAME }}</span>
                                            <span class="sub-title">ID: {{ $item->STATIONCODE }}</span>
                                        </div>
                                    </div>
                                </td>  
                                <td>
                                    <div class="list-info mrg-top-10">
                                        <!-- <img class="thumb-img" src="/assets/images/avatars/thumb-1.jpg" alt=""> -->
                                        <div class="info pl-0">
                                            <span class="title">{{ $item->getOutletByBranchId($item->STATIONCODE)['DESCRIPTION'] }}</span>
                                            <span class="sub-title">ID: {{ $item->OUTLETID }}</span>
                                        </div>
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