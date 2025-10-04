@extends('layouts.app')

@section('content')
    @push('top')
        <style type="text/css">
            html,
            body {
                height: 100%;
                padding: 0px;
                margin: 0px;

            }

            .dataTables_filter {
                margin-bottom: 10px;
            }

            .dt-buttons {
                margin-bottom: 10px;
                color: #fff;
            }

            .dataTables_wrapper .dataTables_paginate {
                margin-top: 20px;
                margin-bottom: 20px;
                /* Tambahkan margin bawah */
            }



            /* Ganti warna latar belakang header */
            #myTable thead th {
                background-color: #ebf5fb;
                /* Warna teks putih */
                font-size: 10px !important;
            }

            .bg-th {
                background-color: #ebf5fb;
            }

            #myTable thead th {
                padding: 5px;
                font-size: 8px !important;
            }

            #myTable tbody td {
                font-size: 10px !important;
                color: #2d2c2c
            }

            #pdf-viewer-modal .modal-dialog {
                max-width: 90%;
                margin: 1.75rem auto;
            }

            #pdf-viewer-modal .modal-body {
                height: 80vh;
                overflow-y: auto;
                padding: 0;
            }

            #pdf-canvas {
                width: 100%;
                display: block;
                margin: 0 auto;
            }
        </style>
        <style>
            .attendance-container {
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
                width: 100%;
                /* Agar responsif di dalam col-md-6 */
            }

            /* Header Absen Masuk */
            .header-masuk {
                background-color: #e6f7e9;
                /* Hijau muda */
                color: #28a745;
                padding: 1rem;
                font-size: 1.2rem;
                font-weight: bold;
                text-align: center;
            }

            /* Tombol Masuk */
            .btn-masuk-solid {
                background-color: #28a745;
                /* Hijau solid */
                border: none;
                border-radius: 0;
                padding: 1.5rem 0;
                font-size: 1.35rem;
                font-weight: bold;
                width: 100%;
            }

            /* Header Absen Pulang */
            .header-pulang {
                background-color: #fcebeb;
                /* Merah muda */
                color: #dc3545;
                padding: 1rem;
                font-size: 1.2rem;
                font-weight: bold;
                text-align: center;
            }

            /* Tombol Pulang */
            .btn-pulang-solid {
                background-color: #dc3545;
                /* Merah solid */
                border: none;
                border-radius: 0;
                padding: 1.5rem 0;
                font-size: 1.35rem;
                font-weight: bold;
                width: 100%;
            }
        </style>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.1/css/buttons.dataTables.css" />
    @endpush
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row  pb-2">
            <div class="d-flex align-items-center gap-4">

                <h6 class="op-7 mb-2">Document Management/ Attendance</h6>

            </div>

            {{-- <div class="ms-md-auto py-2 py-md-0">
                @can('add_mrr')
                    <a href="{{ route('mrr-tambah') }}" class="btn btn-primary btn-round">Tambah</a>
                @endcan
            </div> --}}
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="container my-4">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-6 text-center bg-white p-4 border rounded mb-4">

                                            <!-- Header -->
                                            <h3 class="fw-bold mb-3">Live Attendance</h3>

                                            <!-- Work Description -->
                                            <div class="mb-3">

                                                <textarea class="form-control" id="work_description" name="work_description" rows="3"
                                                    placeholder="Work Description....">{{ optional($absen_today)->work_description }}</textarea>
                                            </div>

                                            <!-- Attendance Buttons -->
                                            <div class="d-flex justify-content-center gap-3 p-3">
                                                <!-- Check In -->
                                                <div class="flex-fill">
                                                    <div class="fw-bold text-success mb-2" style="font-size: 2.5rem;"
                                                        id="check_in_time">
                                                        {{ optional($absen_today)->check_in ? \Carbon\Carbon::parse($absen_today->check_in)->format('H:i') : '00:00' }}
                                                    </div>
                                                    <button class="btn btn-success w-100 py-3 fw-bold" id="btn-in"
                                                        onClick="getLocation('IN')">
                                                        Clock In
                                                    </button>
                                                </div>

                                                <!-- Check Out -->
                                                <div class="flex-fill">
                                                    <div class="fw-bold text-danger mb-2" style="font-size: 2.5rem;"
                                                        id="check_out_time">
                                                        {{ optional($absen_today)->check_out ? \Carbon\Carbon::parse($absen_today->check_out)->format('H:i') : '00:00' }}
                                                    </div>
                                                    <button class="btn btn-danger w-100 py-3 fw-bold" id="btn-out"
                                                        onClick="getLocation('OUT')">
                                                        Clock Out
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Location Info -->
                                            <p id="location_check"></p>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12">
                <div class="card ">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>

                                        <th class="bg-th">Date</th>
                                        <th class="bg-th">Clock In</th>
                                        <th class="bg-th">Clock In Address</th>
                                        <th class="bg-th">Lat, Long</th>
                                        <th class="bg-th">Clock Out</th>
                                        <th class="bg-th">Clock Out Address</th>
                                        <th class="bg-th">Lat, Long</th>
                                        <th class="bg-th">Work Desciption</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-pdf" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen"> <!-- Tambahkan modal-lg di sini -->
            <div class="modal-content">

            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-large" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- Tambahkan modal-lg di sini -->
            <div class="modal-content">

            </div>
        </div>
    </div>
@endsection

@push('bottom')
    <!-- Bootstrap 5 + DataTables -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Buttons Scripts -->
    <script src="https://cdn.datatables.net/buttons/3.2.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.1/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.1/js/buttons.print.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Autosize textarea
        const textarea = document.getElementById('work_description');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto'; // reset height
            this.style.height = this.scrollHeight + 'px'; // sesuaikan dengan konten
        });
    </script>
    <script>
        // Inisialisasi map dengan posisi default (Jakarta)
        // const map = L.map("map").setView([-6.2, 106.816666], 10);

        // // Tambahkan tile layer dari OSM
        // L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        //     attribution: '&copy; <a href="https://www.openstreetmap.org/">OSM</a> contributors'
        // }).addTo(map);

        // let marker;

        // Fungsi untuk ambil alamat dari lat-lng (reverse geocoding)
        async function getAddress(lat, lon) {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`;
            const response = await fetch(url, {
                headers: {
                    "User-Agent": "my-app"
                }
            });
            const data = await response.json();
            return data.display_name || "Alamat tidak ditemukan";
        }

        // Fungsi tombol
        function getLocation(param) {

            const work_description = document.getElementById("work_description").value;
            if (!work_description || work_description.trim() === "") {
                alert("Please enter your work description before checking in/out.");
                return;
            }

            if (navigator.geolocation) {

                if (param == 'IN') {
                    document.getElementById("btn-in").disabled = true;
                    document.getElementById("btn-in").innerText = "Loading...";
                } else {
                    document.getElementById("btn-out").disabled = true;
                    document.getElementById("btn-out").innerText = "Loading...";
                }
                navigator.geolocation.getCurrentPosition(async (pos) => {
                    const lat = pos.coords.latitude;
                    const lon = pos.coords.longitude;
                    const address = await getAddress(lat, lon);

                    const now = new Date();

                    // 1. Tanggal lengkap beserta jam, menit, detik (lokal)
                    const tanggalLengkap =
                        now.getFullYear() + "-" +
                        String(now.getMonth() + 1).padStart(2, "0") + "-" +
                        String(now.getDate()).padStart(2, "0") + " " +
                        String(now.getHours()).padStart(2, "0") + ":" +
                        String(now.getMinutes()).padStart(2, "0") + ":" +
                        String(now.getSeconds()).padStart(2, "0");

                    // 2. Hanya jam dan menit (lokal)
                    const jamMenit =
                        String(now.getHours()).padStart(2, "0") + ":" +
                        String(now.getMinutes()).padStart(2, "0");




                    const payload = {
                        _token: "{{ csrf_token() }}",
                        latitude: lat,
                        longitude: lon,
                        type: param,
                        address: address,
                        check_time: tanggalLengkap,
                        work_description: $("#work_description").val()
                    }



                    $.ajax({
                        url: "{{ route('presensi-add') }}",
                        type: "POST",
                        data: payload,
                        success: function(response) {
                            console.log(response);
                            if (response.status == 'success') {
                                msg_swal = response.message;
                                color = "btn btn-success";

                                if (param == 'IN') {
                                    document.getElementById("check_in_time").innerText = jamMenit;
                                } else {
                                    document.getElementById("check_out_time").innerText = jamMenit;
                                }




                            } else {
                                msg_swal = response.message;
                                color = "btn btn-danger";
                            }
                            swal(msg_swal, {
                                buttons: {
                                    confirm: {
                                        className: color,
                                    },
                                },
                            });
                            // sendEmail(email, status, description)

                            location.reload();

                            if (param == 'IN') {
                                document.getElementById("btn-in").disabled = false;
                                document.getElementById("btn-in").innerText = "Check In";
                            } else {
                                document.getElementById("btn-out").disabled = false;
                                document.getElementById("btn-out").innerText = "Check Out";
                            }
                        },
                        error: function(xhr) {
                            alert('An error occurred: ' + xhr.responseText);
                        }
                    });


                }, (err) => {
                    alert("Gagal mendapatkan lokasi: " + err.message);
                });
            } else {
                alert("Browser tidak mendukung Geolocation API");
            }
        }
    </script>
    <script>
        function viewDelete(param) {
            $(".modal-content").html("");
            $.ajax({
                url: "{{ route('mrr-delete', ':id') }}".replace(':id', param), // Ganti dengan route yang sesuai
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $(".modal-content").html("");
                    $(".modal-content").html(response);

                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        }

        function viewShare(param) {
            $(".modal-content").html("");
            $.ajax({
                url: "{{ route('mrr-share', ':id') }}".replace(':id', param), // Ganti dengan route yang sesuai
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $(".modal-content").html("");
                    $(".modal-content").html(response);

                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        }

        function viewPdf(param) {
            $(".modal-content").html("");
            $.ajax({
                url: "{{ route('mrr-pdf', ':id') }}".replace(':id', param), // Ganti dengan route yang sesuai
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $(".modal-content").html("Harap menuggu, data sedang di muat......");
                    setTimeout(() => {
                        $(".modal-content").html(response);
                    }, 2000);

                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        }

        $(document).ready(function() {
            var table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                pageLength: 30, // Ini mengatur default jumlah data per halaman
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    sLengthMenu: "Show _MENU_"
                },

                ajax: {
                    //mdr tidak ada kondisi
                    url: "{{ route('get-presensi') }}",
                },
                dom: '<"d-flex flex-column"<"mb-2"B><"d-flex justify-content-between"lf>>rtip',
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export PDF',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn btn-primary btn-sm'
                    }
                ],

                columns: [{
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'check_in',
                        name: 'check_in'
                    },
                    {
                        data: 'check_in_address',
                        name: 'check_in_address'
                    },
                    {
                        data: 'latlong_in',
                        name: 'latlong_in',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'check_out',
                        name: 'check_out'
                    },
                    {
                        data: 'check_out_address',
                        name: 'check_out_address'
                    },
                    {
                        data: 'latlong_out',
                        name: 'latlong_out',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'work_description',
                        name: 'work_description'
                    },


                ],

            });
        });



        function viewHistory(param) {
            $(".modal-content").html("");
            $.ajax({
                url: "{{ route('mrr-history', ':id') }}".replace(':id', param), // Ganti dengan route yang sesuai
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $(".modal-content").html("");
                    $(".modal-content").html(response);

                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        }
    </script>
@endpush
