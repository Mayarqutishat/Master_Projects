

@extends('layouts.masterPage')
@section('content')


	<!-- breadcrumb-section -->
	 
	<div class="breadcrumb-section hero-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<p>We sale embroidery Tools</p>
						<h1>About Us</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- featured section -->
	<div class="feature-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-7">
					<div class="featured-text">
						<h2 class="pb-3">Why <span class="orange-text">Alma Embroidery Tools</span></h2>
						<div class="row">
							<div class="col-lg-6 col-md-6 mb-4 mb-md-5">
								<div class="list-box d-flex">
									<div class="list-icon">
										<i class="fas fa-shipping-fast"></i>
									</div>
									<div class="content">
										<h3>Home Delivery</h3>
										<p>At Alma Embroidery Tools , we understand the joy of creating beautiful embroidery projects. That's why we offer a convenient home delivery service for all your embroidery tools.</p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 mb-5 mb-md-5">
								<div class="list-box d-flex">
									<div class="list-icon">
										<i class="fas fa-money-bill-alt"></i>
									</div>
									<div class="content">
										<h3>Best Price</h3>
										<p>At Alma Embroidery Tools , we believe that  every crafter deserves the high-quality embroidery tools  all at unbeatable prices!.</p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 mb-5 mb-md-5">
								<div class="list-box d-flex">
									<div class="list-icon">
										<i class="fas fa-briefcase"></i>
									</div>
									<div class="content">
										<h3>Custom Box</h3>
										<p>At Alma Embroidery Tools, we believe that every crafter is unique. That’s why we offer a customizable embroidery tools box that allows you to choose exactly what you need for your projects—all in one convenient package.</p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="list-box d-flex">
									<div class="list-icon">
										<i class="fas fa-sync-alt"></i>
									</div>
									<div class="content">
										<h3>Quick Refund</h3>
										<p>Customer Support :Our dedicated team is here to assist you at every step, answering any questions you may have about your return.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end featured section -->

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

@endsection