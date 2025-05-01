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

                <h6 class="op-7 mb-2">Schedule Management / Input S-Curve</h6>

            </div>

            @can('add_weight')
                <div class="ms-md-auto py-2 py-md-0">
                    <span onclick="viewWeight()" class="btn btn-primary btn-round" data-bs-toggle="modal"
                        data-bs-target="#modal" class="text-primary text-center" style="cursor: pointer;">Weight</span>
                </div>
            @endcan
        </div>
        <div class="row">
            @can('add_input_s_curve')
                <div class="col-sm-12 col-md-12">

                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        *Jika parameter Kategori dan tanggal sama, maka akan Replace data sebelumnya <br>
                                        *Jika ingin merubah tanggal, lebih baik di hapus dan input ulang
                                    </div>

                                    <div class="form-row d-flex align-items-center">

                                        <!-- Dropdown -->

                                        <div class="form-group col-md-3">
                                            <label for="optionSelect">Pilih Category</label>
                                            <select class="form-control form-control-sm" id="description" name="description">
                                                <option value="">Pilih Category</option>
                                                @foreach ($data_category as $item_category)
                                                    <option value="{{ $item_category->description }}">
                                                        {{ $item_category->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="optionSelect">Pilih Sub Category</label>
                                            <select class="form-control form-control-sm" id="category" name="category">
                                                <option value="">Pilih Sub Category</option>
                                                @foreach ($data_sub_category as $item_sub_category)
                                                    <option value="{{ $item_sub_category->description }}">
                                                        {{ $item_sub_category->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Input Tanggal -->
                                        <div class="form-group col-md-3">
                                            <label for="dateInput">Tanggal</label>
                                            <input type="date" class="form-control form-control-sm" id="tanggal"
                                                name="tanggal">
                                        </div>

                                        <!-- Input Persentase -->
                                        <div class="form-group col-md-3">
                                            <label for="percentInput">Persentase (%)</label>
                                            <input type="number" class="form-control form-control-sm" id="percent"
                                                name="percent" min="0" max="100" step="0.01" placeholder="0-100">
                                        </div>

                                    </div>


                                    <button type="submit" id="save" name="save"
                                        class="btn btn-sm btn-primary ms-2">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan


            <div class="col-sm-12 col-md-12">
                <div class="card ">

                    <div class="card-body">
                        <form action="{{ route('s-curve') }}" method="GET">
                            <div class="form-row d-flex align-items-end">

                                <!-- Dropdown -->

                                <div class="form-group col-md-3">
                                    <label for="optionSelect">Pilih Week</label>
                                    <select class="form-control form-control-sm" id="filter_week" name="filter_week">
                                        <option>Pilih Week</option>
                                        @foreach ($data_week as $item_week)
                                            <option value="{{ $item_week['week'] }}"
                                                {{ request('filter_week') == $item_week['week'] ? 'selected' : '' }}>
                                                Week {{ $item_week['week'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <button type="submit" class="btn btn-sm btn-success ms-2 h-2">Filter</button>
                                </div>

                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="bg-th">Week</th>
                                        <th class="bg-th">Description</th>
                                        <th class="bg-th">Tanggal</th>
                                        <th class="bg-th">Engineering</th>
                                        <th class="bg-th">Procurument</th>
                                        <th class="bg-th">Construction</th>
                                        <th class="bg-th">Commissioning</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_scurve as $index => $item_curve)
                                        @if (
                                            $item_curve['engineering'] != '' ||
                                                $item_curve['procurement'] != '' ||
                                                $item_curve['construction'] != '' ||
                                                $item_curve['commissioning'] != '')
                                            <tr>

                                                <td>Week {{ $item_curve['week'] }}</td>
                                                <td>{{ $item_curve['description'] }}</td>
                                                <td>{{ $item_curve['tanggal'] }}</td>
                                                <td><span data-bs-toggle="modal" data-bs-target="#modal"
                                                        class="text-primary text-center" style="cursor: pointer;"
                                                        onClick="viewEdit('Engineering','{{ $item_curve['tanggal'] }}','{{ $item_curve['description'] }}','{{ $item_curve['engineering'] }}')">{{ $item_curve['engineering'] }}
                                                        <i class="fas fa-pen"></i></span></td>
                                                <td><span data-bs-toggle="modal" data-bs-target="#modal"
                                                        class="text-primary" style="cursor: pointer;"
                                                        onClick="viewEdit('Procurement','{{ $item_curve['tanggal'] }}','{{ $item_curve['description'] }}','{{ $item_curve['procurement'] }}')">{{ $item_curve['procurement'] }}
                                                        <i class="fas fa-pen"></i></span></td>
                                                <td><span data-bs-toggle="modal" data-bs-target="#modal"
                                                        class="text-primary" style="cursor: pointer;"
                                                        onClick="viewEdit('Construction','{{ $item_curve['tanggal'] }}','{{ $item_curve['description'] }}','{{ $item_curve['construction'] }}')">{{ $item_curve['construction'] }}
                                                        <i class="fas fa-pen"></i></span></td>
                                                <td><span data-bs-toggle="modal" data-bs-target="#modal"
                                                        class="text-primary" style="cursor: pointer;"
                                                        onClick="viewEdit('Commissioning','{{ $item_curve['tanggal'] }}','{{ $item_curve['description'] }}','{{ $item_curve['commissioning'] }}')">{{ $item_curve['commissioning'] }}
                                                        <i class="fas fa-pen"></i></span></td>
                                            </tr>
                                        @endif
                                    @endforeach
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
        document.addEventListener("DOMContentLoaded", function() {

            let saveButton = document.getElementById("save");
            if (saveButton) {
                saveButton.addEventListener('click', function() {

                    // Reset error messages
                    $(".is-invalid").removeClass("is-invalid");
                    let valid = true;
                    let description = $("#description").val();
                    let tanggal = $("#tanggal").val()
                    let percent = $("#percent").val()
                    let category = $("#category").val()
                    // Validasi Activity

                    // Validasi Start Date
                    if (description === "") {
                        $("#description").addClass("is-invalid");
                        valid = false;
                    }


                    // Validasi End Date
                    if (tanggal === "") {
                        $("#tanggal").addClass("is-invalid");
                        valid = false;
                    }
                    const inputDate = new Date(tanggal);
                    const minYear = 2023;
                    if (inputDate.getFullYear() < minYear) {
                        alert(`Cek Tanggal, apakah sudah benar`);
                        $("#tanggal").addClass("is-invalid");
                        valid = false;
                    }
                    // Validasi End Date
                    if (percent === "") {
                        $("#percent").addClass("is-invalid");
                        valid = false;
                    }
                    if (category === "") {
                        $("#category").addClass("is-invalid");
                        valid = false;
                    }

                    if (valid == true) {

                        $.ajax({
                            url: "{{ route('s-curve-save') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                description: description,
                                percent: percent,
                                tanggal: tanggal,
                                category: category,
                            },
                            success: function(response, color) {
                                if (response.status == 'ok') {
                                    msg_swal = "File Successfully Saved";
                                    color = "btn btn-success";
                                } else {
                                    msg_swal = "Failed";
                                    color = "btn btn-success";
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
            }
        });

        function viewDelete(param) {
            $(".modal-content").html("");
            $.ajax({
                url: "{{ route('s-curve-delete', ':id') }}".replace(':id',
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

        function viewEdit(param_category, param_tanggal, param_description, param_value) {
            $(".modal-content").html("");
            $.ajax({
                url: "{{ route('s-curve-edit-value') }}", // Ganti dengan route yang sesuai
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    category: param_category,
                    tanggal: param_tanggal,
                    description: param_description,
                    percent: param_value,
                },
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

        function viewWeight() {
            $(".modal-content").html("");
            $.ajax({
                url: "{{ route('s-curve-weight') }}", // Ganti dengan route yang sesuai
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    engineering: $("#engineering_input").val(),
                    procurement: $("#procurement_input").val(),
                    construction: $("#construction_input").val(),
                    commissioning: $("#commissioning_input").val(),
                },
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

        // function viewEdit(param){
        //   $("#description").val(param["description"])
        //   $("#tanggal").val(param["tanggal"])
        //   $("#percent").val(param["percent"])
        //   $("#category").val(param["category"])
        // }
        //  $(document).ready(function() {
        //   var table = $('.table').DataTable({
        //           processing: true,
        //           serverSide: true,
        //           stateSave: true,
        //           pageLength: 30,  // Ini mengatur default jumlah data per halaman
        //           lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ], 
        //           language : {
        //                 sLengthMenu: "Show _MENU_"
        //             },

        //           ajax: {
        //             //mdr tidak ada kondisi
        //             url : "{{ route('get-s-curve') }}",
        //           },
        //           dom: '<"d-flex flex-column"<"mb-2"B><"d-flex justify-content-between"lf>>rtip',
        //           buttons: [
        //             { extend: 'excelHtml5', text: 'Export Excel', className: 'btn btn-success btn-sm' },
        //             { extend: 'pdfHtml5', text: 'Export PDF', className: 'btn btn-danger btn-sm' },
        //             { extend: 'print', text: 'Print', className: 'btn btn-primary btn-sm' }
        //           ],
        //           columns: [
        //               { data: 'description', name: 'description' },
        //               { data: 'category', name: 'category' },
        //               { data: 'tanggal', name: 'tanggal',render: function(data, type, row) {
        //                 if (!data) return ""; // Jika data kosong, return string kosong
        //                 const date = new Date(data);
        //                 const day = String(date.getDate()).padStart(2, '0');
        //                 const month = String(date.getMonth() + 1).padStart(2, '0'); // Januari = 0
        //                 const year = date.getFullYear();
        //                 return `${year}-${month}-${day}`;
        //             }  },
        //               { data: 'percent', name: 'percent' },
        //               { data: 'action', name: 'action', orderable: false, searchable: false } ,
        //              ],

        //       });
        //     });
    </script>
@endpush
