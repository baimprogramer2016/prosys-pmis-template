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

            /*SLIDE*/
            .toggle-container {
                width: 100%;
                max-width: 100%;
                height: 40px;
                background: #cbd3ceff;
                border-radius: 6px;
                position: relative;
                cursor: pointer;
                transition: background 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }

            .toggle-container.active {
                background: #e82323ff;
                /* merah jika break */
            }

            .toggle-knob {
                width: 12%;
                /* 🔥 proporsional agar ikut lebar container */
                min-width: 40px;
                max-width: 58px;
                height: calc(100% - 4px);
                background: linear-gradient(145deg, #ffffff, #e6e6e6);
                /* 🌈 gradasi elegan */
                border-radius: 10px;
                position: absolute;
                top: 2px;
                left: 5px;
                transition: all 0.3s ease;
                box-shadow:
                    0 3px 6px rgba(0, 0, 0, 0.15),
                    inset 0 2px 2px rgba(255, 255, 255, 0.7);
                /* efek muncul & depth */
            }

            /* Saat toggle aktif, knob sedikit “menyala” */
            .toggle-container.active .toggle-knob {
                background: linear-gradient(145deg, #fefefe, #f1f1f1);
                box-shadow:
                    0 3px 10px rgba(0, 0, 0, 0.25),
                    0 0 8px rgba(255, 255, 255, 0.6);
                left: calc(100% - 14%);
            }

            /* Tambahan efek hover agar terasa interaktif */
            .toggle-knob:hover {
                transform: scale(1.05);
                box-shadow:
                    0 4px 10px rgba(0, 0, 0, 0.25),
                    inset 0 2px 3px rgba(255, 255, 255, 0.8);
            }

            .toggle-container.active .toggle-knob {
                left: calc(100% - 11%);
                /* 100% - lebar knob (12%) - margin kecil (2%) */
            }

            .toggle-label {
                font-weight: 600;
                font-size: 14px;
                color: #333;
                pointer-events: none;
                z-index: 2;
            }

            .toggle-container.active .toggle-label {
                color: #fff;
            }

            /*end slide*/
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
                                                <input type="hidden" id="id"
                                                    value="{{ optional($absen_today)->id }}" />
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
                                                        onclick="openCamera('IN')">
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
                                                        onclick="openCamera('OUT')">
                                                        Clock Out
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="p-3">
                                                <div class="toggle-container" id="toggleBtn">
                                                    <div class="toggle-knob"></div>
                                                    <div class="toggle-label text-white" id="toggleLabel">Slide to start
                                                        break </div>
                                                </div>
                                                {{-- <div class="status-text" id="statusText">Status: Working </div> --}}

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
                                        <th class="bg-th">Detail</th>
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


    <!-- Modal Camera -->
    <div class="modal fade" id="cameraModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraTitle">Capture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <video id="cameraStream" width="100%" autoplay playsinline style="border:1px solid #ccc"></video>
                    <canvas id="cameraCanvas" class="d-none"></canvas>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="captureBtn">Ambil Foto</button>
                    <button class="btn btn-success d-none" id="confirmBtn">Simpan</button>
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
        <div class="modal-dialog modal-sm"> <!-- Tambahkan modal-lg di sini -->
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
        const toggle = document.getElementById("toggleBtn");

        const toggleLabel = document.getElementById("toggleLabel");
        const id = document.getElementById("id");
        const presensiBreakCount = document.getElementById("presensi_break_count");

        let isOn = false; // default

        // 🔹 Fungsi untuk update tampilan berdasarkan status
        function updateToggleUI() {
            toggle.classList.toggle("active", isOn);

            if (isOn) {
                toggleLabel.innerHTML = "On Break";

            } else {
                toggleLabel.innerHTML = "Slide to start break";

            }
        }

        // 🔹 Ambil status awal dari backend
        $.ajax({
            url: "{{ route('presensi-status-break') }}",
            method: "GET",
            success: function(res) {
                console.log("Status awal:", res);
                // Jika API balikin status "onbreak", aktifkan toggle
                isOn = (res.status === "onbreak");
                updateToggleUI();
            },
            error: function(err) {
                console.error("Gagal ambil status awal:", err);
            },
        });

        // 🔹 Saat tombol diklik
        toggle.addEventListener("click", () => {
            if ($("#id").val() === "") {
                alert("Please clock in first to start break.");
                return;
            }

            // Ubah status
            isOn = !isOn;
            updateToggleUI();

            // 🔹 Kirim ke backend untuk simpan status terbaru
            const now = new Date();

            // 1. Tanggal lengkap beserta jam, menit, detik (lokal)
            const tanggalLengkap =
                now.getFullYear() + "-" +
                String(now.getMonth() + 1).padStart(2, "0") + "-" +
                String(now.getDate()).padStart(2, "0") + " " +
                String(now.getHours()).padStart(2, "0") + ":" +
                String(now.getMinutes()).padStart(2, "0") + ":" +
                String(now.getSeconds()).padStart(2, "0");

            $.ajax({
                url: "{{ route('presensi-update-break') }}", // ganti ke route update kamu
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: isOn ? "insert" : "update",
                    id: $("#id").val(),
                    break_time: tanggalLengkap
                },
                success: function(res) {
                    console.log("Status berhasil diubah:", res);
                },
                error: function(err) {
                    alert("Gagal update status: " + err.responseText);
                },
            });

        });
    </script>

    <script>
        // Autosize textarea
        const textarea = document.getElementById('work_description');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto'; // reset height
            this.style.height = this.scrollHeight + 'px'; // sesuaikan dengan konten
        });
    </script>
    <script>
        let cameraStream, currentType;

        function openCamera(type) {
            currentType = type; // IN / OUT
            const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
            modal.show();

            // Start camera
            navigator.mediaDevices.getUserMedia({
                    video: {
                        width: {
                            ideal: 320
                        }, // target lebar 320px
                        height: {
                            ideal: 240
                        }, // target tinggi 240px
                        facingMode: "user" // atau "environment" untuk kamera belakang
                    }
                })
                .then(stream => {
                    cameraStream = stream;
                    document.getElementById('cameraStream').srcObject = stream;
                })
                .catch(err => {
                    alert("Tidak bisa akses kamera: " + err);
                });

            document.getElementById("captureBtn").classList.remove("d-none");
            document.getElementById("confirmBtn").classList.add("d-none");

        }

        document.getElementById("captureBtn").addEventListener("click", function() {
            const video = document.getElementById("cameraStream");
            const canvas = document.getElementById("cameraCanvas");
            const ctx = canvas.getContext("2d"); // <- definisikan dulu

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // ambil frame dari video ke canvas
            ctx.drawImage(video, 0, 0);

            // tampilkan hasil di canvas (preview)
            canvas.classList.remove("d-none");
            video.classList.add("d-none"); // opsional: sembunyikan video

            // ambil data base64
            const dataUrl = canvas.toDataURL("image/png");

            // ubah tombol
            this.classList.add("d-none");
            document.getElementById("confirmBtn").classList.remove("d-none");

            // ❌ jangan stop kamera dulu, biar tetap hidup sampai konfirmasi

            // stop camera stream setelah capture
            // cameraStream.getTracks().forEach(track => track.stop());
        });

        document.getElementById("confirmBtn").addEventListener("click", function() {
            const canvas = document.getElementById("cameraCanvas");
            const dataUrl = canvas.toDataURL("image/png");

            // kirim data + foto ke server
            sendAttendance(currentType, dataUrl);
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('cameraModal'));
            modal.hide();
        });
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


        function sendAttendance(type, imageBase64) {
            let $btn = (type === 'IN') ? $("#btn-in") : $("#btn-out");

            // set loading
            $btn.prop("disabled", true).text("Processing...");

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


                $.ajax({
                    url: "{{ route('presensi-add') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        type: type,
                        latitude: lat,
                        longitude: lon,
                        address: address,
                        check_time: tanggalLengkap,
                        work_description: $("#work_description").val(),
                        image: imageBase64 // base64 string
                    },
                    success: function(res) {
                        swal(res.message, {
                            buttons: {
                                confirm: {
                                    className: "btn btn-success"
                                }
                            }
                        });
                        // kembalikan tombol
                        $btn.prop("disabled", false)
                            .text(type === 'IN' ? "Clock In" : "Clock Out");


                        location.reload();
                    },
                    error: function(err) {
                        alert("Gagal: " + err.responseText);

                        $btn.prop("disabled", false)
                            .text(type === 'IN' ? "Clock In" : "Clock Out");
                    }
                });
            });

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
                url: "{{ route('presensi-detail', ':id') }}".replace(':id',
                    param), // Ganti dengan route yang sesuai
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
                    {
                        data: 'detail',
                        name: 'detail'
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
