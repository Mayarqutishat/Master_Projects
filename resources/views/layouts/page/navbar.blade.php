<!-- PreLoader -->
<div class="loader">
    <div class="loader-inner">
        <div class="circle"></div>
    </div>
</div>
<!-- PreLoader Ends -->

<!-- Header -->
<div class="top-header-area" id="sticker">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-sm-12 text-center">
                <div class="main-menu-wrap">
                    <!-- Logo -->
                    <div class="site-logo">
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('assetsPages/assets/img/logo/logo2.png') }}" alt="">
                        </a>
                    </div>
                    <!-- Logo -->

                    <!-- Menu Start -->
                    <nav class="main-menu">
                        <ul>
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li><a href="{{ url('/shop') }}">Shop</a></li>
                            <li><a href="{{ url('/about') }}">About</a></li>
                            <li><a href="{{ url('/contact') }}">Contact</a></li>
                            <li>
                                <div class="header-icons">
                                    <!-- User Icon with Dropdown -->
                                    <div class="dropdown">
                                        <a href="#" class="login-register"><i class="fas fa-user"></i></a>
                                        <div class="dropdown-menu">
                                            @guest
                                                <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                                                <a class="dropdown-item" href="{{ route('login') }}">Register</a>
                                            @endguest

                                            @auth
                                                @if(auth()->user()->isAdmin())
                                                    <!-- Admin Links -->
                                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                                                @elseif(auth()->user()->isCustomer())
                                                    <!-- Customer Links -->
                                                    <a class="dropdown-item" href="{{ route('customer.dashboard') }}">MY Dashboard</a>
                                                @endif
                                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    Logout
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            @endauth
                                        </div>
                                    </div>
                                    <a class="wishlist" href="{{ url('/wishlist') }}"><i class="fas fa-heart"></i></a>
                                    <a class="shopping-cart" href="{{ url('/cart') }}"><i class="fas fa-shopping-cart"></i></a>
                                    <a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
                    <div class="mobile-menu"></div>
                    <!-- Menu End -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Header -->

<!-- Search Area -->
<div class="search-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <span class="close-btn"><i class="fas fa-window-close"></i></span>
                <div class="search-bar">
                    <div class="search-bar-tablecell">
                        <h3>Search For:</h3>
                        <form action="{{ route('search') }}" method="GET">
                            <input type="text" name="query" placeholder="Keywords">
                            <button type="submit">Search <i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Search Area -->

<!-- Add this CSS for dropdown styling -->
<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        background-color: #fff;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        min-width: 160px;
        z-index: 1;
    }

    .dropdown-menu a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-menu a:hover {
        background-color: #ddd;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }
</style>
