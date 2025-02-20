<div class="modal-header">

    <h5 class="modal-title" id="modalTambahLabel">Document Check</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <form id="formData">
      <div class="mb-3">
        <label for="start_date" class="form-label">Document Number</label>
        <input type="hidden" id="data_id" name="data_id" value="{{ $document->id }}"> <!-- Hidden input for ID -->
        <input type="hidden" id="description" name="description" value="{{ $document->description }}"> <!-- Hidden input for ID -->
        <input type="text" readonly class="form-control" value="{{ $document->document_number }}">
      </div>
      <div class="col mb-3 to">
        <label for="start_date" class="form-label strong">To</label>
   
        <select class="form-select"  id="email" name="email">
        
        </select>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-success" id="checkData">Update</button>
  </div>
  
  <script>
    emailApproval();
    function emailApproval(){
      $.ajax({
      url: "{{ route('assign') }}",
      type: "POST",
      data: {
         status : 'check',
        _token: "{{ csrf_token() }}",
      },
      success: function (response) {
      
        let options ='';
        if(response.length > 0){
        
          response.forEach((item) =>{
            options += `<option value="${item.email}">${item.name} Email : ${item.email}</option>`; 
          })
        }

        $("#email").html(options);
      
      },
      error: function (xhr) {
        alert('An error occurred: ' + xhr.responseText);
      }
    });
    }
  
    // Update data
    $("#checkData").on("click", function() {
      $(".is-invalid").removeClass("is-invalid");
   
      let dataId = $("#data_id").val();
      let email = $("#email").val();
      let description = $("#description").val();

  
        let formData = {
          id: dataId,
          email:email,
          _token: "{{ csrf_token() }}",
        };
  
        $.ajax({
          url: "{{ route('document-engineer-check-update', ':id') }}".replace(':id', dataId), // Ganti dengan route yang sesuai
    
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

            sendEmail(email,'check',description)   

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

    
function sendEmail(email,status, description){
  console.log(email, status, description)
  $.ajax({
      url: "{{ route('send-mail') }}",
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        status:status,  
        email:email,
        description:description
      },
      success: function (response,color) {
      }
    });
  }

  </script>
  