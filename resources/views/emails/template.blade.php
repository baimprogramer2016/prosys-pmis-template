<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            background: #ffffff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            width: 200px;
            margin-bottom: 20px;
        }
        .header {
            background: #004080;
            color: #ffffff;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            font-size: 20px;
        }
        .content {
            padding: 20px;
            text-align: left;
            font-size: 16px;
            color: #333;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background: #004080;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #002a5c;
        }
    </style>
</head>
<body>

<div class="email-container">
    {{-- <img src="https://image1ws.indotrading.com/s3/webp/co50732/companylogo/w400-h220/ca9620ec-9b56-4c54-aec5-537fcb4ff895.jpg" alt="Prosys Bangun Persada" class="logo"> --}}
    
    <div class="header">
        {{ $subject }}
    </div>

    <div class="content">
        {{-- <p>Yth. {{ $name }},</p> --}}
        <p>{{ $body }}</p>
        <p>Title : {{ $description}}</p>

        <p>Silakan cek dokumen terkait melalui Sistem kami.</p>

        {{-- <a href="https://yourwebsite.com/dashboard" class="btn">Cek Sekarang</a> --}}
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Prosys Bangun Persada. Semua Hak Dilindungi.</p>
        <p>Jl. Letjen S Parman Kav 76 Slipi Palmerah Jakarta Barat DKI Jakarta, RT.4/RW.3, Slipi, Kec. Palmerah, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11410</p>
    </div>
</div>

</body>
</html>
