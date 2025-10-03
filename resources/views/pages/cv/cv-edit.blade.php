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
        </style>
    @endpush
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row  pb-2">
            <div class="d-flex align-items-center gap-4">

                <h6 class="op-7 mb-2">CV</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                {{-- <a href="#" class="btn btn-label-info btn-round me-2">Manage</a> --}}
                <a href="{{ route('cv-list') }}" class="btn btn-primary btn-round">List</a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-pen"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0 d-flex">
                                <div class="numbers">
                                    <h4 class="card-title">Edit</h4>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12">
                <div class="card ">
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="alert-warning text-center">CV</div>
                                <form action="{{ route('cv-upload-temp') }}" class="dropzone mt-3" id="myDropzone">

                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="alert-warning text-center">Photo</div>
                                {{-- <img id="preview" src="{{ asset('assets/img/anhari.jpg') }}" alt="Preview"
                                    style="max-height:250px; width: auto; margin-top: 10px;"> --}}
                                <div id="preview"
                                    style="margin-top:10px; height: 250px; border:1px dashed #ccc; display:flex; justify-content:center; align-items:center;">
                                    <span style="color:#aaa;">Preview</span>
                                </div>
                            </div>
                            <div class="col-md-6">


                                <input type="hidden" class="form-control" id="id_edit" name="id_edit"
                                    value="{{ $document->id }}">
                                <div class=" mb-3">
                                    <label for="start_date" class="form-label strong">Name</label>
                                    <input class="form-control" id="name" name="name" type="text"
                                        value="{{ $document->name }}"" />

                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label strong">Education</label>
                                        <input class="form-control" id="degree" name="degree" type="text"
                                            value="{{ $document->degree }}" />

                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label strong">Faculty</label>
                                        <input class="form-control" id="major" name="major" type="text"
                                            value="{{ $document->major }}"" />

                                    </div>
                                </div>
                                <div class=" mb-3">
                                    <label for="start_date" class="form-label strong">University/Institute/Academy</label>
                                    <input class="form-control" id="academy" name="academy" type="text"
                                        value="{{ $document->academy }}"" />

                                </div>
                                <div class=" mb-3">
                                    <label for="start_date" class="form-label strong">Position</label>
                                    <input class="form-control" id="position" name="position" type="text"
                                        value="{{ $document->position }}"" />

                                </div>
                                <div class=" mb-3">
                                    <label for="start_date" class="form-label strong">Upload Foto</label>
                                    <input type='file' class="form-control" id="imageInput" name="imageInput"
                                        accept="image/*/>

                                </div>


                                <div class="col-md-12
                                        mb-3">
                                    <button id="saveUploads" class="btn btn-success mt-3 w-100 ">Update</button>
                                </div>


                            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <script>
        const imageInput = document.getElementById("imageInput");
        const preview = document.getElementById("preview");

        imageInput.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const img = document.createElement("img");
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = "100%";
                img.style.maxHeight = "100%";

                // kosongkan div preview dulu
                preview.innerHTML = "";
                preview.appendChild(img);
            }
        });
    </script>
    <script>
        var uploadedFiles = [];

        Dropzone.options.myDropzone = {
            paramName: "file",
            maxFilesize: 500, // 5MB per file
            maxFiles: 1,
            acceptedFiles: ".pdf,.jpg,.jpeg,.png,.ppt,.doc,.docx,.xls,.xlsx,.pptx,.cad,.dwg",
            addRemoveLinks: true,
            init: function() {
                this.on("success", function(file, response) {

                    let fileName = file.name;
                    // Filter hanya file dengan format "nomor~perihal"

                    uploadedFiles.push({
                        path: response.path,
                        fileName: fileName
                    });

                });

                this.on("removedfile", function(file) {
                    let fileName = file.name;
                    uploadedFiles = uploadedFiles.filter(item => item.fileName !== fileName);
                });
            }
        };

        document.getElementById('saveUploads').addEventListener('click', function() {
            $(".is-invalid").removeClass("is-invalid");
            let valid = true;

            let name = $("#name").val().trim();
            let academy = $("#academy").val();
            let major = $("#major").val();
            let degree = $("#degree").val();
            let position = $("#position").val();
            let id_edit = $("#id_edit").val();
            let imageFile = $("#imageInput")[0].files[0]; // ambil file foto kalau ada

            if (name === "") {
                $("#name").addClass("is-invalid");
                valid = false;
            }
            if (academy === "") {
                $("#academy").addClass("is-invalid");
                valid = false;
            }
            if (major === "") {
                $("#major").addClass("is-invalid");
                valid = false;
            }
            if (degree === "") {
                $("#degree").addClass("is-invalid");
                valid = false;
            }
            if (position === "") {
                $("#position").addClass("is-invalid");
                valid = false;
            }

            if (valid) {
                let formData = new FormData();
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("name", name);
                formData.append("academy", academy);
                formData.append("major", major);
                formData.append("degree", degree);
                formData.append("position", position);

                // kirim data dropzone
                formData.append("uploaded_files", JSON.stringify(uploadedFiles));

                // kirim file image kalau ada
                if (imageFile) {
                    formData.append("imageInput", imageFile);
                }

                $.ajax({
                    url: "{{ route('cv-update', ':id') }}".replace(':id', id_edit),
                    type: "POST",
                    data: formData,
                    processData: false, // wajib utk FormData
                    contentType: false, // wajib utk FormData
                    success: function(response) {
                        let msg_swal, color;
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
                                    className: color
                                }
                            }
                        });
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            }
        });
    </script>
@endpush
