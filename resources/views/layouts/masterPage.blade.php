<!DOCTYPE html>
<html lang="en">
@include('layouts.page.header')

<body>
@include('layouts.page.navbar')
@yield('content')

@include('layouts.page.footer')

    <!-- jquery -->
	<script src="{{asset('assetsPages/assets/js/jquery-1.11.3.min.js') }}"></script>
	<!-- bootstrap -->
	<script src="{{asset('assetsPages/assets/bootstrap/js/bootstrap.min.js') }}"></script>
	<!-- count down -->
	<script src="{{asset('assetsPages/assets/js/jquery.countdown.js') }}"></script>
	<!-- isotope -->
	<script src="{{asset('assetsPages/assets/js/jquery.isotope-3.0.6.min.js') }}"></script>
	<!-- waypoints -->
	<script src="{{asset('assetsPages/assets/js/waypoints.js') }}"></script>
	<!-- owl carousel -->
	<script src="{{asset('assetsPages/assets/js/owl.carousel.min.js') }}"></script>
	<!-- magnific popup -->
	<script src="{{asset('assetsPages/assets/js/jquery.magnific-popup.min.js')}}"></script>
	<!-- mean menu -->
	<script src="{{asset('assetsPages/assets/js/jquery.meanmenu.min.js') }}"></script>
	<!-- sticker js -->
	<script src="{{asset('assetsPages/assets/js/sticker.js') }}"></script>
	<!-- main js -->
	<script src="{{asset('assetsPages/assets/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>