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

        <h6 class="op-7 mb-2">Schedule Management / Input S-Curve</h6>
       
        </div> 
     
    </div>
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="row">
          <div class="col-sm-12 col-md-12">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  *Jika parameter Kategori dan tanggal sama, maka akan Replace data sebelumnya <br>
                  *Jika ingin merubah tanggal, lebih baik di hapus dan input ulang
                </div>
              
                  <div class="form-row d-flex align-items-center">
                    
                    <!-- Dropdown -->
                  
                    <div class="form-group col-md-3">
                      <label for="optionSelect">Pilih Category</label>
                      <select class="form-control form-control-sm" id="description" name="description">
                        <option value="">Pilih Category</option>
                        @foreach ($data_category as $item_category)
                        <option value="{{ $item_category->description}}">{{ $item_category->description}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="optionSelect">Pilih Sub Category</label>
                      <select class="form-control form-control-sm" id="category" name="category">
                        <option value="">Pilih Sub Category</option>
                        @foreach ($data_sub_category as $item_sub_category)
                        <option value="{{ $item_sub_category->description}}">{{ $item_sub_category->description}}</option>
                        @endforeach
                      </select>
                    </div>
      
                    <!-- Input Tanggal -->
                    <div class="form-group col-md-3">
                      <label for="dateInput">Tanggal</label>
                      <input type="date" class="form-control form-control-sm" id="tanggal" name="tanggal">
                    </div>
      
                    <!-- Input Persentase -->
                    <div class="form-group col-md-3">
                      <label for="percentInput">Persentase (%)</label>
                      <input type="number" class="form-control form-control-sm" id="percent" name="percent" min="0" max="100" step="0.01" placeholder="0-100">
                    </div>
                    
                  </div>
      
                  <!-- Tombol Submit -->
                  <button type="submit" id="save" name="save" class="btn btn-sm btn-primary ms-2">Update & Insert</button>
                
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
                  <th  class="bg-th">Category</th>
                  <th  class="bg-th">Sub Category</th>
                  <th  class="bg-th">Tanggal</th>
                  <th  class="bg-th">Percent</th>
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

document.getElementById('save').addEventListener('click', function () {

// Reset error messages
$(".is-invalid").removeClass("is-invalid");
      let valid = true;
      let description = $("#description").val();
      let tanggal = $("#tanggal").val()
      let percent = $("#percent").val()
      let category = $("#category").val()
      // Validasi Activity
   
      // Validasi Start Date
      if (description === "") {
        $("#description").addClass("is-invalid");
        valid = false;
      }

   
 // Validasi End Date
      if (tanggal === "") {
        $("#tanggal").addClass("is-invalid");
        valid = false;
      } 
       // Validasi End Date
       if (percent === "") {
        $("#percent").addClass("is-invalid");
        valid = false;
      } 
       if (category === "") {
        $("#category").addClass("is-invalid");
        valid = false;
      } 

if(valid == true){
  console.log("masuk")
  $.ajax({
    url: "{{ route('s-curve-save') }}",
    type: "POST",
    data: {
      _token: "{{ csrf_token() }}",
      description : description,
      percent : percent,
      tanggal : tanggal,
      category : category,
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
  url: "{{ route('s-curve-delete', ':id') }}".replace(':id', param), // Ganti dengan route yang sesuai
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
  $("#description").val(param["description"])
  $("#tanggal").val(param["tanggal"])
  $("#percent").val(param["percent"])
  $("#category").val(param["category"])
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
            //mdr tidak ada kondisi
            url : "{{ route('get-s-curve') }}",
          },
          dom: '<"d-flex flex-column"<"mb-2"B><"d-flex justify-content-between"lf>>rtip',
          buttons: [
            { extend: 'excelHtml5', text: 'Export Excel', className: 'btn btn-success btn-sm' },
            { extend: 'pdfHtml5', text: 'Export PDF', className: 'btn btn-danger btn-sm' },
            { extend: 'print', text: 'Print', className: 'btn btn-primary btn-sm' }
          ],
          columns: [
              { data: 'description', name: 'description' },
              { data: 'category', name: 'category' },
              { data: 'tanggal', name: 'tanggal',render: function(data, type, row) {
                if (!data) return ""; // Jika data kosong, return string kosong
                const date = new Date(data);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Januari = 0
                const year = date.getFullYear();
                return `${year}-${month}-${day}`;
            }  },
              { data: 'percent', name: 'percent' },
              { data: 'action', name: 'action', orderable: false, searchable: false } ,
             ],
            
      });
    });
    </script>

  
@endpush