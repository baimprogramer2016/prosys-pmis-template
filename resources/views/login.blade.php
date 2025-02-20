<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-size: cover;
      background-position: center;
      transition: background-image 1s ease-in-out;
    }
    .login-card {
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      background: #fff;
    }
    .tracking-tighter {
    letter-spacing: -0.09em; /* Sesuaikan sesuai kebutuhan */
    font-weight: 600
  }
  
.logo_prosys {
    position: absolute;
    left: 2%;  /* Menempatkan logo di sisi kiri */
    top: 2%;  /* Menempatkan logo di sisi atas */
    transform: none; /* Hapus translateY(-50%) karena tidak diperlukan */
    width:150px;
}

  </style>
</head>
<body>

<div class="container">
  <img class="logo_prosys" src="{{asset('assets/img/login/logo_prosys.png')}}" alt="">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
      <div class="card login-card p-4">
        <div class="d-flex justify-content-center w-full">
            {{-- <img src="assets/img/logo.png" alt=""> --}}
            <h1 class="tracking-tighter"><span style="color:#4f5052;">PMIS</span> <span style="color:#244092;">PROSYS</span></h1>
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
         {{-- v.1.0.0 --}}
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Daftar gambar background
  const backgrounds = [
    'assets/img/login/bg1.jpg',
    'assets/img/login/bg2.jpg',
    'assets/img/login/bg3.jpg',
    'assets/img/login/bg4.jpg',
    'assets/img/login/bg5.jpg',
    'assets/img/login/bg6.jpg',
    'assets/img/login/bg7.jpg',
    'assets/img/login/bg8.jpg',
    'assets/img/login/bg9.jpg',
    'assets/img/login/bg10.jpg',
    'assets/img/login/bg11.jpg',
    'assets/img/login/bg12.jpg',
  ];

  let currentBackgroundIndex = 0;

  // Fungsi untuk mengubah background
  function changeBackground() {
    document.body.style.backgroundImage = `url('${backgrounds[currentBackgroundIndex]}')`;
    currentBackgroundIndex = (currentBackgroundIndex + 1) % backgrounds.length;
  }

  // Ganti background setiap 5 detik
  setInterval(changeBackground, 5000);

  // Set background awal
  changeBackground();
</script>

</body>
</html>