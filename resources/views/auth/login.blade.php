<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", sans-serif;
    }

    body {
      height: 100vh;
      background: linear-gradient(to top, #1e3c72, #2a5298);
      overflow: hidden;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .stars {
      width: 100%;
      height: 100%;
      background: transparent;
      position: absolute;
      top: 0;
      left: 0;
      z-index: 0;
      background: url('https://www.transparenttextures.com/patterns/stardust.png');
      opacity: 0.3;
    }

    .wave {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 150px;
      background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg"><path fill="%23ffffff" fill-opacity="1" d="M0,96L40,112C80,128,160,160,240,181.3C320,203,400,213,480,202.7C560,192,640,160,720,160C800,160,880,192,960,202.7C1040,213,1120,203,1200,181.3C1280,160,1360,128,1400,112L1440,96L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path></svg>') no-repeat bottom;
      background-size: cover;
      z-index: 0;
    }

    .login-container {
      z-index: 1;
      background: #fff;
      padding: 2rem 2.5rem;
      border-radius: 16px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #333;
    }

    .form-group {
      margin-bottom: 1.2rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.4rem;
      font-weight: 500;
    }

    .form-group input {
      width: 100%;
      padding: 0.7rem;
      border-radius: 8px;
      border: 1px solid #ccc;
      outline: none;
      font-size: 1rem;
    }

    .form-group input:focus {
      border-color: #4a90e2;
    }

    .login-btn {
      width: 100%;
      padding: 0.8rem;
      background: #4a90e2;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .login-btn:hover {
      background: #1450a3;
    }

    .text-center {
      text-align: center;
      margin-top: 1rem;
    }

    .text-center a {
      color: #4a90e2;
      text-decoration: none;
    }

    .alert {
      background: #ffdddd;
      padding: 10px;
      border-left: 5px solid red;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

  <div class="stars"></div>
  <div class="wave"></div>

  <div class="login-container">
    <h2>Welcome Back</h2>
    <div class="card-body">
      @if (session('error'))
        <div class="alert">{{ session('error') }}</div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" required placeholder="Enter your email">
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required placeholder="Enter your password">
        </div>

        <button type="submit" class="login-btn">Login</button>
      </form>
    </div>
  </div>

</body>
</html>
