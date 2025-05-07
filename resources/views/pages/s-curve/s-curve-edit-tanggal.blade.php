<div class="modal-header">

    <h5 class="modal-title" id="modalTambahLabel">Update Tanggal</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <div class="mb-3">
        <label for="edit_tanggal_description" class="form-label">Description</label>
        <input type="text" readonly id="edit_tanggal_description" class="form-control" value="{{ $description }}">
    </div>

    <div class="mb-3">
        <label for="edit_tanggal_lama" class="form-label">Tanggal</label>
        <input type="date" readonly id="edit_tanggal_lama" class="form-control" value="{{ $tanggal }}">
    </div>
    <div class="mb-3">
        <label for="edit_tanggal_baru" class="form-label">Tanggal Baru</label>
        <input type="date" id="edit_tanggal_baru" class="form-control" required>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-success" id="updateTanggal">Update</button>
</div>

<script>
    // Update data
    $("#updateTanggal").on("click", function() {

        let edit_tanggal_description = $("#edit_tanggal_description").val();
        let edit_tanggal_lama = $("#edit_tanggal_lama").val();
        let edit_tanggal_baru = $("#edit_tanggal_baru").val();

        if (edit_tanggal_baru == "") {
            $("#edit_tanggal_baru").addClass("is-invalid");
            alert("Tanggal Baru Tidak Boleh Kosong")
            return false;
        }

        let formData = {
            description: edit_tanggal_description,
            tanggal_lama: edit_tanggal_lama,
            tanggal_baru: edit_tanggal_baru,
            _token: "{{ csrf_token() }}",
        };


        $.ajax({
            url: "{{ route('s-curve-edit-tanggal-update') }}", // Ganti dengan route yang sesuai
            type: "POST", // HTTP PUT untuk update
            data: formData,
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
