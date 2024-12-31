@extends('layouts.masterPage')
@section('content')

<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Get 24/7 Support</p>
                    <h1>Contact us</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- contact form -->
<div class="contact-from-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-5 mb-lg-0">
                <div class="form-title">
                    <h2>Have you any question?</h2>
                </div>
                <div id="form_status"></div>
                <div class="contact-form">
                    <form id="contact-form">
                        <p>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                        </p>
                        <p>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                        </p>
                        <p>
                            <textarea class="form-control" id="message" name="message" cols="30" rows="10" placeholder="Message" required></textarea>
                        </p>
                        <p>
                            <input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
                        </p>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-form-wrap">
                    <div class="contact-form-box">
                        <h4><i class="fas fa-map"></i> Shop Address</h4>
                        <p>AMMAN, JORDAN</p>
                    </div>
                    <div class="contact-form-box">
                        <h4><i class="far fa-clock"></i> Shop Hours</h4>
                        <p>MON - FRIDAY: 8 to 9 PM <br> SAT - SUN: 10 to 8 PM</p>
                    </div>
                    <div class="contact-form-box">
                        <h4><i class="fas fa-address-book"></i> Contact</h4>
                        <p>Phone: 0779348106 <br> Email: Alma_shop@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end contact form -->




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



<!-- إضافة مكتبة SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- إضافة سكرipts الخاص بـ EmailJS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
<script type="text/javascript">
    window.onload = function() {
        emailjs.init("fVW3VQg4d9h_2e6Rq"); // استبدل بـ User ID الخاص بك من EmailJS
    };

    document.getElementById("contact-form").addEventListener("submit", function(event) {
        event.preventDefault(); // لمنع إعادة تحميل الصفحة عند إرسال النموذج

        // إرسال البريد الإلكتروني عبر EmailJS
        emailjs.send('service_8zk38z6', 'template_ldd235u', {
            from_name: document.getElementById('name').value,
            from_email: document.getElementById('email').value,
            message: document.getElementById('message').value
        }).then((response) => {
            Swal.fire({
                icon: 'success',
                title: 'Message Sent!',
                text: 'Your message has been sent successfully.',
                confirmButtonText: 'OK'
            });
        }).catch((error) => {
            console.error('Failed to send message', error);
            Swal.fire({
                icon: 'error',
                title: 'Failed to Send',
                text: 'There was an error sending your message. Please try again later.',
                confirmButtonText: 'OK'
            });
        });
    });
</script>

@endsection
