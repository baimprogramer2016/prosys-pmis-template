<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-card {
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
      <div class="card login-card p-4">
        <div class="d-flex justify-content-center w-full">
            <img src="assets/img/logo.png" alt="">
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
          <div class="mb-3">
            
            @error('username')
            <div class="text-danger text-center">{{ $message }}</div>
           @enderror
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="text" name="username" placeholder="Enter your Username" >
          </div>
          <div class="mb-3">
            @error('password')
            <div class="text-danger text-center">{{ $message }}</div>
            @enderror
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" >
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
        <div class="text-center mt-3">
         
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
