<div class="modal-header">

    <h5 class="modal-title" id="modalTambahLabel">Document Delete</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <form id="formData">
      <div class="mb-3">
        <label for="start_date" class="form-label">Document Number</label>
        <input type="hidden" id="data_id" name="data_id" value="{{ $document->id }}"> <!-- Hidden input for ID -->
        <input type="text" readonly class="form-control" value="{{ $document->document_number }}">
        <input type="hidden" readonly name="tab" id="tab" class="form-control" value="{{ $tab }}">
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-danger" id="deleteData">Delete</button>
  </div>
  
  <script>
  
    // Update data
    $("#deleteData").on("click", function() {
      
      $(".is-invalid").removeClass("is-invalid");
   
      let dataId = $("#data_id").val();
      let tab = $("#tab").val();
  
        let formData = {
          id: dataId,
          tab:tab,
          _token: "{{ csrf_token() }}",
        };
  
        $.ajax({
          url: "{{ route('custom-document-management-deleted', ':id') }}".replace(':id', dataId), // Ganti dengan route yang sesuai
    
          type: "POST",  // HTTP PUT untuk update
          data: formData,
          success: function(response) {
            swal("Data successfully Deleted.", {
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
  