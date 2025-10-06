<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS Kustom Dasar (Dipertahankan) */
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
            /* Tambahkan font Bootstrap/system default */
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

        .tracking-tighter {
            letter-spacing: -0.09em;
            font-weight: 700 !important;
            /* Ditingkatkan agar lebih tegas */
        }

        .logo_prosys {
            position: absolute;
            left: 2%;
            top: 2%;
            transform: none;
            width: 150px;
            z-index: 10;
        }

        /* --- CSS Tambahan untuk Tampilan Modern --- */

        .login-card {
            /* Warna Latar Belakang Putih dengan sedikit transparansi */
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            /* Sudut lebih membulat */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            /* Bayangan yang lebih dalam */
            border: none;
            backdrop-filter: blur(5px);
            /* Efek blur pada backdrop */
        }

        .form-control:focus {
            border-color: #244092;
            /* Warna fokus sesuai merek */
            box-shadow: 0 0 0 0.25rem rgba(36, 64, 146, 0.25);
            /* Shadow fokus yang halus */
        }

        .btn-primary {
            background-color: #244092;
            /* Warna tombol sesuai merek */
            border-color: #244092;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1a3070;
            /* Warna hover sedikit lebih gelap */
            border-color: #1a3070;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center text-center">
            <div class="col-auto">
                <img class="logo_prosys" src="{{ asset('assets/img/login/logo_prosys.jpg') }}" alt="PROSYS Logo"
                    width="120">
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-4">
                <div class="card login-card p-5">
                    <div class="d-flex justify-content-center align-items-center w-100 mb-4">
                        <h1 class="tracking-tighter display-5 me-2"> <span style="color:#4f5052;">PMIS</span>
                            <span style="color:#244092;">PROSYS</span>
                        </h1>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            @error('username')
                                <div class="text-danger text-center small mb-2">{{ $message }}</div>
                            @enderror
                            <label for="username" class="form-label small fw-semibold">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Enter your Username" required>
                        </div>
                        <div class="mb-4">
                            @error('password')
                                <div class="text-danger text-center small mb-2">{{ $message }}</div>
                            @enderror
                            <label for="password" class="form-label small fw-semibold">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter your password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Login</button>
                        </div>
                    </form>
                    <div class="text-center mt-4 text-muted small">
                        {{-- v.1.0.0 --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Daftar gambar background (Logika JS tidak diubah)
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
