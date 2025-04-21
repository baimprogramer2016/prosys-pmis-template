<div class="modal-header">

  <h5 class="modal-title" id="modalTambahLabel">Update</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
  
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <input type="text" disabled  id="description_edit" class="form-control" value="{{ $description }}">
    </div>
    <div class="mb-3">
      <label for="tanggal" class="form-label">Tanggal</label>
      <input type="text"  disabled id="tanggal_edit" class="form-control" value="{{ $tanggal }}">
    </div>
    <div class="mb-3">
      <label for="category" class="form-label">Category</label>
      <input type="text" disabled  id="category_edit" class="form-control" value="{{ $category }}">
    </div>
    <div class="mb-3">
      <label for="percent" class="form-label">Percent</label>
      <input type="text" id="percent_edit"  class="form-control" value="{{ $percent }}">
    </div>
  
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-success" id="updateData">Update</button>
</div>

<script>

  // Update data
  $("#updateData").on("click", function() {
    console.log($("#percent").val() +"HELO")

      let description_edit = $("#description_edit").val();
      let tanggal_edit = $("#tanggal_edit").val();
      let category_edit = $("#category_edit").val();
      let percent_edit = $("#percent_edit").val();
      let formData = {
        description: description_edit,
        tanggal:  tanggal_edit,
        category: category_edit,
        percent: percent_edit,
        _token: "{{ csrf_token() }}",
      };
      console.log(formData)
      $.ajax({
        url: "{{ route('s-curve-edit-update') }}", // Ganti dengan route yang sesuai
        type: "POST",  // HTTP PUT untuk update
        data: formData,
        success: function(response) {
          console.log(response)
          swal("Data successfully Update.", {
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
