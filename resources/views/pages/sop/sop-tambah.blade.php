@extends('layouts.app')

@section('content')
@push('top')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<style type="text/css">
    html, body{
        height:100%;
        padding:0px;
        margin:0px;
        
    }
    .alert-warning {
      background-color: #fff3cd;
      color: #856404;
      border: 1px solid #ffeeba;
      font-size: 14px;
      padding: 8px;
      border-radius: 4px;
    }
    .dropzone {
      border: 2px dashed #d2d6de;
      background: #f9f9f9;
      min-height: 10px;
   
      text-align: center;
    }
</style>
@endpush
<div class="page-inner">
    <div
      class="d-flex align-items-left align-items-md-center flex-column flex-md-row  pb-2"
    >
      <div class="d-flex align-items-center gap-4">

        <h6 class="op-7 mb-2">Project Procedure</h6>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('sop') }}"  class="btn btn-primary btn-round">Daftar</a>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div
                  class="icon-big text-center icon-primary bubble-shadow-small"
                >
                <i class="fas fa-pen"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0 d-flex">
                <div class="numbers">
                  <h4 class="card-title">Upload Document</h4>
                </div>
                
              </div>
            
            </div>
          </div>
        </div>
      </div>    
      <div class="col-sm-12 col-md-12">
      <div class="card ">   
        <div class="card-body">
          <div class="alert-warning text-center">Hanya bisa Upload 1 File</div>
          <form action="{{ route('sop-upload-temp')}}" class="dropzone mt-3" id="myDropzone">
            
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
          </form>
          <div class="align-items-center mb-3 mt-3   p-3 row">
            <div class="col-md-6 mb-3">
              <label for="start_date" class="form-label strong">Document Number</label>
              <input type="text" class="form-control" id="document_number" name="document_number">
            </div>
            <div class="col-md-6 mb-3">
              <label for="start_date" class="form-label strong">Description</label>
              <input type="text" class="form-control" id="description" name="description">
            </div>
          
            <div class="col-md-12 mb-3">
              <button id="saveUploads" class="btn btn-success mt-3 w-100 ">Submit</button>
            </div>

          </div>
        
        
         
        </div>
        
      </div> 
    </div>
    </div>
  </div>

  <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
     
      </div>
    </div>
  </div>
  
  
@endsection

@push('bottom')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

<script>
  var uploadedFiles = [];

Dropzone.options.myDropzone = {
  paramName: "file",
  maxFilesize: 10, // 5MB per file
  maxFiles: 1,
  acceptedFiles: ".pdf,.jpg,.jpeg,.png,.ppt",
  addRemoveLinks: true,
  init: function () {
    this.on("success", function (file, response) {
     
      let fileName = file.name;
      // Filter hanya file dengan format "nomor~perihal"
   
        uploadedFiles.push({
          path: response.path,
          fileName: fileName
        });
      
    });

    this.on("removedfile", function (file) {
      let fileName = file.name;
      uploadedFiles = uploadedFiles.filter(item => item.fileName !== fileName);
    });
  }
};

document.getElementById('saveUploads').addEventListener('click', function () {

  // Reset error messages
  $(".is-invalid").removeClass("is-invalid");
        let valid = true;
  
        let document_number = $("#document_number").val().trim();
        let description = $("#description").val();
     
  
        // Validasi Activity
        if (document_number === "") {
          $("#document_number").addClass("is-invalid");
          valid = false;
        }
  
        // Validasi Start Date
        if (description === "") {
          $("#description").addClass("is-invalid");
          valid = false;
        }
      

  if (uploadedFiles.length === 0) {
    alert('No valid files uploaded!');
    valid = false;
    return;
  }

  if(valid == true){
    $.ajax({
      url: "{{ route('sop-save-uploads') }}",
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        uploaded_files: uploadedFiles,
        document_number : document_number,
        description : description,
    
      },
      success: function (response,color) {
        if (response.status == 'ok'){
          msg_swal = "File Successfully Saved";
          color = "btn btn-success";
        }else{
          msg_swal = "Failed";
          color = "btn btn-success";
        }
              swal(msg_swal, {
                buttons: {
                  confirm: {
                    className: color,
                  },
                },
              });
            
        
        location.reload();
      },
      error: function (xhr) {
        alert('An error occurred: ' + xhr.responseText);
      }
    });
  }

});

</script>
@endpush