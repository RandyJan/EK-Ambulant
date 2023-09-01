@extends('layouts.master')

@section('title','Products')

@section('content')

@include('alerts.alert')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/part-location/category">Category </a> </li>
        <li class="breadcrumb-item active" aria-current="page">Sub-Category</li>

    </ol>
</nav>

<br>

<div class="row" id="container">
  
</div>



@endsection
@section('js')
<script>
    $(document).ready(function() {
        get('/part-location/category/' + getStorage('selected-category') + '/sub-category', {}, function(response) {
            console.log(response);
            var c = $('#container'); // c = container
            c.empty();

            
            $.each(response.data, function(k, v) {
                c.append(
                    '<div class="col-4 col-sm-3 col-md-2 py-0 px-1  mb-2">' +
                    '<div class="card h-100 mb-0 category espire" data-subcategory="' + v.category_id.trim() + '">' +
                    '<div class="card-block h-100 d-flex align-items-center p-3">' +
                    '<ul class="list-info">' +
                    '<li class="">' +
                    '<div class="info  pl-0" style="line-height: 1;">' +
                    '<b class="text-primary font-size-13 mt-1 word-break">' + v.description + '</b>' +
                    '</div>' +
                    '</li>' +
                    '</ul>' +

                    '</div>' +
                    '</div>' +
                    '</div>'
                );
            });

            $('.category').on('click', function() {
                console.log($(this));
                setStorage('selected-subcategory', $(this).data('subcategory'));
                redirectTo('/products?group_id=' + getStorage('selected-category') + '&sub_category=' + getStorage('selected-subcategory'));
            })
        });


        


    });
</script>
@endsection