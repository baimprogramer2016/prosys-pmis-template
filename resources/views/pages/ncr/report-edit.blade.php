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

        <h6 class="op-7 mb-2">Quality Management / Edit</h6>
      </div>
      <div onClick="addView()" class="ms-md-auto py-2 py-md-0">
        {{-- <a href="#" class="btn btn-label-info btn-round me-2">Manage</a> --}}
        <a href="{{ route('ncr') }}"  class="btn btn-primary btn-round">Daftar</a>
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
                  <h4 class="card-title">Edit Ncr</h4>
                </div>
                
              </div>
            
            </div>
          </div>
        </div>
      </div>    
      <div class="col-sm-12 col-md-12">
      <div class="card ">   
        <div class="card-body">
          <div class="alert-warning text-center">Hanya bisa Upload 1 File <strong>Kosongkan jika Document tidak dirubah</strong></div>
          <form action="{{ route('ncr-upload-temp')}}" class="dropzone mt-3" id="myDropzone">
            
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
          </form>
          <div class="align-items-center mb-3 mt-3   p-3 row">
            <input type="hidden" class="form-control" id="id_edit" name="id_edit" value="{{$document->id}}">
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Document Number</label>
              <input type="text" class="form-control" id="document_number" name="document_number" value="{{$document->document_number}}">
            </div>
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Title</label>
              <input type="text" class="form-control" id="title" name="title" value="{{$document->title}}">
            </div>
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Description</label>
              <input type="text" class="form-control" id="description" name="description" value="{{$document->description}}">
            </div>
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Category</label>
              <input type="text" class="form-control" id="category" name="category" value="{{$document->category}}">
            </div>
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Status</label>
              <input type="text" class="form-control" id="status" name="status" value="{{$document->status}}">
            </div>
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Pic</label>
              <input type="text" class="form-control" id="pic" name="pic" value="{{$document->pic}}">
            </div>
            <div class="col-md-4 mb-3">
              <label for="start_date" class="form-label strong">Due Date</label>
              <input type="date" class="form-control" id="due_date" name="due_date" value="{{ \Carbon\Carbon::parse($document->due_date)->format('Y-m-d') }}">
            </div>
           
            <div class="col-md-12 mb-3">
              <button id="saveUploads" class="btn btn-success mt-3 w-100 ">Update</button>
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
  maxFilesize: 100, // 5MB per file
  maxFiles: 1,
  acceptedFiles: ".pdf,.jpg,.jpeg,.png,.ppt,.doc,.docx,.xls,.xlsx,.pptx,.cad",
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
        let title = $("#title").val().trim();
        let description = $("#description").val();
        let category = $("#category").val();
        let status = $("#status").val();
        let pic = $("#pic").val();
        let due_date = $("#due_date").val();
        let id_edit = $("#id_edit").val()
    
        if (document_number === "") {
          $("#document_number").addClass("is-invalid");
          valid = false;
        }
  
        // Validasi Start Date
        if (title === "") {
          $("#title").addClass("is-invalid");
          valid = false;
        }
        if (description === "") {
          $("#description").addClass("is-invalid");
          valid = false;
        }
        // Validasi Start Date
        if (category === "") {
          $("#category").addClass("is-invalid");
          valid = false;
        }
        if (status === "") {
          $("#status").addClass("is-invalid");
          valid = false;
        }
        if (due_date === "") {
          $("#due_date").addClass("is-invalid");
          valid = false;
        }
        if (pic === "") {
          $("#pic").addClass("is-invalid");
          valid = false;
        }
  

  if(valid == true){
    $.ajax({
      url: "{{ route('ncr-update',':id') }}".replace(':id', id_edit),
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        uploaded_files: uploadedFiles,
        document_number : document_number,
        title : title,
        description : description,
        category : category,
        status : status,
        pic : pic,
        due_date : due_date,
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

</script>
@endpush