document.addEventListener("DOMContentLoaded", function() {
    fetch("https://restcountries.com/v3.1/all")
        .then((response) => response.json())
        .then((data) => {
            const countrySelect = document.getElementById("country");
            data.forEach((country) => {
                const option = document.createElement("option");
                option.value = country.name.common;
                option.textContent = country.name.common;
                countrySelect.appendChild(option);
            });
        })
        .catch((error) => console.error("Error fetching countries:", error));
});

$(document).ready(function () {
    'use strict';

    var user_name_Error = true,
        emailError = true,
        passwordError = true,
        passConfirmError = true;

    // Label effect
    $('input').focus(function () {
        $(this).siblings('label').addClass('active');
    });

    // Form validation
    $('input[name="user_name"]').blur(function () {
        if ($(this).val().length === 0) {
            $(this).siblings('span.error').text('Please type your full name').fadeIn().parent('.form-group').addClass('hasError');
            user_name_Error = true;
        } else if ($(this).val().length < 4) {
            $(this).siblings('span.error').text('Please type at least 4 characters').fadeIn().parent('.form-group').addClass('hasError');
            user_name_Error = true;
        } else {
            $(this).siblings('.error').text('').fadeOut().parent('.form-group').removeClass('hasError');
            user_name_Error = false;
        }
    });

    $('input[name="email"]').blur(function () {
        if ($(this).val().length === 0) {
            $(this).siblings('span.error').text('Please type your email address').fadeIn().parent('.form-group').addClass('hasError');
            emailError = true;
        } else {
            $(this).siblings('.error').text('').fadeOut().parent('.form-group').removeClass('hasError');
            emailError = false;
        }
    });

    $('input[name="password"]').blur(function () {
        var passwordVal = $(this).val();
        var hasNumber = /\d/.test(passwordVal);
        var hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(passwordVal);
        if (passwordVal.length < 8) {
            $(this).siblings('span.error').text('Please type at least 8 characters').fadeIn().parent('.form-group').addClass('hasError');
            passwordError = true;
        } else if (!hasNumber) {
            $(this).siblings('span.error').text('Password must contain at least one number').fadeIn().parent('.form-group').addClass('hasError');
            passwordError = true;
        } else if (!hasSpecialChar) {
            $(this).siblings('span.error').text('Password must contain at least one special character').fadeIn().parent('.form-group').addClass('hasError');
            passwordError = true;
        } else {
            $(this).siblings('.error').text('').fadeOut().parent('.form-group').removeClass('hasError');
            passwordError = false;
        }
    });

    $('input[name="passConfirm"]').blur(function () {
        if ($('input[name="password"]').val() !== $(this).val()) {
            $(this).siblings('.error').text('Passwords don\'t match').fadeIn().parent('.form-group').addClass('hasError');
            passConfirmError = true;
        } else {
            $(this).siblings('.error').text('').fadeOut().parent('.form-group').removeClass('hasError');
            passConfirmError = false;
        }
    });

    // Prevent form submission if there are errors
    $('.signup-form').submit(function (e) {
        if (user_name_Error || emailError || passwordError || passConfirmError) {
            e.preventDefault(); // Prevent form submission
            alert('Please correct the errors before submitting.');
        }
    });

    // Form switch
    $('a.switch').click(function (e) {
        $(this).toggleClass('active');
        e.preventDefault();
        if ($(this).hasClass('active')) {
            $(this).parents('.form-peice').addClass('switched').siblings('.form-peice').removeClass('switched');
        } else {
            $(this).parents('.form-peice').removeClass('switched').siblings('.form-peice').addClass('switched');
        }
    });
});