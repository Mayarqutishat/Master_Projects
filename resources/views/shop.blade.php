@extends('layouts.masterPage')

@section('content')
<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <h1>Shop</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->


<!-- products -->
<div class="product-section mt-150 mb-150">
    <div class="container">
        <!-- Product filters -->
        <div class="row">
            <div class="col-md-12">
                <div class="product-filters">
                    <ul>
                        <li class="{{ request('filter') === '*' ? 'active' : '' }}" data-filter="*"><a href="{{ route('shop') }}">All</a></li>
                        <li class="{{ request('filter') === 'Bags' ? 'active' : '' }}" data-filter=".bag"><a href="{{ route('shop', ['filter' => 'Bag']) }}">Bags</a></li>
                        <li class="{{ request('filter') === 'Frame' ? 'active' : '' }}" data-filter=".Frame"><a href="{{ route('shop', ['filter' => 'Frame']) }}">Frame</a></li>
                        <li class="{{ request('filter') === 'Needles' ? 'active' : '' }}" data-filter=".Needles"><a href="{{ route('shop', ['filter' => 'Needles']) }}">Needles</a></li>
                        <li class="{{ request('filter') === 'Yarns' ? 'active' : '' }}" data-filter=".Yarns"><a href="{{ route('shop', ['filter' => 'Yarns']) }}">Yarns</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Product list -->
        <div class="row product-lists">
            <div class="row">
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
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-container d-flex justify-content-center align-items-center mt-4">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
<!-- end products -->

<!-- logo carousel -->
<div class="logo-carousel-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="logo-carousel-inner">
                    <div class="single-logo-item">
                        <img src="{{asset('assetsPages/assets/img/company-logos/company1.jpg') }}" alt=""/>
                    </div>
                    <div class="single-logo-item">
                        <img src="{{asset('assetsPages/assets/img/company-logos/company2.jpg') }}" alt=""/>
                    </div>
                    <div class="single-logo-item">
                        <img src="{{asset('assetsPages/assets/img/company-logos/company3.jpg') }}" alt=""/>
                    </div>
                    <div class="single-logo-item">
                        <img src="{{asset('assetsPages/assets/img/company-logos/company4.jpg') }}" alt=""/>
                    </div>
                    <div class="single-logo-item">
                        <img src="{{asset('assetsPages/assets/img/company-logos/company5.jpg') }}" alt=""/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end logo carousel -->

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
