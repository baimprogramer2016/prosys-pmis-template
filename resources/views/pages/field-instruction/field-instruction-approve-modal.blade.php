<div class="modal-header">
  <h5 class="modal-title" id="modalTambahLabel">Document Approve</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
  <form id="formData">
    <div class="mb-3">
      <label for="start_date" class="form-label">Document Number</label>
      <input type="hidden" id="data_id" name="data_id" value="{{ $document->id }}"> <!-- Hidden input for ID -->
      <input type="text" readonly class="form-control" value="{{ $document->document_number }}">
    </div>
    
    <!-- Radio Button Section -->
    <div class="mb-3 p-3" style="background-color: #c1d0f5; border-radius: 8px;">
      <label class="form-label">Approval Status</label>
      <div class="d-flex align-items-center">
        <div class="form-check me-3">
          <input class="form-check-input" type="radio" name="approval_status" id="approve" value="approve">
          <label class="form-check-label" for="approve">Approve</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="approval_status" id="notApprove" value="notapprove">
          <label class="form-check-label" for="notapprove">Not Approve</label>
        </div>
      </div>
    </div>
  </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-success" id="reviewData">Update</button>
</div>


  <script>
  
    // Update data
    $("#reviewData").on("click", function() {
      $(".is-invalid").removeClass("is-invalid");
   
      let dataId = $("#data_id").val();
      let approvalStatus = $("input[name='approval_status']:checked").val();
  
        let formData = {
          id: dataId,
          approval_status : approvalStatus,
          _token: "{{ csrf_token() }}",
        };
        $.ajax({
          url: "{{ route('field-instruction-approve-update', ':id') }}".replace(':id', dataId), // Ganti dengan route yang sesuai
    
          type: "POST",  // HTTP PUT untuk update
          data: formData,
          success: function(response) {
            swal("Data successfully Updated.", {
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
  