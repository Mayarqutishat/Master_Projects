<!-- Link to Material Design Icons -->
<link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

<!-- Sidebar navigation -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <!-- Dashboard Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        <!-- Users Section -->
        <li class="nav-item">
           <a class="nav-link" href="{{ route('customer.users.index') }}">

                <span class="menu-title">Users</span>
                <i class="mdi mdi-account menu-icon"></i>
            </a>
        </li>

    

        <!-- Orders Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.orders.index') }}">
                <span class="menu-title">Orders</span>
                <i class="mdi mdi-cart menu-icon"></i>
            </a>
        </li>

        <!-- Order Items Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.order_items.index') }}">
                <span class="menu-title">Order Items</span>
                <i class="mdi mdi-cart-plus menu-icon"></i>
            </a>
        </li>

        <!-- Reviews Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.reviews.index') }}">
                <span class="menu-title">Reviews</span>
                <i class="mdi mdi-comment menu-icon"></i>
            </a>
        </li>

        <!-- Cart Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.carts.index') }}">
                <span class="menu-title">Cart</span>
                <i class="mdi mdi-cart-outline menu-icon"></i>
            </a>
        </li>

        <!-- Cart Items Section -->
        <li class="nav-item">
            <a class="nav-link"  href="{{ route('customer.cart_items.index') }}">
                <span class="menu-title">Cart Items</span>
                <i class="mdi mdi-cart-outline menu-icon"></i>
            </a>
        </li>

     

        <!-- Payments Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.payments.index') }}">
                <span class="menu-title">Payments</span>
                <i class="mdi mdi-credit-card menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>
