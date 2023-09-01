<!-- Header START -->
<div class="header navbar">
    <div class="header-container">
        <ul class="nav-left ml-0">
            <li>
                <a class="side-nav-toggle" href="javascript:void(0);">
                    <i class="ti-view-grid"></i>
                </a>
            </li>
            @if( is_null( Auth::user()->activeOrder() ) )
                <li>
                    <a href="javascript:void(0);"><i class="ti-user"></i><strong >&nbsp;{{ Auth::user()->activeOrder() ? Auth::user()->activeOrder()->total_hc : '' }}</strong></span>
                    </a>
                </li>
            @else
                <li>
                <a id="headcount" href="javascript:void(0);"
                        class="{{ ( Auth::user()->activeOrder()->is_paid == 1 ) ? 'not-allowed' : '' }}"
                        data-branch-id="{{ Auth::user()->activeOrder()->branch_id }}"
                        data-os-id="{{ Auth::user()->activeOrder()->orderslip_header_id }}"
                        data-outlet-id="{{ Auth::user()->activeOrder()->outlet_id }}"
                        data-device-id="{{ Auth::user()->activeOrder()->device_no }}" >
                        <span class="font-size-20 text-dark pdd-right-10"><i class="ti-user"></i><strong >&nbsp;{{ Auth::user()->activeOrder() ? Auth::user()->activeOrder()->total_hc : '' }}</strong></span>
                    </a>
                </li>
            @endif
            <li class="search-input">
                <input id="txt-searchbox" class="form-control" type="text" placeholder="Search...">
                <!-- <div class="advanced-search">
                    <div class="search-wrapper">
                        <div class="pdd-vertical-10">
                            <span class="display-block mrg-vertical-5 pdd-horizon-20 text-gray">
                                    <i class="ti-user pdd-right-5"></i>
                                    <span>People</span>
                            </span>
                            <ul class="list-unstyled list-info">
                                <li>
                                    <a href="#">
                                        <img class="thumb-img" src="/assets/images/avatars/thumb-1.jpg" alt="">
                                        <div class="info">
                                            <span class="title">Jordan Hurst</span>
                                            <span class="sub-title">
                                                    <i class="ti-location-pin"></i>
                                                    <span>44 Shirley Ave. West Chicago</span>
                                            </span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img class="thumb-img" src="/assets/images/avatars/thumb-5.jpg" alt="">
                                        <div class="info">
                                            <span class="title">Jennifer Watkins</span>
                                            <span class="sub-title">
                                                    <i class="ti-location-pin"></i>
                                                    <span>514 S. Magnolia St. Orlando</span>
                                            </span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img class="thumb-img" src="/assets/images/avatars/thumb-4.jpg" alt="">
                                        <div class="info">
                                            <span class="title">Michael Birch</span>
                                            <span class="sub-title">
                                                    <i class="ti-location-pin"></i>
                                                    <span>70 Bowman St. South Windsor</span>
                                            </span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="mrg-horizon-20 border top"></div>
                        <div class="pdd-vertical-10">
                            <span class="display-block mrg-vertical-5 pdd-horizon-20 text-gray">
                                    <i class="ti-rss pdd-right-5"></i>
                                    <span>Post</span>
                            </span>
                            <ul class="list-unstyled list-info">
                                <li>
                                    <a href="#">
                                        <img class="thumb-img" src="/assets/images/img-1.jpg" alt="">
                                        <div class="info">
                                            <span class="title">Artoo expresses his relief</span>
                                            <span class="sub-title">
                                                    <span>Oh, thank goodness we're coming out...</span>
                                            </span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img class="thumb-img" src="/assets/images/img-2.jpg" alt="">
                                        <div class="info">
                                            <span class="title">Ready for some power?</span>
                                            <span class="sub-title">
                                                    <span>Lord Vader. You may take Caption So...</span>
                                            </span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="search-footer">
                        <span>You are Searching for '<b class="text-dark"><span class="serach-text-bind"></span></b>'</span>
                    </div>
                </div> -->
            </li>

        </ul>
        <ul class="nav-right">
            <li class="user-profile dropdown">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#modal-sm-user">
                    <img class="profile-img img-fluid" src="/assets/images/user.png" alt="">

                </a>
                <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img class="profile-img img-fluid" src="/assets/images/user.png" alt="">
                    <div class="user-info">
                        <span class="name pdd-right-5 current-user-name">Nate Leong</span>
                        <i class="ti-angle-down font-size-10"></i>
                    </div>
                </a> -->
                <!-- <ul class="dropdown-menu">
                    <li>
                        <a href="#">
                            <i class="ti-settings pdd-right-10"></i>
                            <span>Setting</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="ti-user pdd-right-10"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="ti-email pdd-right-10"></i>
                            <span>Inbox</span>
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="/logout" class="btn-logout">
                            <i class="ti-power-off pdd-right-10 "></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul> -->
            </li>
            {{--<li class="notifications dropdown">
                <span class="counter">{{ Auth::user()->activeOrder() ? Auth::user()->activeOrder()->itemOnCart()->count():0 }}</span>
            <a href="Javascript:void(0);" class="dropdown-toggle">
                <i class="ti-shopping-cart"></i>
            </a>
            <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="ti-bell"></i>
                </a> -->

            <!-- <ul class="dropdown-menu ">
                    <li class="notice-header">
                        <i class="ti-bell pdd-right-10"></i>
                        <span>Notifications</span>
                    </li>
                    <li>
                        <ul class="list-info overflow-y-auto relative scrollable">
                            <li>
                                <a href="#">
                                    <img class="thumb-img" src="assets/images/avatars/thumb-5.jpg" alt="">
                                    <div class="info">
                                        <span class="title">
                                            <span class="font-size-14 text-semibold">Jennifer Watkins</span>
                                        <span class="text-gray">commented on your <span class="text-dark">post</span></span>
                                        </span>
                                        <span class="sub-title">5 mins ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img class="thumb-img" src="assets/images/avatars/thumb-4.jpg" alt="">
                                    <div class="info">
                                        <span class="title">
                                            <span class="font-size-14 text-semibold">Samuel Field</span>
                                        <span class="text-gray">likes your <span class="text-dark">photo</span></span>
                                        </span>
                                        <span class="sub-title">8 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="thumb-img bg-primary">
                                            <span class="text-white">M</span>
                                    </span>
                                    <div class="info">
                                        <span class="title">
                                            <span class="font-size-14 text-semibold">Michael Birch</span>
                                        <span class="text-gray">likes your <span class="text-dark">photo</span></span>
                                        </span>
                                        <span class="sub-title">5 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="thumb-img bg-success">
                                        <span class="text-white"><i class="fa fa-paper-plane-o"></i></span>
                                    </span>
                                    <div class="info">
                                        <span class="title">
                                            <span class="font-size-14 text-semibold">Message sent</span>
                                        </span>
                                        <span class="sub-title">8 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="thumb-img bg-info">
                                        <span class="text-white"><i class="ti-user"></i></span>
                                    </span>
                                    <div class="info">
                                        <span class="title">
                                            <span class="font-size-14 text-semibold">Admin</span>
                                        <span class="text-gray">Welcome on board</span>
                                        </span>
                                        <span class="sub-title">8 hours ago</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="notice-footer">
                        <span>
                            <a href="#" class="text-gray">Check all notifications <i class="ei-right-chevron pdd-left-5 font-size-10"></i></a>
                        </span>
                    </li>
                </ul> -->
            </li> --}}
            <li>
                <a class="side-panel-toggle notifications" href="javascript:void(0);">
                @php
                    $cart_count = 0;
                    $cart_count +=  Auth::user()->activeOrder() ? Auth::user()->activeOrder()->itemOnCart()->count():0;
                @endphp
                @if( isset (Auth::user()->activeOrder()['MEAL_STUB_COUNT']) )
                     @php $cart_count += Auth::user()->activeOrder()['MEAL_STUB_COUNT'] @endphp
                @endif

                    <span class="counter">{{ $cart_count }}</span>
                    {{--<i class="ei-hamburger"></i>--}}
                    <i class="ei-shopping-cart"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- Header END -->

<div class="modal fade" id="modal-sm-user">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <img class="img-responsive mrg-horizon-auto mrg-vertical-25" src="/assets/images/user.png" alt="">
                    <h4>{{ Auth::user()->name }}</h4>

                    <!-- <h5>DATE:{{ date('d-M-y')}}</h5>
                    <h5>TIME:{{ date('g:i A')}}</h5> -->

                    <!-- <p>@Ambulant</p> -->
                    {{--
                    <a href="javascript:void(0);">
                        <i class="ti-home pdd-right-5"></i>
                        <span>Branch</span>
                        <span class="label label-info mrg-left-5"> {{ config('ambulant.branch_id') }}</span>
                    </a>
                    <div class="mt-2"></div>
                    <a href="javascript:void(0);">
                        <i class="ti-shopping-cart pdd-right-5"></i>
                        <span>Outlet</span>
                        <span class="label label-info mrg-left-5">{{ auth()->user()->outlet_id }}</span>
                    </a>
                    <div class="mt-2"></div>
                    <a href="javascript:void(0);">
                        <i class="ti-mobile pdd-right-5"></i>
                        <span>Device</span>
                        <span class="label label-info mrg-left-5">{{ auth()->user()->device_no }} </span>
                    </a>
                    --}}
                </div>
                <hr>
                <h2 class="text-center">Details</h2>
                <div class="table-overflow">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>Branch</th>
                                <td class="text-right">
                                <span class="label label-info mrg-left-5">{{ getBranchName()->branch_name }}</span>
                                </td>

                            </tr>
                            <tr>
                                <th>Outlet</th>
                                <td class="text-right">
                                <span class="label label-info mrg-left-5">{{getUserOutletId()}}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Device</th>
                                <td class="text-right">
                                <span class="label label-info mrg-left-5">{{getDeviceName()->name}}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>POS ID</th>
                                <td class="text-right">
                                <span class="label label-info mrg-left-5">{{ getUserPosId()->pos_id }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="/logout" class="btn btn-info btn-block no-mrg no-border pdd-vertical-15 ng-scope">Logout</a>
        </div>
    </div>
</div>

