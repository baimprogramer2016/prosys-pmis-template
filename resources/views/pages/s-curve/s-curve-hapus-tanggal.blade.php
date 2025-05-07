<div class="modal-header">

    <h5 class="modal-title" id="modalTambahLabel">Hapus</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <div class="mb-3">
        <label for="hapus_tanggal_description" class="form-label">Description</label>
        <input type="text" readonly id="hapus_tanggal_description" class="form-control" value="{{ $description }}">
    </div>

    <div class="mb-3">
        <label for="hapus_tanggal_lama" class="form-label">Tanggal</label>
        <input type="date" readonly id="hapus_tanggal_lama" class="form-control" value="{{ $tanggal }}">
    </div>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger" id="hapusTanggal">Hapus</button>
</div>

<script>
    // Update data
    $("#hapusTanggal").on("click", function() {

        let hapus_tanggal_description = $("#hapus_tanggal_description").val();
        let hapus_tanggal_lama = $("#hapus_tanggal_lama").val();


        let formData = {
            description: hapus_tanggal_description,
            tanggal: hapus_tanggal_lama,
            _token: "{{ csrf_token() }}",
        };


        $.ajax({
            url: "{{ route('s-curve-hapus-tanggal-delete') }}", // Ganti dengan route yang sesuai
            type: "POST", // HTTP PUT untuk update
            data: formData,
            success: function(response) {

                if (response.status !== "ok") {
                    alert(response.status)

                } else {
                    swal("Data successfully deleted.", {
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
