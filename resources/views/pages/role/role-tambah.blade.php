<div class="modal-header">

    <h5 class="modal-title" id="modalTambahLabel">Tambah Role</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <form id="formData">
      <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <input type="text"  class="form-control" name="role" id='role'>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-success" id="tambahData">Tambah</button>
  </div>
  
  <script>
  
    // Update data
    $("#tambahData").on("click", function() {
      $(".is-invalid").removeClass("is-invalid");
   
      let valid = true;

      let role = $("#role").val()

      // Validasi Activity
      if (role === "") {
        $("#role").addClass("is-invalid");
        valid = false;
      }
     
      if(valid)
      {
        let formData = {
          role: role,
          _token: "{{ csrf_token() }}",
        };
  
        $.ajax({
          url: "{{ route('role-save') }}",
          type: "POST",  // HTTP PUT untuk update
          data: formData,
          success: function(response) {
            swal("Data successfully Saved", {
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
      }
    });
  </script>
  