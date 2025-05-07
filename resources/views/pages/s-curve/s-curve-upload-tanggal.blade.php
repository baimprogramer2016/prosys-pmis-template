<div class="modal-header">

    <h5 class="modal-title" id="modalTambahLabel">Upload</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <div class="mb-3">
        <label for="upload_description" class="form-label">Description</label>
        <input type="text" readonly id="upload_description" class="form-control" value="{{ $description }}">
    </div>

    <div class="mb-3">
        <label for="upload_tanggal" class="form-label">Tanggal</label>
        <input type="date" readonly id="upload_tanggal" class="form-control" value="{{ $tanggal }}">
    </div>
    <div class="mb-3">
        <label for="upload_file" class="form-label">File</label>
        <input type="file" id="upload_file" class="form-control" required>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-success" id="uploadTanggal">Upload</button>
</div>

<script>
    // Update data
    $("#uploadTanggal").on("click", function() {


        let upload_description = $("#upload_description").val();
        let upload_tanggal = $("#upload_tanggal").val();
        let upload_file = $("#upload_file")[0].files[0];

        if (!upload_file) {
            $("#upload_file").addClass("is-invalid");
            alert("File Tidak Boleh Kosong");
            return;
        }

        $("#uploadTanggal").html('Proses Upload...');
        let formData = new FormData();
        formData.append('description', upload_description);
        formData.append('tanggal', upload_tanggal);
        formData.append('file', upload_file);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ route('s-curve-upload-tanggal-upload') }}", // Ganti dengan route yang sesuai
            type: "POST", // HTTP PUT untuk update
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {

                if (response.status !== "ok") {
                    alert(response.status)

                } else {
                    swal("Data successfully Update.", {
                        buttons: {
                            confirm: {
                                className: "bg-success",
                            },
                        },
                    });
                    $("#uploadTanggal").html('Upload');
                    $("#modal").modal('hide');
                    location.reload();
                }

            },
            error: function(xhr) {
                alert("An error occurred: " + xhr.responseText);
            }
        });

    });
</script>
