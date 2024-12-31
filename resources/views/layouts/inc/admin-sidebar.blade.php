<!-- Link to Material Design Icons -->
<link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

<!-- Sidebar navigation -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <li class="nav-item">
            <a class="nav-link" href="index.html">
               
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}"><span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a></li>
            </a>
        </li>

        <!-- Users Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <span class="menu-title">Users</span>
                <i class="mdi mdi-account menu-icon"></i>
            </a>
        </li>

        <!-- Admins Section -->
   <!-- Admins Section -->
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.admins.index') }}">
        <span class="menu-title">Admins</span>
        <i class="mdi mdi-account-circle menu-icon"></i>
    </a>
</li>


        <!-- Categories Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                <span class="menu-title">Categories</span>
                <i class="mdi mdi-view-grid menu-icon"></i>
            </a>
        </li>

        <!-- Products Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.products.index') }}">
                <span class="menu-title">Products</span>
                <i class="mdi mdi-package-variant menu-icon"></i>
            </a>
        </li>

        <!-- Orders Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.orders.index') }}">
                <span class="menu-title">Orders</span>
                <i class="mdi mdi-cart menu-icon"></i>
            </a>
        </li>

        <!-- Order Items Section -->
        <li class="nav-item">
            <a class="nav-link"href="{{ route('admin.order_items.index') }}">
                <span class="menu-title">Order Items</span>
                <i class="mdi mdi-cart-plus menu-icon"></i>
            </a>
        </li>

        <!-- Reviews Section -->
        <li class="nav-item">
            <a class="nav-link"  href="{{ route('admin.reviews.index') }}">
                <span class="menu-title">Reviews</span>
                <i class="mdi mdi-comment menu-icon"></i>
            </a>
        </li>

        <!-- Images Section -->
        <li class="nav-item">
            <a class="nav-link"  href="{{ route('admin.images.index') }}">
                <span class="menu-title">Images</span>
                <i class="mdi mdi-image menu-icon"></i>
            </a>
        </li>

        <!-- Cart Section -->
        <li class="nav-item">
            <a class="nav-link"  href="{{ route('admin.carts.index') }}">
                <span class="menu-title">Cart</span>
                <i class="mdi mdi-cart-outline menu-icon"></i>
            </a>
        </li>


<!-- Cart Section -->
<li class="nav-item">
            <a class="nav-link"  href="{{ route('admin.cart_items.index') }}">
                <span class="menu-title">Cart_items</span>
                <i class="mdi mdi-cart-outline menu-icon"></i>
            </a>
        </li>






        <!-- Coupons Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.coupons.index') }}">
                <span class="menu-title">Coupons</span>
                <i class="mdi mdi-ticket menu-icon"></i>
            </a>
        </li>

        <!-- Payments Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.payments.index') }}">
                <span class="menu-title">Payments</span>
                <i class="mdi mdi-credit-card menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>
