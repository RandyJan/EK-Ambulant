@extends('layouts.master')

@section('title','Select Products')

@section('content')
    @include('alerts.alert')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('categories') }}">Category</a> / <span class="text-info"> {{$group->description}}</span>  </li>
            <li class="breadcrumb-item active"><a href="{{ url()->full() }}">Products</a></li>
        </ol>
    </nav>
    <br>

    <div class="row">

        @if( count($items) > 0 )

            @foreach($items as $item)
                <div class="col-12 col-sm-4 col-md-3  py-0 px-1 mb-2 ">
                    <a href="{{ route('product-selected', [
                        'outlet_id' => $item->outlet_id,
                        'product_id' => $item->product_id
                    ]) }}">
                <div class="card h-100 mb-3 espire" >
                    <div class="row no-gutters h-100">
                      <div class="col-3 col-sm-4 col-md-4 ">
                          <div class="h-100" style="overflow: hidden">
                            <img
                             class="img-fluid"
                              style="object-fit: cover !important; width: 100px; height: 100%;"
                              src="{{ $item->part->img_url ? Storage::url('images/'.$item->part->img_url):'/assets/images/default-product.png' }}"
                              alt="">

                          </div>
                      </div>
                      <div class="col-9 col-sm-8 col-md-8 ">
                        <div class="card-body p-2">
                            <span class="badge badge-info">{{ $item->category_id }}</span>
                            <div class="mt-1 mb-1 text-primary font-size-16 mt-1"><b>{{ number_format($item->retail,2) }}</b></div>
                            <b class="text-dark font-size-14">{{ strtoupper($item->short_code) }}</b>
                        </div>
                      </div>
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
        {{  $items->links() }}
    </div>
@endsection

@section('js')
@endsection
