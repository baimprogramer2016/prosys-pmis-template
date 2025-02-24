@extends('layouts.app')

@section('content')
@push('top')
    
<script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
<link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">

<style type="text/css">
    html, body{
        height:100%;
        padding:0px;
        margin:0px;
        
    }
  /* Ganti warna latar belakang header */
#myTable thead th {
    background-color: #ebf5fb;
     /* Warna teks putih */
    font-size:12px;
}
.bg-th{
  background-color: #ebf5fb;
}

/* Optional: Tambahkan padding untuk header */
#myTable thead th {
    padding: 5px;
} 

/* Optional: Tambahkan padding untuk header */

/* Ganti warna latar belakang dan teks pada sel <td> */
  #myTable tbody td td {
   font-size:10px;
}
</style>
@endpush
<div class="page-inner">
    <div
      class="d-flex align-items-left align-items-md-center flex-column flex-md-row  pb-2"
    >
      <div class="d-flex align-items-center gap-4">

        <h6 class="op-7 mb-2">Master Schedule</h6>
      </div>
      <div onClick="addView('0')" class="ms-md-auto py-2 py-md-0">
        {{-- <a href="#" class="btn btn-label-info btn-round me-2">Manage</a> --}}
        <a  class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modal">Tambah</a>
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
                <i class="fas fa-calendar-alt"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0 d-flex">
                <div class="numbers">
                  <h4 class="card-title">Schedule</h4>
                </div>
                
              </div>
            
            </div>
          </div>
        </div>
      </div>    
      <div class="col-sm-12 col-md-12">
      <div class="card ">
               
        <div class="card-body">
          {{-- <div class="card-sub">
            Create responsive tables by wrapping any table with
            <code class="highlighter-rouge">.table-responsive</code>
            <code class="highlighter-rouge">DIV</code> to make them
            scroll horizontally on small devices
          </div> --}}
          <div class="table-responsive">
            <table class="table table-bordered" id="myTable">
              <thead>
                <tr>
                  <th  class="bg-th">Activity</th>
                  <th  class="bg-th">Parent</th>
                  <th  class="bg-th">Duration</th>
                  <th  class="bg-th">Start Date</th>
                  <th  class="bg-th">End Date</th>
                  <th  class="bg-th">Progress</th>
                  {{-- <th>Status</th> --}}
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
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
  function addView(param){
    $(".modal-content").html("");
    $.ajax({
        url: "{{ route('master-schedule-tambah', ':id') }}".replace(':id', param), // Ganti dengan route yang sesuai
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
  function editView(param){
    $(".modal-content").html("");
    $.ajax({
      url: "{{ route('master-schedule-edit', ':id') }}".replace(':id', param), // Ganti dengan route yang sesuai
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
  function deleteView(param){
    $(".modal-content").html("");
    $.ajax({
        url: "{{ route('master-schedule-delete', ':id') }}".replace(':id', param), // Ganti dengan route yang sesuai
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


  
</script>

<script>
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
            url : "{{ route('get-schedule') }}",
          },
          columns: [
              { data: 'text', name: 'text' },
              { data: 'parent_desc', name: 'parent_desc' },
              { data: 'duration', name: 'duration' },
              { data: 'start_date', name: 'start_date' ,render: function(data, type, row) {
                if (!data) return ""; // Jika data kosong, return string kosong
                const date = new Date(data);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Januari = 0
                const year = date.getFullYear();
                return `${year}-${month}-${day}`;
            } },
              { data: 'end_date', name: 'end_date' ,render: function(data, type, row) {
                if (!data) return ""; // Jika data kosong, return string kosong
                const date = new Date(data);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Januari = 0
                const year = date.getFullYear();
                return `${year}-${month}-${day}`;
            } },
              { data: 'progress1', name: 'progress1' }    ,
              { data: 'action', name: 'action', orderable: false, searchable: false }   
             ],
            
      });
    });
    </script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
  
<!-- Kaiadmin DEMO methods, don't include it in your project! -->

@endpush