<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Schedule Management</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <style>
      #global-loader {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(255, 255, 255, 0.8);
          z-index: 9999;
          display: flex;
          align-items: center;
          justify-content: center;
      }
      #global-loader .spinner {
          border: 4px solid rgba(0, 0, 0, 0.1);
          border-left-color: #3498db;
          border-radius: 50%;
          width: 50px;
          height: 50px;
          animation: spin 1s linear infinite;
      }
      @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
      }
      .dataTables_wrapper .dataTables_paginate .paginate_button {
    padding:5px;
}



  </style>
    <link
      rel="icon"
      href="{{ asset('assets/img/kaiadmin/favicon.ico')}}"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/swiper-bundle.min.js')}}"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css')}}" />
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js')}}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["/assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/kaiadmin.min.css')}}" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" />
    @stack('top')
  </head>
  <body>
    <div id="global-loader">
      <div class="spinner"></div>
    </div>
    <div class="wrapper">
      @include("layouts.sidebar")

      <div class="main-panel">
        @include('layouts.navbar')

        <div class="container" style="display: none;">
            @yield('content')
        </div>

        @include('layouts.footer')
      </div>


    </div>
    <script>
      window.addEventListener('load', function() {
          // Hilangkan preloader setelah halaman selesai dimuat
          document.getElementById('global-loader').style.display = 'none';
          document.querySelector('.container').style.display = 'block';
      });
    </script>
    <!--   Core JS Files   -->
    <script src="{{asset('assets/js/core/jquery-3.7.1.min.js')}}"></script>
   
    <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
    <!-- jQuery Scrollbar -->
    <script src="{{asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js')}}"></script>
    <script src="{{asset('assets/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    
   @stack('bottom')

  </body>
</html>
