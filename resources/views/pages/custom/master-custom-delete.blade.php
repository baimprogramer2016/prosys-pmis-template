<div class="modal-header">

    <h5 class="modal-title" id="modalTambahLabel">Document Delete</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      Data yang telah di input akan hilang 
    </div>
    <form id="formData">
      <div class="mb-3">
        <label for="start_date" class="form-label">Document Number</label>
        <input type="hidden" id="data_id" name="data_id" value="{{ $document->id }}"> <!-- Hidden input for ID -->
        <input type="text" readonly class="form-control" value="{{ $document->name}}">
        <input type="hidden" name="tab_delete"  id="tab_delete"  class="form-control" value="custom_{{ $document->tab}}">
        <input type="hidden" name="tab_history" id="tab_history"  class="form-control" value="custom_{{ $document->tab}}_history">
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
      
      let tab = "";
      let tab_history = "";
      let dataId = $("#data_id").val();
      if( $("#tab_delete").val().replace("custom_") != "undefined"){
        tab =  $("#tab_delete").val();
        tab_history = $("#tab_history").val();
      }
  
        let formData = {
          id: dataId,
          tab: tab,
          tab_history: tab_history,
          _token: "{{ csrf_token() }}",
        };
  
        $.ajax({
          url: "{{ route('master-custom-deleted', ':id') }}".replace(':id', dataId), // Ganti dengan route yang sesuai
          type: "POST",  // HTTP PUT untuk update
          data: formData,
          success: function(response) {
     
            if(response.action == "ok"){
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
            }else{
              swal(response.message, {
                  buttons: {
                    confirm: {
                      className: "bg-danger",
                    },
                  },
                });
            }
          
          },
          error: function(xhr) {
            alert("An error occurred: " + xhr.responseText);
          }
        });
      
    });
  </script>
  