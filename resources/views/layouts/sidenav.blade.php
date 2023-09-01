<!-- Side Nav START -->
<div class="side-nav">
    <div class="side-nav-inner">
        <div class="side-nav-logo">
            <a href="/">
                <div class="logo logo-dark" style="background-image: url('/assets/images/logo/logo.png')"></div>
                {{-- <div class="logo logo-white" style="background-image: url('assets/images/logo/logo-white.png')"></div> --}}
            </a>
            <div class="mobile-toggle side-nav-toggle">
                <a href="#">
                    <i class="ti-arrow-circle-left"></i>
                </a>
            </div>
        </div>

        <ul class="side-nav-menu scrollable">
            @if( request()->cookie('device_type') == 'kiosk')
                <li class="nav-item">
                    <a class="" href="{{ route('categories') }}">
                        <span class="icon-holder">
                            <i class="ei-package"></i>
                        </span>
                        <span class="title">Choose Menu</span>
                    </a>
                </li>
                @if( request()->cookie('accept_mealstub') == true)
                    <li class="nav-item">
                        <a class="active" href="{{ route('mealstub') }}">
                            <span class="icon-holder">
                                <i class="ei-ticket"></i>
                            </span>
                            {{--<span class="title">Mealstub</span> --}}
                            <span class="title">Claim Stub</span>
                        </a>
                    </li>
                @endif
            @else
            <li class="nav-item">
                <a class="" href="/">
                    <span class="icon-holder">
                        <i class="ti-write"></i>
                    </span>
                    <span class="title">Unresolve OS</span>
                </a>
            </li>
            @if( request()->cookie('accept_mealstub') == true)
            <li class="nav-item">
                <a class="active" href="{{ route('mealstub') }}">
                    <span class="icon-holder">
                        <i class="ei-ticket"></i>
                    </span>
                    {{--<span class="title">Mealstub</span> --}}
                    <span class="title">Claim Stub</span>
                </a>
            </li>
            @endif
            <li class="nav-item">

                <a href="{{ route('summary_device') }}">
                   <span class="icon-holder">
                       <i class="ei-list"></i>
                   </span>
                   <span class="title">Summary</span>
               </a>
           </li>
            @endif

            {{--
            <li class="nav-item">
                <a class="" href="{{ route('categories') }}">
                    <span class="icon-holder">
                        <i class="ei-package"></i>
                    </span>
                    <span class="title">Choose Menu</span>
                </a>
            </li>  --}}

            {{-- <li class="nav-item">
                <a class="" href="{{ route('summary') }}">
                    <span class="icon-holder">
                        <i class="ei-list"></i>
                    </span>
                    <span class="title">Summary</span>
                </a>
            </li> --}}

            {{--
            <li class="nav-item">
            <a class="" href="{{config('ambulant.ek_card_web_url')}}" target="_blank">
                    <span class="icon-holder">
                        <i class="ti-credit-card"></i>
                    </span>
                    <span class="title">Card Registration</span>
                </a>
            </li>
            --}}
            {{--
                 <!--
            <li class="nav-item">
                 <a class="" id="customer-info">
                    <span class="icon-holder">
                        <i class="ei-id-card-alt"></i>
                    </span>
                    <span class="title">Customer Info</span>
                </a>
            </li>





             <li class="nav-item">
                <a class=" " href="{{ route('outlets') }}">
                    <span class="icon-holder">
                        <i class="ei-store"></i>
                    </span>
                    <span class="title">Outlets</span>
                </a>
            <li class="nav-item">
                <a class=" " href="{{ route('devices') }}">
                    <span class="icon-holder">
                        <i class="ei-smartphone"></i>
                    </span>
                    <span class="title">Devices</span>
                </a>
            </li>


              <!-- ADMIN SIDE -->

              <!-- <li class="nav-item">
                <a class="" href="">
                    <span class="icon-holder">
                        <i class="ei-employees"></i>
                    </span>
                    <span class="title">Users</span>
                </a>

            </li>
            <li class="nav-item">
                <a class="" href="/pages/admin/admin">
                    <span class="icon-holder">
                        <i class="ei-collaboration"></i>
                    </span>
                    <span class="title">Branch</span>
                </a>

            </li>

            <ul class="side-nav-menu scrollable">
            <li class="nav-item">
                <a class="mrg-top-30" href="/part-location/category">
                    <span class="icon-holder">
                        <i class="ei-package"></i>
                    </span>
                    <span class="title">Menu</span>
                </a>

            </li> -->





              <!-- <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <span class="icon-holder">
                            <i class="ei-collaboration"></i>
                        </span>
                    <span class="title">Branch</span>
                    <span class="arrow">
                            <i class="ti-angle-right"></i>
                        </span>
                </a>
                    <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('outlets') }}">
                        <span class="icon-holder">
                        <i class="ei-store"></i>
                    </span>
                            <span class="title"> &nbsp;Outlets</span>
                        </a>
                    </li>
                    <li>
                        <a href="/completed-order">
                        <span class="icon-holder">
                        <i class="ei-smartphone"></i>
                        </span>
                            <span class="title"> &nbsp;Devices</span>
                        </a>
                    </li>
                </ul>
                </li> -->

            <!-- <li class="nav-item">
                <a class=" " href="{{ route('devices') }}">
                    <span class="icon-holder">
                        <i class="ei-smartphone"></i>
                    </span>
                    <span class="title">Settings</span>
                </a>
            </li> -->

            <!-- <li class="nav-item">
                <a class="mrg-top-30" href="/">
                    <span class="icon-holder">
                        <i class="ti-pencil-alt"></i>
                    </span>
                    <span class="title">Order</span>
                </a>
            </li> -->
            <!-- <li class="nav-item">
                <a class="" href="/my-order">
                    <span class="icon-holder">
                        <i class="ti-shopping-cart"></i>
                    </span>
                    <span class="title">My Order</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <span class="icon-holder">
                            <i class="ti-package"></i>
                        </span>
                    <span class="title">Sales Order's</span>
                    <span class="arrow">
                            <i class="ti-angle-right"></i>
                        </span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="/pending-order">
                            <span class="icon-holder">
                                <i class="ti-reload"></i>
                            </span>
                            <span class="title"> &nbsp;Pending</span>
                        </a>
                    </li>
                    <li>
                        <a href="/completed-order">
                            <span class="icon-holder">
                                <i class="ti-check"></i>
                            </span>
                            <span class="title"> &nbsp;Completed</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="" href="/claiming">
                    <span class="icon-holder">
                        <i class="ti-gift"></i>
                    </span>
                    <span class="title">Claiming</span>
                </a>
            </li> -->
            --}}

        </ul>

    </div>
</div>
<!-- Side Nav END -->
<div class="modal fade" id="modal-customer-info">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <div class="mrg-top-40">
                    <div class="row">
                        <div class="col-md-8 ml-auto mr-auto">
                            <form>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input type="number" placeholder="" name="phone_number" required value="" id="phone_number" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Customer Name</label>
                                            <input type="text" placeholder="" name="customer_name" required value="" id="customer_name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <input type="date" placeholder="" name="bdate" required value="" id="bdate" class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer no-border">
                <div class="text-right">
                    <button class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm" value="submit" id="save_info" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
