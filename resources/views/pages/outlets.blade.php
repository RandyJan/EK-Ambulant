@extends('layouts.master')

@section('title','Tables')

@section('content') 
<div class="row">
    @foreach( $outlets as $item)
    <div class="col-md-12"> 
        <a href="{{ route('outlet.select', ['id' => $item->outlet_id]) }}">
            <div class="card">
                <div class="overlay-dark bg-success">
                    <div class="card-block p-2">
                        <div class="">
                            <!-- <div class="tag tag-success">Available</div> -->
                            <h2>
                                {{ $item->description }}
                            </h2>
                            <!-- <div class="text-white text-opacity">
                                <span>
                                        <i class="ti-user pdd-right-5"></i>
                                        <span> asd </span>
                                </span> 
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </a> 
    </div>
    @endforeach
</div>
@endsection