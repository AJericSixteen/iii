<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | III Advertising Services</title>
    <link rel="icon" href="./asset/img/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('./asset/img/2.jpg') no-repeat center center/cover;
        }
        .login-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }
        .logo {
            width: 100px;
            animation: rotateLogo 5s linear infinite;
            transform-style: preserve-3d;
        }
        @keyframes rotateLogo {
            from {
                transform: rotateY(0deg);
            }
            to {
                transform: rotateY(360deg);
            }
        }
        .input-group {
            margin: 15px 0;
            text-align: left;
        }
        label {
            display: block;
            font-weight: bold;
            color: white;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            background: rgba(255, 255, 255, 0.8);
            color: black;
            font-size: 16px;
        }
        input::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #2e3192;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }
        .login-btn:hover {
            background: #2e3192;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="./asset/img/logo.png" alt="logo" class="logo">
        <form action="login.php" method="POST">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>
