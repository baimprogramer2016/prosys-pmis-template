@extends('layouts.app')

@push('top')
  
    <!-- Swiper CSS -->
 
    
    <style>
     
        .swiper-container {
            width: 80%;
            padding-top: 50px;
            padding-bottom: 50px;
            border-radius: 10px;
            /* margin-right: 50px;
            margin-left: 50px; */
            /* padding-left: 50px; */
            
        }
        .swiper-slide {
        
            background: #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            height:200px;
            width:400px;
            border-radius: 10px;
            object-fit: cover;
            /* box-shadow: 0px 12px 20px #b0b7e1; Efek shadow */
            transition: transform 0.3s ease-in-out;
            border:3px solid #ffffff;
        }
        .con{
          border-radius: 20px;
          box-shadow: 0px 8px 12px rgba(239, 236, 236, 0.1); /* Efek shadow */
          /* background-color:blue; */
          background-image: url('/assets//img/slide/bg_box.jpg');
          background-size: cover;
          background-position: center; 
        
          /* width: 100%;
          height: 400px;  */
           background-repeat: no-repeat ;
          /* border:4px solid #fff; */
        }

        /* .marquee {
            width: 80%;
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
            animation: marquee 10s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes marquee {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(-100%);
            }
        } */

    </style>

@endpush
@section('content')
<div class="page-inner" >

  <div class=" d-flex justify-content-center con ">
    <div class="swiper-container ">
      <div class="swiper-wrapper">
        <img class="swiper-slide" src="{{asset('assets/img/slide/bg_slide2.jpg')}}" alt="">
        <img class="swiper-slide" src="{{asset('assets/img/slide/bg_slide1.jpg')}}" alt="">
        <img class="swiper-slide" src="{{asset('assets/img/slide/bg_slide3.jpg')}}" alt="">
       
          {{-- <div class="swiper-slide" style="background: #3498db;">Slide 2</div>
          <div class="swiper-slide" style="background: #e74c3c;">Slide 3</div>
          <div class="swiper-slide" style="background: #9b59b6;">Slide 4</div>
          <div class="swiper-slide" style="background: #f1c40f;">Slide 5</div> --}}
      </div>
      
      <!-- Pagination -->
      <div class="swiper-pagination"></div>
      
      <!-- Navigation Buttons -->
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
     </div>
  </div>

  <div class="row mt-3">
    <h3 class="fw-bold mb-3">Shortcut</h3>
    @can('view_doc_schedule')
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('schedule-management') }}">
        <div class="card p-2">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-secondary me-3">
              <i class="fa fa-dollar-sign"></i>
            </span>
            <div>
              <h5 class="mb-1">
                <b
                  > <small>Schedule Management</small></b
                >
              </h5>
              <small class="text-muted">Schedule</small>
            </div>
          </div>
        </div>
      </a>
      </div>
      @endcan
      @can('view_input_s_curve')
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('s-curve') }}">
        <div class="card p-2">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-success me-3">
              <i class="fa fa-shopping-cart"></i>
            </span>
            <div>
              <h5 class="mb-1">
                <b
                  > <small>Input S-Curve</small></b
                >
              </h5>
              <small class="text-muted">Schedule</small>
            </div>
          </div>
        </div>
      </a>
      </div>
      @endcan
      @can('view_s_curve')
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('s-curve-chart') }}">
        <div class="card p-2">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-danger me-3">
              <i class="fa fa-users"></i>
            </span>
            <div>
              <h5 class="mb-1">
                <b
                  > <small>S-Curve</small></b
                >
              </h5>
              <small class="text-muted">Schedule</small>
            </div>
          </div>
        </div>
      </a>
      </div>
      @endcan
      @can('view_progress')
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('s-curve-bar') }}">
        <div class="card p-2">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-warning me-3">
              <i class="fa fa-comment-alt"></i>
            </span>
            <div>
              <h5 class="mb-1">
                <b
                  > <small>Progress</small></b
                >
              </h5>
              <small class="text-muted">Schedule</small>
            </div>
          </div>
        </div>
      </a>
      </div>
      @endcan
      @can('view_sop')
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('sop') }}">
        <div class="card p-2">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-danger me-3">
              <i class="fa fa-comment-alt"></i>
            </span>
            <div>
              <h5 class="mb-1">
                <b
                  > <small> Project Procedure (SOP)</small></b
                >
              </h5>
              <small class="text-muted">Document Management</small>
            </div>
          </div>
        </div>
      </a>
      </div>
      @endcan
      @can('view_doc_engineering_mdr')
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('document-engineer-master-deliverables-register') }}">
        <div class="card p-2">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-info me-3">
              <i class="fa fa-comment-alt"></i>
            </span>
            <div>
              <h5 class="mb-1">
                <b
                  > <small>MDR</small></b
                >
              </h5>
              <small class="text-muted">Document Management</small>
            </div>
          </div>
        </div>
      </a>
      </div>
      @endcan
      @can('view_construction_document')
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('construction-document') }}">
        <div class="card p-2">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-primary me-3">
              <i class="fa fa-comment-alt"></i>
            </span>
            <div>
              <h5 class="mb-1">
                <b
                  > <small>Construction Document</small></b
                >
              </h5>
              <small class="text-muted">Document Management</small>
            </div>
          </div>
        </div>
      </a>
      </div>
      @endcan
      @can('view_field_instruction')
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('field-instruction') }}">
        <div class="card p-2">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-secondary me-3">
              <i class="fa fa-comment-alt"></i>
            </span>
            <div>
              <h5 class="mb-1">
                <b
                  > <small>Field Instruction</small></b
                >
              </h5>
              <small class="text-muted">Document Management</small>
            </div>
          </div>
        </div>
      </a>
      </div>
      @endcan
    </div>
  </div>
  

@endsection
@push('bottom')
   
    <!-- Swiper JS -->
  
    <script>
        var swiper = new Swiper('.swiper-container', {
            effect: 'coverflow',
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: 'auto',
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: true,
            },
            autoplay: {
        delay: 3000, // Ganti dengan waktu dalam milidetik (3000ms = 3 detik)
        disableOnInteraction: false, // Agar tetap berjalan setelah interaksi pengguna
    },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>

@endpush
