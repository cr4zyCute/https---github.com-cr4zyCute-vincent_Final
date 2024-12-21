<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #5661a6, #c6b9e2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            padding: 40px 30px;
            box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.2);
            width: 360px;
            text-align: center;
            position: relative;
        }

        .profile-icon {
            background: #294b81;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: -60px auto 20px;
            position: relative;
            z-index: 1;
        }

        .profile-icon img {
            width: 40px;
            height: 40px;
            filter: invert(1);
        }

        .login-container input {
            width: 100%;
            max-width: 300px; 
            padding: 15px;
            margin: 15px auto;
            border: none;
            outline: none;
            border-radius: 5px;
            background: #f0f3fc;
            font-size: 16px;
            box-shadow: inset 0px 2px 5px rgba(0, 0, 0, 0.1);
            display: block;
        }

        .login-container input::placeholder {
            color: #666;
            font-size: 14px;
        }

        .register__here {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            margin-top: 10px;
        }

        .register__here a {
            color: #294b81;
            text-decoration: none;
            font-size: 14px;
        }

        .register__here a:hover {
            text-decoration: underline;
        }

        .login-container button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: #294b81;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .login-container button:hover {
            background: #3a609a;
            box-shadow: 0px 5px 15px rgba(42, 75, 129, 0.5);
        }

        .login-container label {
            display: flex;
            align-items: center;
        }

        .login-container label input {
            margin-right: 5px;
        }

        .login-button-container {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 50px;
            background: #294b81;
            border-radius: 25px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            color: white;
            font-size: 18px;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .login-button-container:hover {
            background: #3a609a;
            transform: translateX(-50%) scale(1.05);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="profile-icon">
            <img src="https://via.placeholder.com/40" alt="User Icon">
        </div>
        <form>
            <input type="text" placeholder="Username" required>
            <input type="password" placeholder="Password" required>
            <div class="register__here">
                <label>
                    <input type="checkbox"> Remember me
                </label>
                <a href="#">Forgot Password?</a>
            </div>
        </form>
        <div class="login-button-container">
            LOGIN
        </div>
    </div>
</body>
</html>