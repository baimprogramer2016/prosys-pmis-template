<div class="modal-header">

    <h5 class="modal-title" id="modalTambahLabel">Delete Data</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <form id="formData">
      <input type="hidden" id="data_id" name="data_id" value="{{ $data_parent->id }}"> <!-- Hidden input for ID -->
      <div class="mb-3">
        <label for="activity" class="form-label">Activity</label>
        <input type="text" readonly class="form-control" id="activity" name="activity" placeholder="Enter activity name" value="{{ $data_parent->text}}">
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-danger" id="deleteData">Delete</button>
  </div>
  
  <script>
  
    // Update data
    $("#deleteData").on("click", function() {
      $(".is-invalid").removeClass("is-invalid");
      let valid = true;
  
      let dataId = $("#data_id").val();
  
      if (valid) {
        let formData = {
          _token: "{{ csrf_token() }}",
        };
      
  
        $.ajax({
          url: "{{ route('master-schedule-destroy', ':id') }}".replace(':id', dataId), // Ganti dengan route yang sesuai
    
          type: "POST",  // HTTP PUT untuk update
          data: formData,
          success: function(response) {
            alert("Data successfully deleted.");
            $("#modal").modal('hide');
            window.location.reload();
          },
          error: function(xhr) {
            alert("An error occurred: " + xhr.responseText);
          }
        });
      }
    });
  </script>
  