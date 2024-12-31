@extends('layouts.masterPage')

@section('content')
<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <h1>Search Results</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- products -->
<div class="product-section mt-150 mb-150">
    <div class="container">
        <!-- Product filters (optional) -->
        <div class="row">
            <div class="col-md-12">
                <div class="product-filters">
                    <ul>
                        <li><a href="{{ route('shop') }}">All Products</a></li>
                        <!-- Add other filters if needed -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- Product list -->
        <div class="row product-lists">
            @if ($products->isEmpty())
                <p>No results found</p>
            @else
                @foreach($products as $product)
                <div class="col-lg-4 col-md-6 text-center {{ $product->category->name }}">
                    <div class="single-product-item">
                        <div class="product-image">
                            <a href="{{ url('/single-product/' . $product->id) }}">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $product->images->first()->url) }}" alt="{{ $product->name }}">
                                @else
                                    <img src="{{ asset('assetsPages/assets/img/bags/bag2.jpg') }}" alt="{{ $product->name }}">
                                @endif
                            </a>
                        </div>
                        <h3>{{ $product->name }}</h3>
                        <p class="product-price">{{ $product->price }} JD</p>
                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                        <div class="product-actions d-flex justify-content-center">
                            <button class="cart-btn add-to-wishlist" data-product-id="{{ $product->id }}">
                                <i class="fas fa-heart"></i> Wishlist
                            </button>
                            <a href="{{ url('/single-product/' . $product->id) }}" class="cart-btn view-product ms-2">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- Pagination -->
        <div class="pagination-container d-flex justify-content-center align-items-center mt-4">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
<!-- end products -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wishlistButtons = document.querySelectorAll('.add-to-wishlist');

        wishlistButtons.forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.productId;
                const icon = this.querySelector('i');

                // Check if the icon is already red (indicating the product is in the wishlist)
                if (icon.style.color !== 'red') {
                    // Add to wishlist
                    fetch("{{ route('wishlist.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ product_id: productId }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Change the icon color to red if successful
                            icon.style.color = 'red';
                        } else {
                            // Handle error (optional)
                            console.error('Failed to add to wishlist');
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
                } else {
                    // Remove from wishlist
                    fetch("{{ route('wishlist.remove') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ product_id: productId }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reset the icon color to default if removed
                            icon.style.color = '';
                        } else {
                            // Handle error (optional)
                            console.error('Failed to remove from wishlist');
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
                }
            });
        });
    });
</script>

@endsection
