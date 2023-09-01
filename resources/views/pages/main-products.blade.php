@extends('layouts.master')

@section('title','Products')

@section('content')

@include('alerts.alert')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-items"><a href="/products">Category / </a></li>
        <li class="breadcrumb-item"> <a href="/subCategory"> Sub-Category</a></li>
        <li class="breadcrumb-item active" aria-current="page">Products</li>

    </ol>
</nav>

<br>
<div class="container">
    <div class="row" id="container">

    </div>
</div>


@endsection
@section('js')
<script>
    get('/products', {
        group_id: getStorage('selected-group'),


    }, function(response) {
        // console.log(response);
        var c = $('#container'); // c = container
        c.empty();

        $.each(response.data, function(k, v) {
            c.append (
                '<div class="col-4 col-sm-3 col-md-2 p-0 mb-2">'+
                '<a href="#'+v.product_id+'" class="card h-100 mb-0 category" data-subcategory="'+v.product_id+'">'+
                    '<div class="card-block h-100 d-flex align-items-center p-3">'+
                        '<ul class="list-info">'+
                            '<li class="">'+
                                '<div class="info  pl-0" style="line-height: 1;">'+
                                    '<b class="text-primary font-size-13 mt-1">'+v.description+'</b>'+
                                '</div>'+
                            '</li>'+
                        '</ul>'+

                    '</div>'+
                '</a>'+
            '</div>'
            );
        });
    });

    $('.group').on('click', function(){
        setStorage('selected-subcat', $(this).data('subcategory'));
        redirectTo('/main-products');
    })
</script>
@endsection
