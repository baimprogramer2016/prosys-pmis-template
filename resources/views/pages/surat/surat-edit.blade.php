  <div class="modal-header">

    <h5 class="modal-title" id="modalTambahLabel">Update Status</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <form id="formData">
      <div class="mb-3">
        <label for="start_date" class="form-label">Nomor</label>
        <input type="hidden" id="data_id" name="data_id" value="{{ $data_surat->id }}"> <!-- Hidden input for ID -->
        <input type="text" readonly class="form-control" value="{{ $data_surat->nomor }}">
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-success" id="updateData">Update Status</button>
  </div>
  
  <script>
  
    // Update data
    $("#updateData").on("click", function() {
      $(".is-invalid").removeClass("is-invalid");
   
      let dataId = $("#data_id").val();
  
        let formData = {
          id: dataId,
          _token: "{{ csrf_token() }}",
        };
  
        $.ajax({
          url: "{{ route('surat-update', ':id') }}".replace(':id', dataId), // Ganti dengan route yang sesuai
    
          type: "POST",  // HTTP PUT untuk update
          data: formData,
          success: function(response) {
            swal("Data successfully updated.", {
              buttons: {
                confirm: {
                  className: "bg-success",
                },
              },
            });
            $("#modal").modal('hide');
            setTimeout(function() {
                window.location.reload();
            }, 1000);
          },
          error: function(xhr) {
            alert("An error occurred: " + xhr.responseText);
          }
        });
      
    });
  </script>
  