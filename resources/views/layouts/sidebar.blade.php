<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/1936bois.png') }}" alt="" height="26">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/1936bois.png') }}" alt="" height="28">
            </span>
        </a>

        <a href="index" class="logo logo-light">
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/1936bois.png') }}" alt="" height="30">
            </span>
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/1936bois.png') }}" alt="" height="26">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
        <i class="bx bx-menu align-middle"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">
    @if (auth()->check() && auth() -> user() -> user_type === 'admin')
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Admin Dashboard</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bx bx-home-alt icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title" data-key="t-applications">Administration</li>
                
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-envelope icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">Registered User</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('users.index', ['view' => 'general']) }}" data-key="t-inbox">General Users</a></li>
                        <li><a href="{{ route('users.index', ['view' => 'admins']) }}" data-key="t-read-email">Administrators</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('event.index') }}">
                        <i class="bx bx-calendar-event icon nav-icon"></i>
                        <span class="menu-item" data-key="t-calendar">Events</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-store icon nav-icon"></i>
                        <span class="menu-item" data-key="t-ecommerce">Ecommerce</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('category.index') }}" data-key="t-category">Category</a></li>
                        <li><a href="{{ route('products.index') }}" data-key="t-products">Products</a></li>
                        <li><a href="{{ route('orders.index') }}" data-key="t-orders">Orders</a></li>
                        <li><a href="{{ route('vouchers.index') }}" data-key="t-customers">Marketing</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        @endif
        @if (auth()->check() && auth() -> user() -> user_type === 'general')
        <div id="sidebar-menu">
        <ul class="metismenu list-unstyled" id="side-menu">
        <li class="menu-title" data-key="t-menu">User Dashboard</li>
                <li>
                    <a href="{{ route('home') }}">
                        <i class="bx bx-home-alt icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Home</span>
                    </a>
                </li>
                <li class="menu-title" data-key="t-menu">Membership</li>
                    <li>
                        <a href="{{ route('memberships.create') }}">
                            <i class="bx bx-credit-card icon nav-icon"></i>
                            <span class="menu-item" data-key="t-dashboard">Register</span>
                        </a>
                    </li> 
                <li class="menu-title" data-key="t-menu">E-Commerce</li>
                <li>
                    <a href="{{ route('products.index') }}">
                        <i class="bx bx-credit-card icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">Products</span>
                    </a>
                </li> 
                <li>
                    <a href="{{ route('event.index') }}">
                        <i class="bx bx-calendar-event icon nav-icon"></i>
                        <span class="menu-item" data-key="t-calendar">Events</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders.index') }}">
                        <i class="bx bx-credit-card icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboard">My Orders</span>
                    </a>
                </li> 
        </ul>  
        </div>
        @endif  
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->