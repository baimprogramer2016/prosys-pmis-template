@extends('layouts.app')

@section('content')
@push('top')
    
<style type="text/css">
    html, body{
        height:100%;
        padding:0px;
        margin:0px;
        
    }
.dataTables_filter {
  margin-bottom: 10px;
}

.dt-buttons {
  margin-bottom: 10px;
  color:#fff;
}
.dataTables_wrapper .dataTables_paginate {
  margin-top: 20px;
  margin-bottom: 20px; /* Tambahkan margin bawah */
}
table.dataTable td {
    padding: 0px 0px !important; /* Kurangi padding default */
    vertical-align: middle;      /* Pastikan teks tetap di tengah */
  }
  table.dataTable {
    border: 1px solid #dee2e6;
  }
  table.dataTable th, table.dataTable td {
    border: 1px solid #dee2e6;
  }

  /* Ganti warna latar belakang header */
#myTable thead th {
    background-color: #ebf5fb;
     /* Warna teks putih */
    font-size:10px !important;
}
.bg-th{
  background-color: #ebf5fb;
}

#myTable thead th {
    padding: 5px;
} 

  #myTable tbody td  {
   font-size:12px !important;
   color: #2d2c2c
   
}
#pdf-viewer-modal .modal-dialog {
        max-width: 90%;
        margin: 1.75rem auto;
    }
    #pdf-viewer-modal .modal-body {
        height: 80vh;
        overflow-y: auto;
        padding: 0;
    }
    #pdf-canvas {
        width: 100%;
        display: block;
        margin: 0 auto;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.1/css/buttons.dataTables.css" />
@endpush
<div class="page-inner">
    <div
      class="d-flex align-items-left align-items-md-center flex-column flex-md-row  pb-2"
    >
      <div class="d-flex align-items-center gap-4">

        <h6 class="op-7 mb-2">Custom </h6>
       
        </div> 
     
    </div>
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="row">
          <div class="col-sm-12 col-md-12">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  Jika parameter Name sama, maka akan Replace data sebelumnya
                </div>
              
                  <div class="form-row d-flex align-items-center row">
                    <!-- Input Tanggal -->
                    <div class="form-group col-md-2">
                      <label for="dateInput">Name</label>
                      <input type="text" class="form-control form-control-sm" id="name" name="name">
                      {{-- <input type="text" class="form-control form-control-sm" id="model_id" name="model_id"> --}}
                    </div>
                    <div class="form-group col-md-2">
                      <label for="role">Type</label>
                      <select class="form-control form-control-sm" id="type" name="type">
                        @foreach ($data_type as $item_type)
                          <option value="{{ $item_type->description}}">{{ ucwords($item_type->description)}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label for="dateInput"><u><a target="_blank" href="https://themewagon.github.io/kaiadmin-lite/components/font-awesome-icons.html">Icon</a></u></label>
                      <input type="text" class="form-control form-control-sm" id="icon" name="icon" placeholder="fas fa-arrow-left">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="dateInput">Table</label>
                      <input type="text" class="form-control form-control-sm" id="tab" name="tab" placeholder="Ex : construction_drawing">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="role">Parent</label>
                      <select class="form-control form-control-sm" id="parent" name="parent">
                        <option value="">Pilih Parent</option>
                        @foreach ($data_parent as $item_parent)
                          <option value="{{ $item_parent->id}}">{{ ucwords($item_parent->name)}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label for="role">Template</label>
                      <select class="form-control form-control-sm" id="template" name="template">
                        @foreach ($data_template as $item_template)
                          <option value="{{ $item_template->description}}">{{ ucwords($item_template->description)}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end mb-2 justify-content-start">
                      <button type="submit" id="save" name="save" class="btn btn-sm btn-primary">Create Menu</button>
                    </div> 
      
                  <!-- Tombol Submit -->
                
              </div>
            </div>
          </div>
        </div>
      </div>
        
      <div class="col-sm-12 col-md-12">
      <div class="card ">
               
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="myTable">
              <thead>
                <tr>  
                  <th  class="bg-th">Name</th>
                  <th  class="bg-th">Type</th>
                  <th  class="bg-th">Parent</th>
                  <th  class="bg-th">Icon</th>
                  <th  class="bg-th">Table</th>                  
                  <th  class="bg-th">Table History</th>                  
                  <th  class="bg-th">Template</th>
                  <th  class="bg-th">Date</th>
                  <th  class="bg-th">Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
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
<!-- Bootstrap 5 + DataTables -->
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<!-- DataTables Buttons Scripts -->
<script src="https://cdn.datatables.net/buttons/3.2.1/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.1/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.1/js/buttons.print.min.js"></script>

<script>
    $("#parent").prop("disabled", true);
    $("#tab").prop("disabled", true);

document.getElementById('type').addEventListener('change', function () {
  $("#parent").prop("disabled", false);
  if($("#type").val() == 'parent'){
    $("#parent").prop("disabled", true);
    $("#icon").prop("disabled", false);
    $("#tab").prop("disabled", true);
    $("#tab").val("");
    $("#parent").html("")
  }else{
    $("#tab").prop("disabled", false);
    $("#parent").prop("disabled", false);
    $("#icon").prop("disabled", true);
    $.ajax({
      url: "{{ route('get-parent') }}",
      type: "GET",
      data: {
        _token: "{{ csrf_token() }}",
      },
      success: function (response) {
      
        let options ='';
        if(response.length > 0){
        
          response.forEach((item) =>{
            options += `<option value="${item.id}">${item.name}</option>`; 
          })
        }

     
        $("#parent").html(options);
      
      },
      error: function (xhr) {
        alert('An error occurred: ' + xhr.responseText);
      }
    });
  }
})

document.getElementById('save').addEventListener('click', function () {

// Reset error messages
$(".is-invalid").removeClass("is-invalid");
      let valid = true;
      let name = $("#name").val();
      let type = $("#type").val()
      let tab = $("#tab").val()
      let icon = $("#icon").val()
      let tab_history =($("#tab").val() != "") ? $("#tab").val()+'_history' :'';
      let parent = $("#parent").val()
      let template = $("#template").val()
  
 // Validasi End Date
      if (name === "") {
        $("#name").addClass("is-invalid");
        valid = false;
      } 
       // Validasi End Date
       if (type === "") {
        $("#type").addClass("is-invalid");
        valid = false;
      } 
       if (tab === "" && type==="child") {
        $("#tab").addClass("is-invalid");
        valid = false;
      } 
       if (type === "child" && parent === "") {
        console.log(type)
        $("#parent").addClass("is-invalid");
        valid = false;
      } 
       if (template === "") {
        $("#template").addClass("is-invalid");
        valid = false;
      } 
       if (icon === "" && type === "parent") {
        $("#icon").addClass("is-invalid");
        valid = false;
      } 

if(valid == true){
  
  $.ajax({
    url: "{{ route('master-custom-save') }}",
    type: "POST",
    data: {
      _token: "{{ csrf_token() }}",
      name : name,
      type : type,
      tab : tab.toLowerCase().replace(/\s+/g, "_"),
      tab_history : tab_history.toLowerCase().replace(/\s+/g, "_"),
      icon : icon,
      parent : parent,
      template : template ,
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

function viewDelete(param){
  $(".modal-content").html("");
$.ajax({
  url: "{{ route('master-custom-delete', ':id') }}".replace(':id', param), // Ganti dengan route yang sesuai
    type: "GET",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) {
    
      $(".modal-content").html("");
      $(".modal-content").html(response);
      
    },
    error: function(xhr) {
        alert('An error occurred: ' + xhr.responseText);
    }
});
}

function viewEdit(param){
  console.log(param)
  if(param["type"] == 'child'){
    $("#icon").prop("disabled", true);
    $("#tab").prop("disabled", false);
    $("#parent").prop("disabled", false);
  }else{
    $("#icon").prop("disabled", false);
    $("#tab").prop("disabled", true);
    $("#parent").prop("disabled", true);
  }
  $("#name").val(param["name"])
  $("#model_id").val(param["id"])
  $("#type").val(param["type"])
  $("#icon").val(param["icon"])
  $("#tab").val(param["tab"])
  $("#parent").val(param["parent"])
  $("#template").val(param["template"])

}

 $(document).ready(function() {
  var table = $('.table').DataTable({
          processing: true,
          serverSide: true,
          stateSave: true,
          pageLength: 30,  // Ini mengatur default jumlah data per halaman
          lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ], 
          language : {
                sLengthMenu: "Show _MENU_"
            },
      
          ajax: {
            url : "{{ route('get-master-custom') }}",
          },
          dom: '<"d-flex flex-column"<"mb-2"B><"d-flex justify-content-between"lf>>rtip',
          buttons: [
            { extend: 'excelHtml5', text: 'Export Excel', className: 'btn btn-success btn-sm' },
            { extend: 'pdfHtml5', text: 'Export PDF', className: 'btn btn-danger btn-sm' },
            { extend: 'print', text: 'Print', className: 'btn btn-primary btn-sm' }
          ],
          columns: [
              { data: 'name', name: 'name' },
              { data: 'parent_type', name: 'parent_type' },
              { data: 'parent_desc', name: 'parent_desc'},
              { data: 'icon_show', name: 'icon_show' },
              { data: 'tab', name: 'tab' },
              { data: 'tab_history', name: 'tab_history' },
              { data: 'template', name: 'template' },
              { data: 'created_at', name: 'created_at',render: function(data, type, row) {
                if (!data) return ""; // Jika data kosong, return string kosong
                const date = new Date(data);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Januari = 0
                const year = date.getFullYear();
                return `${year}-${month}-${day}`;
            }  },
              { data: 'action', name: 'action', orderable: false, searchable: false } ,
             ],
      });
    });
    </script>

  
@endpush