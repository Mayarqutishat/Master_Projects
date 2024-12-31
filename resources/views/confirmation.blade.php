<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Order Success</title>
    <style>
        body {
            background:rgb(232, 193, 216); 
            color: #333;
            height: 100vh; 
            display: flex;
            align-items: center; 
            justify-content: center; 
            margin: 0; 
            font-family: 'Arial', sans-serif; 
            text-align: center;
        }
        .logo {
            width: 150px; 
            margin-bottom: 20px; 
        }
        h1 {
            font-size: 2rem; 
            margin-bottom: 10px; 
            background:#c04d90;
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }
        h3 {
            font-size: 2.5rem; 
            margin: 10px 0; 
        }
        .highlight {
            background:#c04d90;
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            font-size: 2.5rem;
        }
        h5 {
            font-size: 1.1rem; 
            margin-bottom: 10px; 
        }
    
        .btn-dark-gray {
            background-color: #c04d90; 
            color: white;
            transition: background-color 0.3s;
        }
        .btn-dark-gray:hover {
            background-color:#c04d90;  
            color: #f8f8f8;
        }
    </style>
</head>
<body>

<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <img  src="{{ asset('assetsPages/assets/img/logo/logo2.png') }}" alt="Logo" class="logo"> 
            <h1>Order Successful!</h1>
            <h5>Your order is being processed and will arrive soon!</h5>
            <h4><span class="highlight">Thank you</span> for shopping with us!</h4>
            <h5>We appreciate your choice and look forward to serving you again.</h5>
            <a href="{{ url('/') }}" class="btn btn-dark-gray mt-3">Home</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
    });
</script>
</body>
</html>
