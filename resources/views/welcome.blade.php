<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>1936BOIS</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
    <style>
        html, body {
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            margin: 0;
            height: 100vh;
            background-color: #5a5a5f;
            color: #333;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 85%;
            padding: 20px;
        }
        .top-links {
            position: absolute;
            top: 55px;
            right: 60px;
        }
        .top-links a {
            color: #333;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 100px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 25px; /* Slightly smaller gap */
            width: 100%;
            max-width: 1400px; /* Slightly smaller max-width */
            margin-top: 100px;
        }
        .grid-item {
            position: relative;
            background-color: #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            width: 100%;
            height: 250px; /* Slightly smaller height */
        }
        .grid-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.7;
        }
        .grid-item span {
            position: absolute;
        }
        .register-now {
            grid-column: 3 / 4;
            grid-row: 1 / 3;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            height: 500px; /* Adjusted height to match combined height of two grid items */
        }
        .register-now img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .register-now .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(231, 76, 60, 0.6); /* Orange with low opacity */
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }
        .register-now a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid #fff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .register-now a:hover {
            background-color: #c0392b;
        }
        .footer {
            position: absolute;
            bottom: 80px;
            right: 420px;
            display: flex;
            gap: 10px;
        }
        .footer a {
            color: #333;
            text-decoration: none;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="/build/images/1936bois.png" alt="Logo" class="logo">
        <div class="top-links">
            @if (Route::has('login'))
                <a href="{{ route('login') }}">Shop</a>
            @endif
            @if (Route::has('login'))
                <a href="{{ route('login') }}">Event</a>
            @endif
            @if (Route::has('register'))
                <a href="{{ route('register') }}">About Us</a>
            @endif
            @if (Route::has('login'))
                <a href="{{ route('login') }}">Log In</a>
            @endif
        </div>
        <div class="grid">
            <div class="grid-item">
                <img src="/build/images/2.jpg" alt="Discipline">
                <span>Discipline<br>Reach Goals</span>
            </div>
            <div class="grid-item">
                <img src="/build/images/1.jpg" alt="Hard">
                <span>Hard<br>To Deny</span>
            </div>
            <div class="register-now">
                <img src="/build/images/5.jpeg" alt="Register Now">
                <div class="overlay">
                    <a href="{{ route('register') }}">Register Now</a>
                </div>
            </div>
            <div class="grid-item">
                <img src="/build/images/3.jpg" alt="Group">
                <span>Dedication<br>Faith</span>
            </div>
            <div class="grid-item">
                <img src="/build/images/4.jpg" alt="Winner">
                <span>Winner<br>Attitude</span>
            </div>
        </div>
    </div>
</body>
</html>
