@extends('layouts.app')

@section('content')
@push('top')
    
<style type="text/css">
    html, body{
        height:100%;
        padding:0px;
        margin:0px;
        
    }
    #progressTable {
      font-size:12px;
      width: 100%;
      border-radius: 4px;
      text-align: center;
      font-family: Arial, sans-serif;
    }
    #progressTable th, #progressTable td {
      border: 1px solid #a18d8d;
      padding: 8px;
    }
    #progressTable th {
      background-color: #6a1b9a;
      color: white;
    }
    .row-physical {
      background-color: #cb4335;
      color: white;
    }
    .row-plan {
      background-color: #2e86c1;
      color: white;
    }

#multipleLineChart {
    height: 400px !important; /* Ubah ini sesuai keinginan */
  }
</style>
@endpush
<div class="page-inner">
    <div
      class="d-flex align-items-left align-items-md-center flex-column flex-md-row  pb-2"
    >
      <div class="d-flex align-items-center gap-4">

        <h6 class="op-7 mb-2">Schedule Management / S-Curve</h6>
       
        </div> 
     
      <div class="ms-md-auto py-2 py-md-0">
        {{-- <a href="{{ route('schedule-management-tambah') }}"  class="btn btn-primary btn-round">Tambah</a> --}}
       </div>
    </div>
    <div class="row">
      {{-- <div class="col-sm-12 col-md-12">
        <div class="row">
          <div class="col-sm-12 col-md-12">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-icon">
                    <div
                      class="icon-big text-center icon-primary bubble-shadow-small"
                    >
                    <i class="fas fa-chart-line"></i>
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0 d-flex">
                    <div class="numbers">
                      <h4 class="card-title">S- Curve</h4>
                    </div>
                    
                  </div>
                
                </div>
              </div>
            </div>
          </div>    
        </div>
      </div>     --}}
      <div class="col-sm-12 col-md-12">
        <div class="card">
          <div class="card-header d-flex flex-column flex-lg-row align-items-center justify-content-between">
            <div class="card-title fw-bold mb-2 mb-lg-0">S-Curve</div>
            <div class="d-flex flex-wrap align-items-center w-70 justify-content-end">
              <div class="me-3 mb-2 mb-lg-0 d-flex align-items-center">
                <label for="category" class="me-2 mb-0 fw-bold">Category</label>
                <select class="form-control form-control-sm" id="category" name="category">
                  <option value="all">All</option>
                  @foreach ($data_sub_category as $item_sub_category)
                    <option value="{{ $item_sub_category->description }}">{{ $item_sub_category->description }}</option>
                  @endforeach
                </select>
              </div>
              <div class="me-3 mb-2 mb-lg-0 d-flex align-items-center">
                <label for="tanggal_awal" class="me-2 mb-0 fw-bold">Tanggal Awal: {{ $min_date }}</label>
                <input type="date" id="tanggal_awal" class="form-control form-control-sm" style="width: 150px;" value="{{ $min_date }}">
              </div>
              <div class="me-3 mb-2 mb-lg-0 d-flex align-items-center">
                <label for="tanggal_akhir" class="me-2 mb-0 fw-bold">Tanggal Akhir:</label>
                <input type="date" id="tanggal_akhir" class="form-control form-control-sm" style="width: 150px;" value="{{ $max_date }}">
              </div>
              <div class="d-flex">
                <button class="btn btn-primary btn-sm" id="filterBtn">Filter</button>
              </div>
            </div>
          </div>
          
          <div class="card-body">
            <div class="chart-container" id="chart-container-1">
              <canvas id="multipleLineChart" ></canvas>
            </div>
            <div class="table-wrapper bg-success" style="overflow-x: auto;">
              <table id="progressTable">
                <thead>
                  <tr id="tableHeader"></tr>
                </thead>
                <tbody id="tableBody"></tbody>
              </table>
            </div>
            

            
          </div>
        </div>
      </div>
    </div>
  </div>

  
@endsection

@push('bottom')
<script src="{{asset('assets/js/plugin/chart.js/chart.min.js')}}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script> --}}
<script>

$("#filterBtn").click(function() {
  let valid = true;
  // if($("#tanggal_awal").val() == ""){
  //   $("#tanggal_awal").addClass("is-invalid");
  //   valid = false
  // }
  // if($("#tanggal_akhir").val() == ""){
  //   $("#tanggal_akhir").addClass("is-invalid");
  //   valid = false
  // }
  
  if(valid) {
    resetTable();
    resetChartContainer(); 

    showChart($("#tanggal_awal").val(),$("#tanggal_akhir").val(),$("#category").val());
  }
 
});


function resetChartContainer() {
  const chartContainer = document.getElementById("chart-container-1");
  
  // Hapus semua elemen dalam chart container
  chartContainer.innerHTML = "";

  // Buat elemen canvas baru
  const newCanvas = document.createElement("canvas");
  newCanvas.setAttribute("id", "multipleLineChart");
  chartContainer.appendChild(newCanvas);
}

showChart('', '','all')
function showChart(param_tgl_awal, param_tgl_akhir,param_category){

  $.ajax({
    url: "{{route('s-curve-chart-data')}}",
    data: {
      start_date: param_tgl_awal,
      end_date: param_tgl_akhir,
      category: param_category
    },
    method:"GET",
    success:function(response){
      // console.log(response)
      if(response.status == 'ok'){
        curveChart(response)

          // Create table header
        const tableHeader = document.getElementById("tableHeader");
        const thWeek = document.createElement("th");
        thWeek.innerText = "Week";
        tableHeader.appendChild(thWeek);

        response.weeks.forEach(week => {
          const th = document.createElement("th");
          th.innerText = week;
          tableHeader.appendChild(th);
        });

        const tableBody = document.getElementById("tableBody");
          // Add rows for Physical and Plan
          createRow("Planned", response.planned, "row-physical");
          createRow("Actual",  response.actual, "row-plan");

      }else{
        alert("Terjadi Kesalahan")
      }
    }
  })
}

function resetTable() {
  document.getElementById("tableHeader").innerHTML = ""; // Kosongkan header tabel
  document.getElementById("tableBody").innerHTML = "";   // Kosongkan body tabel
}

  function createRow(label, data, className) {
      const tr = document.createElement("tr");
      tr.classList.add(className);

      const tdLabel = document.createElement("td");
      tdLabel.innerText = label;
      tr.appendChild(tdLabel);

      data.forEach(value => {
        // console.log(value)
        const td = document.createElement("td");
        td.innerText = value;
        tr.appendChild(td);
      });

      tableBody.appendChild(tr);
    }

  function curveChart(param){
    var multipleLineChart;
    

    multipleLineChart = document
    .getElementById("multipleLineChart")
    .getContext("2d");
  
    multipleLineChart = new Chart(multipleLineChart, {
    type: "line",
    data: {
      labels: param.weeks,
      datasets: [
        {
          label: "Planned",
          borderColor: "#f3545d",
          pointBorderColor: "#FFF",
          pointBackgroundColor: "#f3545d",
          pointBorderWidth: 2,
          pointHoverRadius: 6,
          pointHoverBorderWidth: 1,
          pointRadius: 4,
          backgroundColor: "transparent",
          fill: true,
          borderWidth: 4,
          data: param.planned
        },
        {
          label: "Actual",
          borderColor: "blue",
          pointBorderColor: "#FFF",
          pointBackgroundColor: "blue",
          pointBorderWidth: 2,
          pointHoverRadius: 6,
          pointHoverBorderWidth: 1,
          pointRadius: 4,
          backgroundColor: "transparent",
          fill: true,
          borderWidth: 4,
          data: param.actual
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: "top",
        labels: {
          boxWidth: 12,
          fontSize: 12
        }
      },
      scales: {
        x: {
          grid: {
            color: function (ctx) {
              return ctx.index % 2 === 0 ? "rgba(0, 0, 0, 0.1)" : "rgba(0, 0, 0, 0.05)";
            }
          }
        },
        y: {
          ticks: {
            stepSize: 20,
            callback: function (value) {
              return value + "%"; // Menambahkan "%" di label Y-axis
            }
          },
          grid: {
            color: function (ctx) {
              return ctx.tick.value % 40 === 0 ? "rgba(255, 99, 132, 0.2)" : "rgba(75, 192, 192, 0.1)";
            }
          }
        }
      },
      tooltips: {
        bodySpacing: 4,
        mode: "nearest",
        intersect: 0,
        position: "nearest",
        xPadding: 12,
        yPadding: 12,
        caretPadding: 10,
        callbacks: {
          label: function (tooltipItem, data) {
            return data.datasets[tooltipItem.datasetIndex].label + ": " + tooltipItem.yLabel + "%";
          }
        }
      },
      layout: {
        padding: { left: 15, right: 15, top: 15, bottom: 15 }
      }
    }
  });
  }
  </script>
  
  
@endpush