<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Dashboard Admin')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .sidebar {
      width: 220px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #4169E1;
      padding-top: 60px;
      color: white;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
    }

    .sidebar a:hover {
      background-color: #3652B3;
    }

    .main-content {
      margin-left: 220px;
      padding: 20px;
      flex: 1;
    }

    .header {
      height: 60px;
      background-color: #4169E1;
      color: white;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
      position: fixed;
      top: 0;
      left: 220px;
      right: 0;
      z-index: 1000;
    }

    .footer {
      background-color: #f1f1f1;
      padding: 10px;
      text-align: center;
      font-size: 14px;
      margin-left: 220px;
    }
  </style>
</head>
<body>

  @include('partials.sidebar')

  @include('partials.header')

  <div class="main-content mt-5 pt-3">
    @yield('content')
  </div>

  @include('partials.footer')

</body>
</html>
