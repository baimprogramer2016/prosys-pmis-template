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

        <h6 class="op-7 mb-2">Field Instruction / Upload</h6>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('field-instruction') }}"  class="btn btn-primary btn-round">Daftar</a>
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
                <i class="fas fa-pen-square"></i>
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
          <form action="{{ route('field-instruction-upload-temp')}}" class="dropzone mt-3" id="myDropzone">
            
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
          </form>
          <div class="align-items-center mb-3 mt-3   p-3 row">
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Document Number</label>
              <input type="text" class="form-control" id="document_number" name="document_number">
            </div>
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Title</label>
              <input type="text" class="form-control" id="description" name="description">
            </div>
          
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Version</label>
              <input type="text" class="form-control" id="version" name="version">
            </div>
          
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Tanggal</label>
              <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Discipline</label>
              <select class="form-select" id="discipline" name="discipline">
                  <option value=""">---Pilih---</option>
                  @foreach ($data_discipline as $item_discipline)
                  <option value="{{ $item_discipline->id }}">{{ $item_discipline->description }} </option>
                  @endforeach
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Category</label>
              <select class="form-select" id="category" name="category">
                  <option value="">---Pilih---</option>
                  {{-- @foreach ($data_category as $item_category)
                  <option value="{{ $item_category->id }}">{{ $item_category->description }} </option>
                  @endforeach --}}
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Status</label>
              <select class="form-select" id="status" name="status">
                  <option value="">---Pilih---</option>
                  @foreach ($data_status as $item_status)
                  <option value="{{ $item_status->code }}">{{ $item_status->description }} </option>
                  @endforeach
              </select>
            </div>
            <div class="col mb-3 to">
              <label for="start_date" class="form-label strong">To</label>
              <select class="form-select"  id="email" name="email">
                 
              </select>
            </div>
            <div class="col-md-12 mb-3">
              @can('add_field_instruction')
              <button id="saveUploads" class="btn btn-success mt-3 w-100 ">Submit</button>
              @endcan
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
  maxFilesize: 500, // 5MB per file
  maxFiles: 1,
  acceptedFiles: ".pdf,.jpg,.jpeg,.png,.ppt,.doc,.docx,.xls,.xlsx,.pptx,.cad,.dwg",
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
        let version = $("#version").val()
        let tanggal = $("#tanggal").val()
        let discipline = $("#discipline").val()
        let category = $("#category").val()
        let status = $("#status").val()
        let email = $("#email").val()
  
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
  
     
   // Validasi End Date
        if (version === "") {
          $("#version").addClass("is-invalid");
          valid = false;
        } 
  
  
         // Validasi End Date
         if (tanggal === "") {
          $("#tanggal").addClass("is-invalid");
          valid = false;
        } 
  
         // Validasi End Date
         if (discipline === "") {
          $("#discipline").addClass("is-invalid");
          valid = false;
        } 
  
        // if (category === "") {
        //   $("#category").addClass("is-invalid");
        //   valid = false;
        // } 
  
        if (status === "") {
          $("#status").addClass("is-invalid");
          valid = false;
        } 


  if (uploadedFiles.length === 0) {
    alert('No valid files uploaded!');
    valid = false;
    return;
  }

  if(valid == true){
    $.ajax({
      url: "{{ route('field-instruction-save-uploads') }}",
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        uploaded_files: uploadedFiles,
        document_number : document_number,
        description : description,
        version : version,
        tanggal : tanggal,
        discipline : discipline,
        category : category,
        status:status,
        email:email  
      },
      success: function (response,color) {
        if (response.status == 'ok'){
          msg_swal = "File Successfully Saved";
          color = "btn btn-success";
        }else{
          msg_swal = "Failed";
          color = "btn btn-danger";
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


function sendEmail(email,status, description){
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



  document.getElementById('status').addEventListener('change', function () {
  $("#email").prop("disabled", true);
  if($("#status").val() == 'approve' || $("#status").val() == 'notapprove'){
    $("#email").prop("disabled", true);
    $("#email").html("")
  }else{
    $("#email").prop("disabled", false);
    $.ajax({
      url: "{{ route('assign') }}",
      type: "POST",
      data: {
         status : $("#status").val(),
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

})
</script>
@endpush