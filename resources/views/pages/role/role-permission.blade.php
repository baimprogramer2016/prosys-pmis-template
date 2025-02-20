<div class="modal-header">

  <h5 class="modal-title" id="modalTambahLabel">Role Permission</h5>

  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
  <form id="formData">
    <div class="mb-3">
      <label for="role" class="form-label">Role</label>
      <input type="hidden" id="data_id" name="data_id" value="{{ $data_role->id }}"> <!-- Hidden input for ID -->
      <input type="text" readonly name="role" id="role"  class="form-control" value="{{ $data_role->name}}">
      <div class="row scroll-container">
        @foreach ($data_permission as $item_permission)
        <div class="col-md-12">
          <div class="form-check me-3 d-flex ">
            <input class="form-check-input" 
            type="checkbox" {{ $item_permission->check == 1 ? 'checked' : '' }} 
            name="approval_status" 
            id="approve" 
            onClick="return updatePermission('{{ $data_role->id }}','{{ $item_permission->id }}')">
            <label class="form-check-label" for="approve">{{  $item_permission->name}}</label>
          </div>
        </div>    
        @endforeach
        
      </div>
    </div>
  </form>
</div>
<div class="modal-footer">
  {{-- <button type="button" class="btn btn-success" id="updateData">Update</button> --}}
</div>

<script>

  function updatePermission(role_id, permission_id){
    $.ajax({
      url: "{{ route('role-permission-update') }}",
       type: "POST",  // HTTP PUT untuk update
       data: {
        role_id : role_id,
        permission_id : permission_id,
        _token: "{{ csrf_token() }}",
       },
       success: function(response) {
         console.log(response)
       },
       error: function(xhr) {
         alert("An error occurred: " + xhr.responseText);
       }
     });
  }

  // Update data
  $("#updateData").on("click", function() {
   
  $(".is-invalid").removeClass("is-invalid");
   
   let valid = true;

   let dataId = $("#data_id").val()
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
      url: "{{ route('role-update', ':id') }}".replace(':id', dataId), 
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
