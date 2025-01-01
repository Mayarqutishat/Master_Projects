@extends('layouts.masterPage')

@section('content')

    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>See more Details</p>
                        <h1>Single Product</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <div class="single-product-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <!-- Product Image -->
                <div class="col-lg-6">
                    <div class="product-image">
                        <img src="{{ asset('storage/' . $product->images->first()->url) }}" alt="{{ $product->name }}">
                    </div>
                </div>
                <!-- Product Details -->
                <div class="col-lg-6">
                    <h1>{{ $product->name }}</h1>
                    <p class="product-price">{{ $product->price }} JD</p>
                    <p class="product-description">{{ $product->description }}</p>
                    <div class="single-product-form">
                        <!-- Replaced the form with the design from the second code -->
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" placeholder="Quantity">
                            <!-- Here is the updated button with same style as in the second code -->
                            <button type="submit" class="cart-btn" style="background-color: #eab0d2; color: white; border-radius: 50px; border: none; padding: 10px 20px; font-size: 16px;">
    <i class="fas fa-shopping-cart"></i> Add to Cart
</button>


                        </form>
                    </div>
                    <!-- Show product reviews -->
                    <div class="product-rating">
    <h4>Rating: {{ number_format($averageRating, 1) }}/5</h4>
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- logo carousel -->
    <div class="logo-carousel-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="logo-carousel-inner">
                        <div class="single-logo-item">
                            <img src="{{asset('assetsPages/assets/img/company-logos/company1.jpg') }}" alt="Company 1">
                        </div>
                        <div class="single-logo-item">
                            <img src="{{asset('assetsPages/assets/img/company-logos/company2.jpg') }}" alt="Company 2">
                        </div>
                        <div class="single-logo-item">
                            <img src="{{asset('assetsPages/assets/img/company-logos/company3.jpg') }}" alt="Company 3">
                        </div>
                        <div class="single-logo-item">
                            <img src="{{asset('assetsPages/assets/img/company-logos/company4.jpg') }}" alt="Company 4">
                        </div>
                        <div class="single-logo-item">
                            <img src="{{asset('assetsPages/assets/img/company-logos/company5.jpg') }}" alt="Company 5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end logo carousel -->

@endsection
