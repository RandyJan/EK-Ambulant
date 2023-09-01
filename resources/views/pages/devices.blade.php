@extends('layouts.master')

@section('title','Devices')

@section('content')
<div class="row">
    @foreach( $devices as $item)
    <div class="col-md-12">
        <a href="{{ route('device.select', ['id' => $item->_id]) }}">
            <div class="card">
                @if ($item->status == 0)
                <div class="overlay-dark bg-success">
                    @else
                    <div class="overlay-dark bg-danger">
                        @endif

                        <div class="card-block p-2">
                            <div class="">
                                <!-- <div class="tag tag-success">Available</div> -->
                                <h2>
                                    {{-- {{ $item->name }} - {{ $item->device_no }} --}}
                                    {{ $item->name }} - {{ $item->_id }}
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