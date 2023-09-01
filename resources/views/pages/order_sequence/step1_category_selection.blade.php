@extends('layouts.master')

@section('title','Select Category')

@section('content')
    @include('alerts.alert')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ route('categories') }}">Category</a></li>
        </ol>
    </nav>
    <br>
    {{-- group_id == 'BV1DP' || $group->group_id == 'DS1CU' || $group->group_id == 'DS1DE' --}}
    <div class="row">
        @if( $groups->count() > 0)
            @foreach($groups as $group)





            <div class="col-4 col-sm-3 col-md-2 py-0 px-1 mb-2">
                <a href="{{ route('categories.products', ['group' => $group->group_id]) }}">
                    <div class="card h-100 mb-0 group espire" data-category="{{ $group->group_id }}">
                        <div class="card-block h-100 d-flex align-items-center p-3">
                            <ul class="list-info">
                                <li class="">
                                    <div class="info  pl-0" style="line-height: 1;">

                                        <b class="text-primary font-size-13 mt-1 word-break">{{ strtoupper($group->description) }}</b>

                                    </div>
                                </li>
                            </ul>

                        </div>
                    </div>
                </a>
            </div>


            @endforeach
        @else
            <div class="col-md-12">
                0 results
            </div>
        @endif
    </div>
    <div class="d-flex justify-content-end mt-2">
        {{  $groups->links() }}
    </div>
@endsection

@section('js')
    <script>
        $('.group').on('click', function() {
            let group_id = $(this).data('category');
            // console.log(group_id)
            setStorage('selected-category',group_id);
        });
    </script>
@endsection
{{-- @extends('layouts.master')

@section('title','Select Category')

@section('content')
    @include('alerts.alert')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ route('categories') }}">Category</a></li>
        </ol>
    </nav>
    <br>
    <div class="row">
        @if( $groups->count() > 0)
            @foreach($groups as $group)
                <div class="col-4 col-sm-3 col-md-2 py-0 px-1 mb-2 ">
                    @if(Empty($group->produ))
                        <div class="card h-100 mb-0 group espire" data-category="{{ $group->group_id }}">
                            <div class="card-block h-100 d-flex align-items-center p-3">
                                <ul class="list-info">
                                    <li class="" >
                                        <div class="info  pl-0" style="line-height: 1;">
                                            <b class="text-primary font-size-13 mt-1 word-break">{{ strtoupper($group->description) }} this</b>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('categories.products', ['group' => $group->group_id]) }}">
                            <div class="card h-100 mb-0 group espire" data-category="{{ $group->group_id }}">
                                <div class="card-block h-100 d-flex align-items-center p-3">
                                    <ul class="list-info">
                                        <li class="">
                                            <div class="info  pl-0" style="line-height: 1;">
                                                <b class="text-primary font-size-13 mt-1 word-break">{{ strtoupper($group->description) }}</b>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            @endforeach
        @else
            <div class="col-md-12">
                0 results
            </div>
        @endif
    </div>
    <div class="d-flex justify-content-end mt-2">
        {{  $groups->links() }}
    </div>
@endsection

@section('js')
    <script>
        $('.group').on('click', function() {
            let group_id = $(this).data('category');
            // console.log(group_id)
            setStorage('selected-category',group_id);
        });
    </script>
@endsection --}}
