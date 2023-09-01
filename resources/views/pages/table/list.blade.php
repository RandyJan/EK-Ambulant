@extends('layouts.master')

@section('title','Tables')

@section('content') 
<div class="row">
    @foreach( $tables as $table)
    <div class="col-md-3">
        @if($table->status2 == 0)
            <a href="/orderslip/{{Auth::user()->activeOrder()->orderslip_header_id}}/table/{{$table->id}}/create">
                <div class="card">
                    <div class="overlay-dark bg-success">
                        <div class="card-block">
                            <div class="">
                                <div class="tag tag-success">Available</div>
                                <h2>
                                    {{ $table->description }}
                                </h2>
                                <div class="text-white text-opacity">
                                    <span>
                                            <i class="ti-user pdd-right-5"></i>
                                            <span> {{ $table->guests }}</span>
                                    </span> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @else 
            <a href="javascript:void(0);">
                <div class="card">
                    <div class="overlay-dark bg-danger">
                        <div class="card-block">
                            <div class="">
                                <div class="tag tag-danger">Not Available</div>
                                <h2>
                                    {{ $table->description }}
                                </h2>
                                <div class="text-white text-opacity">
                                    <span>
                                            <i class="ti-user pdd-right-5"></i>
                                            <span> {{ $table->guests }}</span>
                                    </span> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endif 
    </div>
    @endforeach
</div>
@endsection
    
@section('js') 
    <script>
        $(document).ready(function(){
            
        });
    </script>
@endsection