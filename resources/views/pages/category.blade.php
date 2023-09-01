@extends('layouts.master')

@section('title','Products')

@section('content')

@include('alerts.alert')


<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="/part-location/category">Category</a></li>
    </ol>
</nav>
<br>
<div class="row">

@if( $result->count() == 0)
    <div class="col-md-12">
        0 results
    </div>
@else
    @foreach($result as $item)

     <div class="col-4 col-sm-3 col-md-2 py-0 px-1 mb-2 ">
        <div class="card h-100 mb-0 group espire" data-category="{{ $item['group_id'] }}">
            <div class="card-block h-100 d-flex align-items-center p-3">
                <ul class="list-info">
                    <li class="">
                        <div class="info  pl-0" style="line-height: 1;">
                            <b class="text-primary font-size-13 mt-1 word-break">{{ $item['description'] }}</b>
                        </div>
                    </li>
                </ul>

            </div>
        </div>
    </div>

    @endforeach
@endif




</div>



<div class="modal slide-in-right modal-right fade " id="side-modal-r">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="side-modal-wrapper">
                <div class=" ">
                    <div class="table-cell">

                        @if( is_null( Auth::user()->activeOrder() ) )
                        <div class="todo-wrapper">
                            <div class="todo-category-wrapper">

                                <div class="row mrg-btm-15">
                                    <div class="col-md-12">
                                        <h6 class="no-mrg-top">Hey!, You have no order.</h6>
                                    </div>
                                </div>

                                <a href="javascript:void(0);" class="todo-toggle" style="width: 100%;!important">
                                    <div class="todo-category">
                                        <span class="amount">Create Orderslip</span>
                                        <!-- <span class="category">Hey!, You have no order.</span> -->
                                    </div>
                                </a>
                            </div>
                        </div>



                        @endif
                        {{--<h4>Orders count: {{ Auth::user()->orders->count() }}</h4>

                        <div class="pdd-horizon-15">
                            <h4>Sign Up</h4>
                            <p class="mrg-btm-15 font-size-13">Please enter your email and password to create account</p>
                            <form>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email Adress">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="checkbox font-size-12">
                                    <input id="agreement" name="agreement" type="checkbox">
                                    <label for="agreement">I agree with the <a href="#">Privacy &amp; Policy</a></label>
                                </div>
                                <button class="btn btn-info btn-sm">Sign Up</button>
                            </form>
                        </div> --}}
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <span>Already have an account? <a href="#">Login Now</a></span>
                </div> -->
            </div>
        </div>
    </div>
</div>



{{-- <div class="modal fade" id="modal-sm">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="/products" method="get">
                <div class="modal-body">
                    <div class="">

                        <div class="form-group">
                            <label>Category</label>
                            <select name="group_id" class="form-control" id="category">
                                <option> All </option>
                                @foreach( $groups as $item)
                                <option value="{{ $item['group_id'] }}">{{ $item['description'] }}</option>
@endforeach
</select>
</div>

<div class="form-group">
    <label>Sub Category</label>
    <select name="sub_category" class="form-control" id="sub-category">
        <option> All </option>
    </select>
</div>

<div class="form-group">
    <label>Product name (optional)</label>
    <input name="name" type="text" placeholder="Product name" class="form-control">
</div>
</div>
</div>
<button type="submit" class="btn btn-primary btn-block no-mrg no-border pdd-vertical-15 ng-scope">
    <span class="text-uppercase"> Search <i class="ti-search"></i></span>
</button>
</form>
</div>
</div>
</div> --}}
@endsection

@section('js')
<script>
    $('.group').on('click', function() {
        let group_id = $(this).data('category');
        // console.log(group_id)
        setStorage('selected-category',group_id);
        location.href = '/subCategory';

    });
</script>

@endsection
