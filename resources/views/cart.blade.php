@extends('layouts.masterPage')

@section('content')



<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <h1>Cart</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<!-- cart -->
<div class="cart-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="cart-table-wrap">
                    <table class="cart-table">
                        <thead class="cart-table-head">
                            <tr class="table-head-row">
                                <th class="product-remove"></th>
                                <th class="product-image">Product Image</th>
                                <th class="product-name">Name</th>
                                <th class="product-price">Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-total">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $productId => $item)
                                <tr class="table-body-row">
                                    <td class="product-remove">
                                        <a href="{{ route('cart.remove', $productId) }}"><i class="far fa-window-close"></i></a>
                                    </td>
                                    <td class="product-image">
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                    </td>
                                    <td class="product-name">{{ $item['name'] }}</td>
                                    <td class="product-price">{{ $item['price'] }} JD</td>
                                    <td class="product-quantity">{{ $item['quantity'] }}</td>
                                    <td class="product-total">{{ $item['price'] * $item['quantity'] }} JD</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="total-section">
                    <table class="total-table">
                        <thead class="total-table-head">
                            <tr class="table-total-row">
                                <th>Total</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="total-data">
                                <td><strong>Subtotal: </strong></td>
                                <td>{{ $subtotal }} JD</td>
                            </tr>
                            @if ($couponDiscount > 0)
                                <tr class="total-data">
                                    <td><strong>Coupon Discount: </strong></td>
                                    <td>-{{ $couponDiscount }} JD</td>
                                </tr>
                            @endif
                            <tr class="total-data">
                                <td><strong>Shipping: </strong></td>
                                <td>5 JD</td>
                            </tr>
                            <tr class="total-data">
                                <td><strong>Total: </strong></td>
                                <td>{{ $subtotal + 5 - $couponDiscount }} JD</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="cart-buttons">
                        <a href="{{ url('/checkout') }}" class="boxed-btn black">Check Out</a>
                    </div>
                </div>

                <div class="coupon-section">
                    <h3>Apply Coupon</h3>
                    <div class="coupon-form-wrap">
                        <form action="{{ route('cart.applyCoupon') }}" method="POST">
                            @csrf
                            <p><input type="text" name="coupon_code" placeholder="Coupon Code" required></p>
                            <p><input type="submit" value="Apply"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end cart -->  

@endsection
