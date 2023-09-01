@extends('layouts.master')

@section('title','Home')

@section('content')

@include('alerts.alert')


@if( is_null( Auth::user()->activeOrder() ) )
    <a href="/orderslip/create">
        <div class="card">
            <div class="overlay-dark bg-primary">
                <div class="card-block">
                    <div class="">
                        <div class="tag tag-warning">Notification</div>
                        <h2>
                            Hey!, You have no order.
                            <br>
                            Click this box to create new Order
                        </h2>
                        <!-- <div class="text-white text-opacity">
                            <span>
                                    <i class="ti-comment pdd-right-5"></i>
                                    <span> 88</span>
                            </span>
                            <span class="pdd-horizon-10">
                                    |
                                </span>
                            <span>
                                    <i class="ti-heart pdd-right-5"></i>
                                    <span> 168</span>
                            </span>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </a>
@endif

<div class="mb-2">
    <!-- <button data-toggle="modal" data-target="#side-modal-r" class="btn btn-xs btn-primary">
        <i class="ti-menu"></i>
    </button> -->
    <button data-toggle="modal" data-target="#modal-sm" class="btn btn-xs btn-primary" >
        <i class="ti-filter"></i>
    </button>
    <div style="float:right">
    <b >{{ $products->count() }}</b> Item(s)
    </div>
</div>

<div class="row">
    @foreach( $products as $product)
    <!-- {{ $product }} -->
        <div class="col-md-4 ">
            <a href="/outlet/{{ $product->outlet_id }}/product/{{ $product->product_id}}" class="card mrg-btm-15 scroll-to">
                <div class="card-block padding-25">
                    <ul class="list-unstyled list-info">
                        <li>
                            <div class="text-center">
                            <!-- // '<span class="thumb-img " style="border:1px solid gray">'+
                                // '<i class="ti-help-alt text-primary font-size-30"></i>'+
                                '<img src="'+ base_url + v.img_path+'" class="img-fluid" style="width:150px; height:150px">'+
                            // '</span>'+ -->
                            </div>
                            <div class="info"  style="padding-left: 0px;">
                                <!-- <div class="row">
                                    <div class="col-md-2 col-sm-4">{{ $product->RETAIL }}</div>
                                    <div class="col-md-10 col-sm-8"><b class="text-dark font-size-18">&nbsp;| {{ $product->description }}</b></div>
                                </div> -->
                                {{ $product->RETAIL }}
                                <b class="text-dark font-size-18">&nbsp;| {{ $product->description }}</b>
                            </div>
                        </li>
                    </ul>
                </div>
            </a>
        </div>
    @endforeach
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
                        <!-- <h4>Orders count: {{ Auth::user()->orders->count() }}</h4> -->

                        <!-- <div class="pdd-horizon-15">
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
                        </div> -->
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <span>Already have an account? <a href="#">Login Now</a></span>
                </div> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-sm">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="{{ url('') }}" method="get">
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
                        <!-- <img class="img-responsive mrg-horizon-auto mrg-vertical-25" src="assets/images/others/img-1.jpg" alt="">
                        <h3>Espire</h3>
                        <p>18 items</p> -->
                    </div>
                </div>
                <button type="submit"  class="btn btn-primary btn-block no-mrg no-border pdd-vertical-15 ng-scope">
                    <span class="text-uppercase">  Search <i class="ti-search"></i></span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>

        $(document).ready( function(){
            $('#selectize-dropdown').selectize({
                create: false,
                sortField: {
                    field: 'text',
                    direction: 'asc'
                },
                dropdownParent: 'body'
            });

            categoryCmb();
        });

        function categoryCmb(){
            $('#category').on('change', function(){
                var self = $(this);
                // console.log(self.val());
                if( self.val() != null || self.val() != ''){
                    get('/part-location/category/'+self.val()+'/sub-category',{}, function(response){
                        // console.log(response);
                        var c = $('#sub-category'); // c = container
                        c.empty();
                        c.append(
                            '<option > All </option>'
                        );
                        $.each(response.data, function(k,v){
                            c.append(
                                '<option value="'+v.category_id+'"> '+v.description+' </option>'
                            );
                        });
                    });
                    return;

                }
            });
        }

    </script>
@endsection
