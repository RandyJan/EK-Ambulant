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
            <li class="nav-item">
                
                <a class="" href="{{ route('browser.user-devices') }}">
                    <span class="icon-holder">
                        <i class="ei-package"></i>
                    </span> 
                    <span class="title">User Devices</span>
                </a>  

                <a class="" href="{{ route('browser.mealstub') }}">
                    <span class="icon-holder">
                        <i class="ei-package"></i>
                    </span> 
                    <span class="title">Mealstubs</span>
                </a>  

                <a class="" href="{{ route('browser.terminals') }}">
                    <span class="icon-holder">
                        <i class="ei-package"></i>
                    </span> 
                    <span class="title">Terminals</span>
                </a>  


            </li> 
        </ul>
     
    </div>
</div> 