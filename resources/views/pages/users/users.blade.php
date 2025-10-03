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

            table.dataTable td {
                padding: 0px 0px !important;
                /* Kurangi padding default */
                vertical-align: middle;
                /* Pastikan teks tetap di tengah */
            }

            table.dataTable {
                border: 1px solid #dee2e6;
            }

            table.dataTable th,
            table.dataTable td {
                border: 1px solid #dee2e6;
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
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row  pb-2">
            <div class="d-flex align-items-center gap-4">

                <h6 class="op-7 mb-2">Users </h6>

            </div>

        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    Jika parameter Username sama, maka akan Replace data sebelumnya
                                </div>

                                <div class="form-row d-flex align-items-center row">
                                    <!-- Input Tanggal -->
                                    <div class="form-group col-md-3">
                                        <label for="dateInput">Username</label>
                                        <input type="text" class="form-control form-control-sm" id="username"
                                            name="username">
                                        <input type="hidden" class="form-control form-control-sm" id="model_id"
                                            name="model_id">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="dateInput">Name</label>
                                        <input type="text" class="form-control form-control-sm" id="name"
                                            name="name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="dateInput">Email</label>
                                        <input type="email" class="form-control form-control-sm" id="email"
                                            name="email">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="dateInput">Password</label>
                                        {{-- <input type="hidden" class="form-control form-control-sm" id="password_check" name="password_check"> --}}
                                        <input type="password" class="form-control form-control-sm" id="password"
                                            name="password">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="dateInput">NIK</label>
                                        <input type="text" class="form-control form-control-sm" id="nik"
                                            name="nik">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="dateInput">Job Title</label>
                                        <input type="text" class="form-control form-control-sm" id="position"
                                            name="position">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="dateInput">Departement</label>
                                        <input type="text" class="form-control form-control-sm" id="departement"
                                            name="departement">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="role">Role</label>
                                        <select class="form-control form-control-sm" id="role" name="role">
                                            <option value="">Pilih Role</option>
                                            @foreach ($data_role as $item_role)
                                                <option value="{{ $item_role->id }}">{{ $item_role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row">
                                        <!-- Select Role -->

                                        <!-- Tombol -->
                                        <div class="col-md-3 d-flex align-items-end mb-2 justify-content-start">
                                            <button type="submit" id="save" name="save"
                                                class="btn btn-sm btn-primary">Update & Insert</button>
                                        </div>
                                    </div>


                                    <!-- Tombol Submit -->

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
                                            <th class="bg-th">Name</th>
                                            <th class="bg-th">Username</th>
                                            <th class="bg-th">Email</th>
                                            <th class="bg-th">Nik</th>
                                            <th class="bg-th">Job Title</th>
                                            <th class="bg-th">Departement</th>
                                            <th class="bg-th">Role</th>
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

        <script>
            document.getElementById('save').addEventListener('click', function() {
                console.log($("#nik").val());
                console.log($("#position").val());
                console.log($("#departement").val());
                // Reset error messages
                $(".is-invalid").removeClass("is-invalid");
                let valid = true;
                let name = $("#name").val();
                let username = $("#username").val()
                let email = $("#email").val()
                let nik = $("#nik").val()
                let position = $("#position").val()
                let departement = $("#departement").val()
                let password = $("#password").val()
                let password_check = $("#password_check").val()
                let role = $("#role").val()
                let model_id = $("#model_id").val()
                // Validasi Activity



                // Validasi End Date
                if (name === "") {
                    $("#name").addClass("is-invalid");
                    valid = false;
                }
                // Validasi End Date
                if (username === "") {
                    $("#username").addClass("is-invalid");
                    valid = false;
                }
                if (email === "") {
                    $("#email").addClass("is-invalid");
                    valid = false;
                }
                if (password === "") {
                    $("#password").addClass("is-invalid");
                    valid = false;
                }
                if (role === "") {
                    $("#role").addClass("is-invalid");
                    valid = false;
                }

                if (valid == true) {

                    $.ajax({
                        url: "{{ route('user-save') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: name,
                            username: username,
                            email: email,
                            password: password,
                            nik: nik,
                            position: position,
                            departement: departement,
                            model_id: model_id,
                            role: role,
                        },
                        success: function(response, color) {
                            console.log(response, 'xxxx')
                            if (response.status == 'ok') {
                                msg_swal = "File Successfully Saved";
                                color = "btn btn-success";
                            } else {
                                msg_swal = "Failed";
                                color = "btn btn-danger";
                            }
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

            function viewDelete(param) {
                $(".modal-content").html("");
                $.ajax({
                    url: "{{ route('user-delete', ':id') }}".replace(':id', param), // Ganti dengan route yang sesuai
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

            function viewEdit(param) {
                console.log(param)
                $("#name").val(param["name"])
                $("#model_id").val(param["id"])
                $("#username").val(param["username"])
                $("#nik").val(param["nik"])
                $("#position").val(param["position"])
                $("#departement").val(param["departement"])
                $("#email").val(param["email"])
                $("#password").val(param["password"])
                $("#role").val(param["role_id"])
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
                        url: "{{ route('get-user') }}",
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
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'username',
                            name: 'username'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'nik',
                            name: 'nik'
                        },
                        {
                            data: 'position',
                            name: 'position'
                        },
                        {
                            data: 'departement',
                            name: 'departement'
                        },
                        {
                            data: 'role',
                            name: 'role'
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
