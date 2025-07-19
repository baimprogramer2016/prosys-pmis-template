@extends('layouts.app')

@section('content')
    @push('top')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
        <style>
            html,
            body {
                height: 100%;
                margin: 0;
                padding: 0;
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
                min-height: 100px;
                max-width: 500px;
                margin: auto;
                padding: 20px;
                font-size: 14px;
            }

            .dz-message {
                margin: 0;
            }
        </style>
    @endpush

    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pb-2">
            <div class="d-flex align-items-center gap-4">
                <h6 class="op-7 mb-2">Project Procedure</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('sop') }}" class="btn btn-primary btn-round">Daftar</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
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
                                    <h4 class="card-title">Upload Document</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">


                        <div class="row mt-4">
                            <!-- Dropzone di kiri -->
                            <div class="col-md-3">
                                <form action="{{ route('sop-upload-temp') }}" class="dropzone" id="myDropzone">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                            </div>
                            <!-- Form di kanan -->
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label class="form-label strong">Document Number</label>
                                    <textarea class="form-control" id="document_number" name="document_number"></textarea>

                                </div>
                                <div class="mb-3">
                                    <label class="form-label strong">Title</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label strong">Version</label>
                                    <input type="text" class="form-control" id="version" name="version">
                                </div>
                                <div class="mb-3">
                                    <button id="saveUploads" class="btn btn-success w-100">Submit</button>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('bottom')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
        <script>
            var uploadedFiles = [];

            Dropzone.options.myDropzone = {
                paramName: "file",
                maxFilesize: 500,
                maxFiles: 1,
                acceptedFiles: ".pdf,.jpg,.jpeg,.png,.ppt,.doc,.docx,.xls,.xlsx,.pptx,.cad,.dwg",
                addRemoveLinks: true,
                init: function() {
                    this.on("success", function(file, response) {
                        uploadedFiles.push({
                            path: response.path,
                            fileName: file.name
                        });
                    });

                    this.on("removedfile", function(file) {
                        uploadedFiles = uploadedFiles.filter(item => item.fileName !== file.name);
                    });
                }
            };

            document.getElementById('saveUploads').addEventListener('click', function() {
                $(".is-invalid").removeClass("is-invalid");
                let valid = true;

                let document_number = $("#document_number").val().trim();
                let description = $("#description").val().trim();
                let version = $("#version").val().trim();

                if (!document_number) {
                    $("#document_number").addClass("is-invalid");
                    valid = false;
                }
                if (!description) {
                    $("#description").addClass("is-invalid");
                    valid = false;
                }
                if (!version) {
                    $("#version").addClass("is-invalid");
                    valid = false;
                }
                if (uploadedFiles.length === 0) {
                    alert('No valid files uploaded!');
                    valid = false;
                    return;
                }

                if (valid) {
                    $.ajax({
                        url: "{{ route('sop-save-uploads') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            uploaded_files: uploadedFiles,
                            document_number,
                            description,
                            version
                        },
                        success: function(response) {
                            let msg = response.status === 'ok' ? "File Successfully Saved" : "Failed";
                            let color = response.status === 'ok' ? "btn btn-success" : "btn btn-danger";

                            swal(msg, {
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
        </script>
    @endpush
@endsection
