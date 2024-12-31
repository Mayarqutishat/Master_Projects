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
                                    <a class="login-register" href="{{ route('profile.edit') }}"><i class="fas fa-user"></i></a>
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


