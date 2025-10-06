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

            /* DataTables Styling */
            /* Using Bootstrap 5 classes for filter/pagination to fit the design */
            .dataTables_filter {
                margin-bottom: 10px;
            }

            .dt-buttons {
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dataTables_paginate {
                margin-top: 20px;
                margin-bottom: 20px;
            }

            /* Table Header Styling */
            #myTable thead th {
                background-color: #ebf5fb;
                font-size: 10px !important;
                padding: 5px;
                /* Consolidated padding for th */
            }

            #myTable tbody td {
                font-size: 10px !important;
                color: #2d2c2c;
            }

            /* Modal Styling (PDF Viewer) */
            #pdf-viewer-modal .modal-dialog {
                max-width: 90%;
                margin: 1.75rem auto;
            }

            #pdf-viewer-modal .modal-body {
                height: 80vh;
                overflow-y: auto;
                padding: 0;
            }

            /* PDF Canvas (if used for viewing) */
            #pdf-canvas {
                width: 100%;
                display: block;
                margin: 0 auto;
            }

            /* SLIDE/Toggle Styling */
            .toggle-container {
                width: 100%;
                max-width: 100%;
                height: 40px;
                background: #cbd3ceff;
                /* light grey/blue */
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
                /* red when on break */
            }

            .toggle-knob {
                /* Calibrate width and left position based on container width (100%) and margins */
                width: 12%;
                min-width: 40px;
                max-width: 58px;
                height: calc(100% - 4px);
                background: linear-gradient(145deg, #ffffff, #e6e6e6);
                border-radius: 6px;
                /* Reduced from 10px for better fit */
                position: absolute;
                top: 2px;
                left: 2px;
                /* Adjusted from 5px for proportional calculation */
                transition: all 0.3s ease;
                box-shadow:
                    0 3px 6px rgba(0, 0, 0, 0.15),
                    inset 0 2px 2px rgba(255, 255, 255, 0.7);
            }

            .toggle-container.active .toggle-knob {
                background: linear-gradient(145deg, #fefefe, #f1f1f1);
                box-shadow:
                    0 3px 10px rgba(0, 0, 0, 0.25),
                    0 0 8px rgba(255, 255, 255, 0.6);
                /* 100% - width (12%) - margin (2px on each side * 2) -> Let's simplify this with a calc */
                left: calc(100% - 12% - 2px);
            }

            /* Ensure knob moves correctly on smaller screens/different widths */
            @media (max-width: 768px) {
                .toggle-container.active .toggle-knob {
                    /* On smaller screens, the minimum width of 40px might be more significant,
                                                                                                       so let's keep the general rule but test: 100% - knob width - margin */
                    left: calc(100% - 40px - 2px);
                }
            }


            .toggle-knob:hover {
                transform: scale(1.05);
                box-shadow:
                    0 4px 10px rgba(0, 0, 0, 0.25),
                    inset 0 2px 3px rgba(255, 255, 255, 0.8);
            }

            .toggle-label {
                font-weight: 600;
                font-size: 14px;
                color: #333;
                pointer-events: none;
                z-index: 2;
                white-space: nowrap;
                /* Prevent label from wrapping */
            }

            .toggle-container.active .toggle-label {
                color: #fff;
            }

            /* end slide */
            /* Removed attendance-container, header-masuk/pulang, btn-masuk/pulang-solid
                                                                                               as they seem to be obsolete/redundant based on the current HTML structure which uses Bootstrap classes */
        </style>
        {{-- Updated DataTables CDN for better Bootstrap 5 compatibility (using standard CSS here) --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.1/css/buttons.dataTables.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @endpush
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pb-2">
            <div class="d-flex align-items-center gap-4">
                <h6 class="op-7 mb-2">Document Management/ Attendance</h6>
            </div>
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
                                            <h3 class="fw-bold mb-3">Live Attendance</h3>

                                            <div class="mb-3">
                                                {{-- The ID input should be kept for break logic --}}
                                                <input type="hidden" id="id"
                                                    value="{{ optional($absen_today)->id }}" />
                                                <textarea class="form-control" id="work_description" name="work_description" rows="3"
                                                    placeholder="Work Description....">{{ optional($absen_today)->work_description }}</textarea>
                                            </div>

                                            <div class="d-flex justify-content-center gap-3 p-3">
                                                <div class="flex-fill">
                                                    <div class="fw-bold text-success mb-2" style="font-size: 2.5rem;"
                                                        id="check_in_time">
                                                        {{ optional($absen_today)->check_in ? \Carbon\Carbon::parse($absen_today->check_in)->format('H:i') : '00:00' }}
                                                    </div>
                                                    <button class="btn btn-success w-100 py-3 fw-bold" id="btn-in"
                                                        data-bs-toggle="modal" data-bs-target="#cameraModal"
                                                        onclick="openCamera('IN')">
                                                        Clock In
                                                    </button>
                                                </div>

                                                <div class="flex-fill">
                                                    <div class="fw-bold text-danger mb-2" style="font-size: 2.5rem;"
                                                        id="check_out_time">
                                                        {{ optional($absen_today)->check_out ? \Carbon\Carbon::parse($absen_today->check_out)->format('H:i') : '00:00' }}
                                                    </div>
                                                    <button class="btn btn-danger w-100 py-3 fw-bold" id="btn-out"
                                                        data-bs-toggle="modal" data-bs-target="#cameraModal"
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
                                            </div>

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

    {{-- The modal-lg class should be on the modal-dialog for Bootstrap 5 --}}
    <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraTitle">Capture Photo for Attendance</h5>
                    {{-- Use data-bs-dismiss for Bootstrap 5 --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

    {{-- Other Modals --}}
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-pdf" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content" id="content-pdf">
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-large" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection

@push('bottom')
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    {{-- DataTables Bootstrap 5 Integration is typically needed if not using basic DataTables style --}}
    {{-- <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script> --}}

    <script src="https://cdn.datatables.net/buttons/3.2.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.1/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.1/js/buttons.print.min.js"></script>

    {{-- Leaflet is for map/geo visualization, ensure its CSS is included in @push('top') --}}
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    {{-- SweetAlert2 for better alerts/swal calls, assuming swal() is a placeholder for a library like SweetAlert2 or SweetAlert --}}
    {{-- Note: If you are using an older SweetAlert, you may need to update this --}}
    @if (!empty(\Session::get('alert')))
        <script>
            swal('{{ \Session::get('alert') }}', {
                buttons: {
                    confirm: {
                        className: "btn btn-success"
                    }
                }
            });
        </script>
    @endif

    <script>
        /**
         * Break/Toggle Logic
         */
        const toggle = document.getElementById("toggleBtn");
        const toggleKnob = document.querySelector(".toggle-knob");
        const toggleLabel = document.getElementById("toggleLabel");
        const idInput = document.getElementById("id");

        let isOn = false; // default

        function updateToggleUI() {
            // Apply active class to the container
            toggle.classList.toggle("active", isOn);

            // Update label text and knob position using CSS transition
            if (isOn) {
                toggleLabel.innerHTML = "On Break";
            } else {
                toggleLabel.innerHTML = "Slide to start break";
            }
        }

        // Fetch initial break status
        $.ajax({
            url: "{{ route('presensi-status-break') }}",
            method: "GET",
            success: function(res) {
                // console.log("Status awal:", res);
                isOn = (res.status === "onbreak");
                updateToggleUI();
            },
            error: function(err) {
                console.error("Gagal ambil status awal:", err);
            },
        });

        // Toggle click handler
        toggle.addEventListener("click", () => {
            if (idInput.value === "") {
                alert("Please clock in first to start a break.");
                return;
            }

            // Change status
            isOn = !isOn;
            updateToggleUI();

            // Send status to backend
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, "0");
            const day = String(now.getDate()).padStart(2, "0");
            const hours = String(now.getHours()).padStart(2, "0");
            const minutes = String(now.getMinutes()).padStart(2, "0");
            const seconds = String(now.getSeconds()).padStart(2, "0");
            const tanggalLengkap = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

            $.ajax({
                url: "{{ route('presensi-update-break') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: isOn ? "insert" :
                    "update", // 'insert' for starting break, 'update' for ending break
                    id: idInput.value,
                    break_time: tanggalLengkap
                },
                success: function(res) {
                    console.log("Status berhasil diubah:", res);
                    // No need to reload, the state is managed locally and in the database
                    // You might want to update a break timer here if you had one.
                },
                error: function(err) {
                    console.error("Gagal update status:", err);
                    alert("Gagal update status: " + err.responseText);
                    // Revert UI change if API call fails
                    isOn = !isOn;
                    updateToggleUI();
                },
            });
        });
    </script>

    <script>
        /**
         * Textarea Autosize
         */
        const textarea = document.getElementById('work_description');
        // Initial size adjustment
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';

        textarea.addEventListener('input', function() {
            this.style.height = 'auto'; // reset height
            this.style.height = this.scrollHeight + 'px'; // adjust to content
        });
    </script>

    <script>
        /**
         * Camera and Attendance Logic
         */
        let cameraStream, currentType;
        const cameraModal = document.getElementById('cameraModal');
        const video = document.getElementById("cameraStream");
        const canvas = document.getElementById("cameraCanvas");
        const captureBtn = document.getElementById("captureBtn");
        const confirmBtn = document.getElementById("confirmBtn");

        function resetCameraModal() {
            // Reset UI elements to initial state
            video.classList.remove("d-none");
            canvas.classList.add("d-none");
            captureBtn.classList.remove("d-none");
            confirmBtn.classList.add("d-none");
        }

        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
                video.srcObject = null;
            }
            resetCameraModal(); // Reset UI when stopping camera
        }

        // 🚨 IMPORTANT FIX: Stop camera when modal is hidden (closed by user)
        cameraModal.addEventListener('hidden.bs.modal', stopCamera);


        function openCamera(type) {
            currentType = type; // IN / OUT

            // Reset UI for new capture attempt
            resetCameraModal();

            // Start camera
            navigator.mediaDevices.getUserMedia({
                    video: {
                        width: {
                            ideal: 320
                        },
                        height: {
                            ideal: 240
                        },
                        facingMode: "user" // Use "environment" for rear camera on mobile
                    }
                })
                .then(stream => {
                    cameraStream = stream;
                    video.srcObject = stream;
                    // Reset buttons display logic right here just in case
                    captureBtn.classList.remove("d-none");
                    confirmBtn.classList.add("d-none");
                })
                .catch(err => {
                    alert("Tidak bisa akses kamera: " + err + ". Pastikan Anda memberikan izin akses kamera.");
                    // Hide the modal on failure
                    const modal = bootstrap.Modal.getInstance(cameraModal);
                    modal.hide();
                });
        }

        captureBtn.addEventListener("click", function() {
            const ctx = canvas.getContext("2d");

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Capture frame
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Show canvas and hide video
            canvas.classList.remove("d-none");
            video.classList.add("d-none");

            // Get base64 data
            const dataUrl = canvas.toDataURL("image/jpeg", 0.9); // Use jpeg for smaller size

            // Update buttons
            this.classList.add("d-none");
            confirmBtn.classList.remove("d-none");
        });


        confirmBtn.addEventListener("click", function() {
            const canvas = document.getElementById("cameraCanvas");
            const dataUrl = canvas.toDataURL("image/jpeg", 0.9);

            // Hide the modal now, but don't stop the camera until attendance is sent
            const modal = bootstrap.Modal.getInstance(cameraModal);
            modal.hide();

            // Send data + photo to server
            sendAttendance(currentType, dataUrl);
        });

        /**
         * Geocoding function using Nominatim
         */
        async function getAddress(lat, lon) {
            try {
                const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`;
                const response = await fetch(url, {
                    headers: {
                        // Nominatim requires a user agent or referrer
                        "User-Agent": "Attendance-App/1.0"
                    }
                });
                const data = await response.json();
                return data.display_name || `Lat: ${lat}, Lon: ${lon}`;
            } catch (error) {
                console.error("Geocoding failed:", error);
                return `Lat: ${lat}, Lon: ${lon} (Address lookup failed)`;
            }
        }


        function sendAttendance(type, imageBase64) {
            let $btn = (type === 'IN') ? $("#btn-in") : $("#btn-out");

            // set loading
            $btn.prop("disabled", true).text("Processing...");

            // Stop camera immediately after photo is confirmed
            stopCamera();

            navigator.geolocation.getCurrentPosition(async (pos) => {
                const lat = pos.coords.latitude;
                const lon = pos.coords.longitude;
                const address = await getAddress(lat, lon);

                const now = new Date();

                // 1. Full date and time (local)
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, "0");
                const day = String(now.getDate()).padStart(2, "0");
                const hours = String(now.getHours()).padStart(2, "0");
                const minutes = String(now.getMinutes()).padStart(2, "0");
                const seconds = String(now.getSeconds()).padStart(2, "0");
                const tanggalLengkap = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

                // Send to backend
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
                        // Assuming swal is defined (SweetAlert or similar)
                        swal(res.message, {
                            buttons: {
                                confirm: {
                                    className: "btn btn-success"
                                }
                            }
                        });

                        // Re-enable button
                        $btn.prop("disabled", false).text(type === 'IN' ? "Clock In" : "Clock Out");

                        // Reload page to update UI/table data
                        location.reload();
                    },
                    error: function(err) {
                        alert("Gagal: " + err.responseText);

                        // Re-enable button
                        $btn.prop("disabled", false).text(type === 'IN' ? "Clock In" : "Clock Out");
                    }
                });
            }, (error) => {
                // Geolocation failed
                alert("Gagal mengambil lokasi: " + error.message);
                $btn.prop("disabled", false).text(type === 'IN' ? "Clock In" : "Clock Out");
            });

        }
    </script>

    <script>
        /**
         * DataTables Initialization and Modal Functions
         */

        // DataTables setup
        $(document).ready(function() {
            // Check if DataTable is already initialized before initializing
            if ($.fn.DataTable.isDataTable('#myTable')) {
                $('#myTable').DataTable().destroy();
            }

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                pageLength: 30, // Default rows per page
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    sLengthMenu: "Show _MENU_"
                },

                ajax: {
                    url: "{{ route('get-presensi') }}", // Laravel route for DataTables data
                },
                // Use a standard DataTables DOM structure for better layout control
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
                        name: 'detail',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        });

        // The following functions load content into modals via AJAX.
        // It's a common pattern in Laravel/jQuery but ensure the backend routes and views exist.

        function viewDelete(param) {
            $("#modal .modal-content").html("Loading..."); // Target specific modal content
            $.ajax({
                url: "{{ route('mrr-delete', ':id') }}".replace(':id', param),
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#modal .modal-content").html(response);
                    // Manually open modal if not already open
                    const modal = new bootstrap.Modal(document.getElementById('modal'));
                    modal.show();
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                    $("#modal .modal-content").html("Error loading content.");
                }
            });
        }

        function viewShare(param) {
            $("#modal .modal-content").html("Loading...");
            $.ajax({
                url: "{{ route('mrr-share', ':id') }}".replace(':id', param),
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#modal .modal-content").html(response);
                    const modal = new bootstrap.Modal(document.getElementById('modal'));
                    modal.show();
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                    $("#modal .modal-content").html("Error loading content.");
                }
            });
        }


        function viewPdf(param) {
            $("#content-pdf").html("");
            $.ajax({
                url: "{{ route('presensi-detail', ':id') }}".replace(':id',
                    param), // Ganti dengan route yang sesuai
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $("#content-pdf").html("Harap menuggu, data sedang di muat......");
                    setTimeout(() => {
                        $("#content-pdf").html(response);
                    }, 2000);

                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        }


        function viewHistory(param) {
            $("#modal-large .modal-content").html("Loading...");
            $.ajax({
                url: "{{ route('mrr-history', ':id') }}".replace(':id', param),
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#modal-large .modal-content").html(response);
                    const modal = new bootstrap.Modal(document.getElementById('modal-large'));
                    modal.show();
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                    $("#modal-large .modal-content").html("Error loading content.");
                }
            });
        }
    </script>
@endpush
