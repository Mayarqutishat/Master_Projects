@extends('layouts.masterPage')

@section('content')

<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <h1>Check Out</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- checkout-section -->
<div class="checkout-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="checkout-accordion-wrap">
                    <div class="accordion" id="accordionExample">
                        <div class="card single-accordion">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Billing Details
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="billing-address-form">
                                        <form id="billingForm">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" id="name" class="form-control" value="{{ $user->name }}" placeholder="Enter your name" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="tel" id="phone" class="form-control" value="{{ $user->phone }}" placeholder="Enter your phone" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input type="text" id="address" class="form-control" value="{{ $user->address }}" placeholder="Enter your address" disabled>
                                            </div>
                                            <a href="#" id="editInfoBtn" class="btn btn-primary mt-3">Edit Your Information</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="order-details-wrap">
                    <table class="order-details">
                        <thead>
                            <tr>
                                <th colspan="2" class="order-title">Your Order Details</th>
                            </tr>
                        </thead>
                        <tbody class="order-details-body">
                            <tr>
                                <td>Product</td>
                                <td>Total</td>
                            </tr>
                            @foreach($cartItems as $item)
                                <tr>
                                    <td>{{ $item['name'] }} x {{ $item['quantity'] }}</td>
                                    <td>{{ $item['price'] * $item['quantity'] }} JD</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tbody class="checkout-details">
                            <tr>
                                <td>Subtotal</td>
                                <td>{{ $subtotal }} JD</td>
                            </tr>
                            <tr>
                                <td>Shipping</td>
                                <td>5 JD</td>
                            </tr>
                            @if($couponDiscount > 0)
                                <tr>
                                    <td>Coupon Discount</td>
                                    <td>-{{ $couponDiscount }} JD</td>
                                </tr>
                            @endif
                            <tr>
                                <td>Total</td>
                                <td>{{ $subtotal + 5 - $couponDiscount }} JD</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Stripe Payment Form -->
                    <form id="checkout-form" method="post" action="{{ url('stripe.post') }}">
                        @csrf
                        <input type="hidden" name="stripeToken" id="stripe-token-id">
                        <button 
    id="pay-btn"
    class="btn btn-success mt-3"
    type="button"
    style="margin-top: 20px; width: 100%; padding: 7px;"
    onclick="redirectToStripePaymentPage()">PAY ${{ $total }}
</button>

<script type="text/javascript">
    function redirectToStripePaymentPage() {
        window.location.href = "{{ route('stripe') }}";
    }
</script>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Stripe Scripts -->
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    function createToken() {
        document.getElementById("pay-btn").disabled = true;
        stripe.createToken(cardElement).then(function(result) {
            if (typeof result.error != 'undefined') {
                document.getElementById("pay-btn").disabled = false;
                alert(result.error.message);
            }
            if (typeof result.token != 'undefined') {
                document.getElementById("stripe-token-id").value = result.token.id;
                document.getElementById('checkout-form').submit();
            }
        });
    }
</script>

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

@endsection  

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editInfoBtn = document.querySelector('#editInfoBtn');
        const nameInput = document.querySelector('#name');
        const phoneInput = document.querySelector('#phone');
        const addressInput = document.querySelector('#address');
        
        editInfoBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (editInfoBtn.innerHTML === 'Edit Your Information') {
                // Enable the inputs for editing
                nameInput.disabled = false;
                phoneInput.disabled = false;
                addressInput.disabled = false;
                
                // Change the button to save
                editInfoBtn.innerHTML = 'Save Changes';
                editInfoBtn.classList.remove('btn-primary');
                editInfoBtn.classList.add('btn-success');

                // Update the event listener for saving the changes
                editInfoBtn.removeEventListener('click', saveInfoHandler);
                editInfoBtn.addEventListener('click', saveInfoHandler);
            } else {
                saveInfoHandler(); // Save the info when clicked
            }
        });

        function saveInfoHandler() {
            // Send an AJAX request to save the data
            const formData = new FormData();
            formData.append('name', nameInput.value);
            formData.append('phone', phoneInput.value);
            formData.append('address', addressInput.value);
            
            // Send the data to the backend
            fetch("{{ route('user.updateProfile') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Information Updated',
                        text: 'Your information has been updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    // Disable the inputs after saving
                    nameInput.disabled = true;
                    phoneInput.disabled = true;
                    addressInput.disabled = true;
                    editInfoBtn.innerHTML = 'Edit Your Information';
                    editInfoBtn.classList.remove('btn-success');
                    editInfoBtn.classList.add('btn-primary');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'There was an error updating your information.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
</script>
@endpush
