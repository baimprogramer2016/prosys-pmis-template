@extends('layouts.app')

@section('content')
    @push('top')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
        <style type="text/css">
            html,
            body {
                height: 100%;
                padding: 0px;
                margin: 0px;

            }

            .alert-warning {
                background-color: #fff3cd;
                color: #856404;
                border: 1px solid #ffeeba;
                font-size: 14px;
                padding: 8px;
                border-radius: 4px;
            }

            .dropzone {
                border: 2px dashed #d2d6de;
                background: #f9f9f9;
                min-height: 10px;

                text-align: center;
            }

            <style type="text/css">html,
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
            }

            #myTable tbody td {
                font-size: 12px !important;
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
        <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.1/css/buttons.dataTables.css" />
    @endpush
    <div class="page-inner">
        <div class="row">
            @can('add_cv_group')
                <div class="col-sm-12 col-md-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description" class="form-label strong">Description</label>
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>
                                    <!-- Input autocomplete -->
                                    <div class="mb-3 position-relative">
                                        <label for="searchName" class="form-label strong">Cari Nama</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" id="searchName"
                                                class="form-control form-control-sm border-gray" placeholder="Ketik nama...">
                                            <button id="addName" class="btn btn-primary btn-sm" type="button">Tambah</button>
                                        </div>

                                        <!-- dropdown suggestion -->
                                        <ul id="suggestions" class="list-group position-absolute w-100" style="z-index: 1000;">
                                        </ul>
                                    </div>
                                    <div class="input-group input-group-md">
                                        <label for="reviewer_id" class="form-label strong">Kirim Ke :</label>
                                        <select class="form-control form-control-md reviewer_id" name="reviewer_id"
                                            id="reviewer_id" style="width: 100%;">

                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button id="saveUploads" class="btn btn-success mt-3 w-100">Submit</button>
                                    </div>
                                </div>

                                <!-- daftar nama -->
                                <div class="col-md-6">
                                    <h5>Daftar Nama Terpilih</h5>
                                    <ul id="nameList" class="list-group"></ul>

                                    <!-- penampung ID untuk dikirim ke backend -->
                                    <input type="hidden" name="selected_ids" id="selectedIds">
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            @endcan
            <div class="col-sm-12 col-md-12">
                <div class="card ">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="bg-th">Description</th>
                                        <th class="bg-th">Reviewer</th>
                                        <th class="bg-th">Status</th>
                                        <th class="bg-th">Date</th>
                                        <th class="bg-th">Action</th>
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
@endsection

@push('bottom')
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function viewDelete(param) {
            $(".modal-content").html("");
            $.ajax({
                url: "{{ route('cv-pengajuan-delete', ':id') }}".replace(':id',
                    param), // Ganti dengan route yang sesuai
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

        $(document).ready(function() {

            $.ajax({
                url: "{{ route('cv-search-reviewer') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",

                },
                success: function(response) {

                    if (response.status === 'ok') {
                        const reviewers = response.data; // data dari backend

                        // tampilkan suggestion
                        reviewers.forEach(n => {
                            const option = document.createElement("option");
                            option.value = n.id;
                            option.textContent = n.name;
                            $('.reviewer_id').append(option);

                        });
                    }
                },
                error: function(xhr) {
                    // console.error("Error search:", xhr.responseText);
                }
            });

            $('.reviewer_id').select2({
                placeholder: "Pilih nama...",
                allowClear: true
            });
        });

        const searchInput = document.getElementById("searchName");
        const suggestions = document.getElementById("suggestions");
        const nameList = document.getElementById("nameList");
        const hiddenInput = document.getElementById("selectedIds");

        let selectedIds = [];

        // 🔹 setiap keyup, ambil dari backend
        searchInput.addEventListener("keyup", function() {

            const query = this.value;
            suggestions.innerHTML = "";

            if (query.length > 0) {
                $.ajax({
                    url: "{{ route('cv-search') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        search: query, // kirim query ke backend
                    },
                    success: function(response) {

                        if (response.status === 'ok') {

                            const names = response.data; // data dari backend
                            suggestions.innerHTML = "";
                            // tampilkan suggestion
                            names.forEach(n => {
                                const li = document.createElement("li");
                                li.classList.add("list-group-item", "list-group-item-action");
                                li.textContent = n.name;
                                li.onclick = () => {
                                    searchInput.value = n.name;
                                    searchInput.dataset.id = n.id;
                                    suggestions.innerHTML = "";
                                };
                                suggestions.appendChild(li);
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error("Error search:", xhr.responseText);
                    }
                });
            }
        });

        // tambah ke daftar
        document.getElementById("addName").addEventListener("click", function() {
            const val = searchInput.value;
            const id = searchInput.dataset.id;

            if (val && id && !selectedIds.includes(id)) {
                selectedIds.push(id);

                const li = document.createElement("li");
                li.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center");
                li.textContent = val;

                // tombol hapus
                const btn = document.createElement("button");
                btn.classList.add("btn", "btn-sm", "btn-danger");
                btn.textContent = "Hapus";
                btn.onclick = () => {
                    li.remove();
                    selectedIds = selectedIds.filter(x => x !== id);
                    hiddenInput.value = selectedIds.join(",");
                };

                li.appendChild(btn);
                nameList.appendChild(li);

                // update hidden input
                hiddenInput.value = selectedIds.join(",");

                // reset input
                searchInput.value = "";
                searchInput.dataset.id = "";
                suggestions.innerHTML = "";


            }
        });

        document.getElementById('saveUploads').addEventListener('click', function() {

            // Reset error messages
            $(".is-invalid").removeClass("is-invalid");
            let valid = true;

            let selectedIds = $("#selectedIds").val();
            let description = $("#description").val();

            // Validasi Activity
            if (selectedIds === "") {
                $("#selectedIds").addClass("is-invalid");
                valid = false;
                alert("Belum Memilih CV");
                return
            }

            // Validasi Start Date
            if (description === "") {
                $("#description").addClass("is-invalid");
                valid = false;
                alert("Belum isi Deskripsi");
                return
            }

            if (valid == true) {

                $.ajax({
                    url: "{{ route('cv-pengajuan-save') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        selected_ids: selectedIds,
                        description: description,
                        reviewer_id: $('.reviewer_id').val()

                    },
                    success: function(response, color) {
                        if (response.status == 'ok') {
                            msg_swal = "File Successfully Saved";
                            color = "btn btn-success";

                            $("#description").val("");
                            $("#nameList").html("");
                            $("#selectedIds").val("");
                        } else {
                            msg_swal = "Failed";
                            color = "btn btn-danger";
                        }
                        name
                        swal(msg_swal, {
                            buttons: {
                                confirm: {
                                    className: color,
                                },
                            },
                        });

                        location.reload();
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            }

        });

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
                    url: "{{ route('get-cv-pengajuan') }}",
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
                        data: 'description',
                        name: 'description'
                    }, {
                        data: 'reviewer_name',
                        name: 'reviewer_name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            if (!data) return ""; // Jika data kosong, return string kosong
                            const date = new Date(data);
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2,
                                '0'); // Januari = 0
                            const year = date.getFullYear();
                            return `${year}-${month}-${day}`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],

            });
        });
    </script>
@endpush
