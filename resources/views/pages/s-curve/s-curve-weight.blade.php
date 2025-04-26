<div class="modal-header">

  <h5 class="modal-title" id="modalTambahLabel">Update</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    @foreach ($data_weight as $item_weight)
        
    <div class="mb-3">
      <label for="engineering_weight" class="form-label">{{ $item_weight->description }}</label>
      <input type="number"  id="{{ strtolower($item_weight->description)."_weight" }}" class="form-control" value="{{ $item_weight->weight }}">
    </div>
    @endforeach


  
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-success" id="updateData">Update</button>
</div>

<script>

  // Update data
  $("#updateData").on("click", function() {
    
      let engineering = $("#engineering_weight").val();
      let procurement = $("#procurement_weight").val();
      let construction = $("#construction_weight").val();
      let commissioning = $("#commissioning_weight").val();
      
      let formData = {
        engineering: engineering,
        procurement:  procurement,
        construction: construction,
        commissioning: commissioning,
        _token: "{{ csrf_token() }}",
      };
    
      $.ajax({
        url: "{{ route('s-curve-weight-update') }}", // Ganti dengan route yang sesuai
        type: "POST",  // HTTP PUT untuk update
        data: formData,
        success: function(response) {
     
          swal("Data successfully Update.", {
            buttons: {
              confirm: {
                className: "bg-success",
              },
            },
          });
          $("#modal").modal('hide');
        },
        error: function(xhr) {
          alert("An error occurred: " + xhr.responseText);
        }
      });
    
  });
</script>
