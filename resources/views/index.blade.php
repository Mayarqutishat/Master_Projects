@extends('layouts.masterPage')
@section('content')
<!-- hero area -->
<div class="hero-area hero-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 offset-lg-2 text-center">
                <div class="hero-text">
                    <div class="hero-text-tablecell">
                        <h1>Embroidery Tools</h1>
                        <div class="hero-btns">
                            <a href="{{ url('/shop') }}" class="boxed-btn">SHOP NOW</a>
                            <a href="{{ url('/contact') }}" class="bordered-btn">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end hero area -->

<!-- features list section -->
<div class="list-section pt-80 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="list-box d-flex align-items-center">
                    <div class="list-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="content">
                        <h3>Free Shipping</h3>
                        <p>When order over 25 JO</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="list-box d-flex align-items-center">
                    <div class="list-icon">
                        <i class="fas fa-phone-volume"></i>
                    </div>
                    <div class="content">
                        <h3>24/7 Support</h3>
                        <p>Get support all day</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="list-box d-flex justify-content-start align-items-center">
                    <div class="list-icon">
                        <i class="fas fa-sync"></i>
                    </div>
                    <div class="content">
                        <h3>Refund</h3>
                        <p>Get refund within 1 day!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end features list section -->

<!-- product section -->
<div class="product-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">
                    <h3><span class="orange-text">New</span> Products</h3>
                    <p>Welcome to our Alma Embroidery Tools Collection.</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($products as $product)
            <div class="col-lg-4 col-md-6 text-center">
                <div class="single-product-item">
                    <div class="product-image">
                        <a href="single-product.html">
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
</div>
<!-- end product section -->

<!-- cart banner section -->
<section class="cart-banner pt-100 pb-100">
    <div class="container">
        <div class="row clearfix">
            <!--Image Column-->
            <div class="image-column col-lg-6">
                <div class="image">
                    <div class="price-box">
                        <div class="inner-price">
                            <span class="price">
                                <strong>coupon</strong>
                                <br>
                                <p>ALMA30</p>
                            </span>
                        </div>
                    </div>
                    <img src="{{asset('assetsPages/assets/img/product3.jpg') }}" alt=""/>
                </div>
            </div>
            <!--Content Column-->
            <div class="content-column col-lg-6">
                <h3><span class="orange-text">Deal</span> of the month</h3>
                <h4>Special embroidery thread</h4>
                <div class="text">Special embroidery threads are designed to add unique textures, colors, and effects to embroidery projects.</div>
                <!-- Countdown Timer -->
                <div class="time-counter">
                    <div class="time-countdown clearfix" data-countdown="2025/3/01">
                        <div class="counter-column"><div class="inner"><span class="count">00</span>Days</div></div>
                        <div class="counter-column"><div class="inner"><span class="count">00</span>Hours</div></div>
                        <div class="counter-column"><div class="inner"><span class="count">00</span>Mins</div></div>
                        <div class="counter-column"><div class="inner"><span class="count">00</span>Secs</div></div>
                    </div>
                </div>
                <!-- Removed "Add to Cart" button -->
            </div>
        </div>
    </div>
</section>
<!-- end cart banner section -->


<!-- testimonial slider -->
<!-- testimonial slider -->
<section class="testimonial-slider pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">
                    <h3><span class="orange-text">What</span> Our Customers Say</h3>
                    <p>Check out what our satisfied customers have to say about our products and service.</p>
                </div>
            </div>
        </div>
        <div class="testimonial-carousel">
            @foreach($testimonials as $testimonial)
                <div class="single-testimonial card">
                    <div class="testimonial-content">
                        <p>"{{ $testimonial->comment }}"</p>
                    </div>
                    <div class="testimonial-author">
                        <h4>{{ $testimonial->user->name }}</h4>
                        <span>Happy Customer</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- end testimonial slider -->





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

                // تحقق إذا كانت الأيقونة قد تم إضافتها لقائمة الرغبات
                if (icon.style.color !== 'red') {
                    // إضافة إلى قائمة الرغبات
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
                        // تغيير لون الأيقونة إلى اللون الأحمر
                        icon.style.color = 'red';
                    })
                    .catch(error => {
                        console.log(error);
                    });
                } else {
                    // إزالة من قائمة الرغبات
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
                        // إعادة اللون إلى الحالة الافتراضية
                        icon.style.color = '';
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
