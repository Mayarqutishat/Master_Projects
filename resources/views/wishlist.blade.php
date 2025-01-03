@extends('layouts.masterPage')
@section('content')
<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <h1>Wishlist</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- wishlist -->
<br>
@php
$wishlist = Session::get('wishlist', []);
$wishlistProducts = \App\Models\Product::whereIn('id', $wishlist)->get();
@endphp

<div class="row" id="wishlist-container">
    @foreach($wishlistProducts as $product)
    <div class="col-lg-4 col-md-6 text-center wishlist-item" data-product-id="{{ $product->id }}">
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

            <!-- الأزرار بجانب بعضها البعض -->
            <div class="wishlist-buttons">
                <!-- تعديل الرابط ليتوجه إلى صفحة السلة -->
               
                <button class="cart-btn add-to-wishlist remove-from-wishlist" data-product-id="{{ $product->id }}">
                    <i class="fas fa-heart-broken"></i> 
                </button>
                <a href="{{ url('/single-product/' . $product->id) }}" class="cart-btn view-product ms-2">
                                <i class="fas fa-eye"></i> 
                            </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
<!-- end wishlist -->

<!-- logo carousel -->
<div class="logo-carousel-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="logo-carousel-inner">
                    <div class="single-logo-item">
                        <img src="{{asset('assetsPages/assets/img/company-logos/company1.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{asset('assetsPages/assets/img/company-logos/company2.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{asset('assetsPages/assets/img/company-logos/company3.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{asset('assetsPages/assets/img/company-logos/company4.jpg') }}" alt="">
                    </div>
                    <div class="single-logo-item">
                        <img src="{{asset('assetsPages/assets/img/company-logos/company5.jpg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end logo carousel -->

<!-- SweetAlert2 Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.remove-from-wishlist', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const item = $(this).closest('.wishlist-item');

        // Display SweetAlert for confirmation
        Swal.fire({
            title: 'Are you sure to remove product ?',
            text: "You won't be able to undo this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#118B50',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send request to remove the product
                $.ajax({
                    url: "{{ route('wishlist.remove') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId,
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            Swal.fire({
                                title: 'Removed!',
                                text: 'The product has been removed from your wishlist.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Remove the item from the page after confirmation
                                item.remove();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'Failed to remove the product from your wishlist.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'An error occurred while processing your request. Please try again later.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
</script>

@endsection
