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
      min-height: 100px;
   
      text-align: center;
    }
</style>
@endpush
<div class="page-inner">
    <div
      class="d-flex align-items-left align-items-md-center flex-column flex-md-row  pb-2"
    >
      <div class="d-flex align-items-center gap-4">

        <h6 class="op-7 mb-2">Surat</h6>
      </div>
      <div onClick="addView()" class="ms-md-auto py-2 py-md-0">
        {{-- <a href="#" class="btn btn-label-info btn-round me-2">Manage</a> --}}
        <a href="{{ route('surat') }}"  class="btn btn-primary btn-round">Daftar Surat</a>
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
                  <h4 class="card-title">Upload Surat</h4>
                </div>
                
              </div>
            
            </div>
          </div>
        </div>
      </div>    
      <div class="col-sm-12 col-md-12">
      <div class="card ">
               
        <div class="card-body">
          <div class="alert-warning">Format File (nomor~nama surat) Cth: 001/XXX/YYY/2022~UNDANGAN PT. XXX, Maksimal : 5 File</div>
          <form action="{{ route('surat-upload-temp')}}" class="dropzone mt-3" id="myDropzone">
            
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
          </form>
          <div class="d-flex align-items-center mb-3 mt-3 border  p-3">
            <div class="me-4">
              <label class="form-label strong">Jenis Surat</label>
              <div class="d-flex align-items-center">
                <div class="form-check me-3">
                  <input class="form-check-input" type="radio" name="jenis" id="surat_masuk" value="masuk" checked>
                  <label class="form-check-label" for="surat_masuk">Surat Masuk</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="jenis" id="surat_keluar" value="keluar">
                  <label class="form-check-label" for="surat_keluar">Surat Keluar</label>
                </div>
              </div>
            </div>
            <div>
              <label for="start_date" class="form-label strong">Tanggal Surat</label>
              <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">

            </div>
          </div>
          <button id="saveUploads" class="btn btn-success mt-3">Upload</button>
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
  maxFiles: 5,
  acceptedFiles: ".pdf,.jpg,.jpeg,.png,.ppt,.doc,.docx,.xls,.xlsx,.pptx,.cad,.dwg",
  addRemoveLinks: true,
  init: function () {
    this.on("success", function (file, response) {
     
      let fileName = file.name;
      // Filter hanya file dengan format "nomor~perihal"
      if (fileName.includes("~")) {
        uploadedFiles.push({
          path: response.path,
          fileName: fileName
        });
      } else {
        alert(`File ${fileName} tidak sesuai format penulisan!`);
        this.removeFile(file);
      }
    });

    this.on("removedfile", function (file) {
      let fileName = file.name;
      uploadedFiles = uploadedFiles.filter(item => item.fileName !== fileName);
    });
  }
};

document.getElementById('saveUploads').addEventListener('click', function () {
  if (uploadedFiles.length === 0) {
    alert('No valid files uploaded!');
    return;
  }

  $.ajax({
    url: "{{ route('surat-save-uploads') }}",
    type: "POST",
    data: {
      _token: "{{ csrf_token() }}",
      uploaded_files: uploadedFiles,
      jenis : $('input[name="jenis"]:checked').val(),
      tanggal: $("#tanggal").val()
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
});

</script>
@endpush