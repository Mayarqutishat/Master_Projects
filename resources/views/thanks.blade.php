// thanks.blade.php

@extends('layouts.masterPage')

@section('content')

<div class="thank-you-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Thank You for Your Order!</h1>
                <p>Your payment has been successfully processed. Your order is now complete.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
            </div>
        </div>
    </div>
</div>

@endsection
