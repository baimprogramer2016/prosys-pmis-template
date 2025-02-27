@extends('layouts.app')

@section('content')
@push('top')
    <style>
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
      </style>
@endpush

<div class="page-inner">
    <div
      class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
    >
      <div>
        {{-- <h3 class="fw-bold mb-3">Dashboard</h3> --}}
        <h6 class="op-7 mb-2">Dashboard</h6>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        {{-- <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
        <a href="#" class="btn btn-primary btn-round">Add Customer</a> --}}
      </div>
    </div>
    <div class="row">
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
                <label for="tanggal_awal" class="me-2 mb-0 fw-bold">Tanggal Awal:</label>
                <input type="date" id="tanggal_awal" class="form-control form-control-sm" style="width: 150px;">
              </div>
              <div class="me-3 mb-2 mb-lg-0 d-flex align-items-center">
                <label for="tanggal_akhir" class="me-2 mb-0 fw-bold">Tanggal Akhir:</label>
                <input type="date" id="tanggal_akhir" class="form-control form-control-sm" style="width: 150px;">
              </div>
              <div class="d-flex">
                <button class="btn btn-primary btn-sm" id="filterBtnSCurve">Filter</button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container" id="sCurveContainer">
              <canvas id="sCurveChart" ></canvas>
            </div>

            <table id="progressTable">
              <thead>
                <tr id="tableHeader"></tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title">Correspondence</div>
            <div class="d-flex align-items-center">
              <label for="start_date" class="me-2 mb-0">Tanggal Awal:</label>
              <input type="date" id="start_date" class="form-control form-control-sm me-3" style="width: 150px;">
              
              <label for="end_date" class="me-2 mb-0">Tanggal Akhir:</label>
              <input type="date" id="end_date" class="form-control form-control-sm me-3" style="width: 150px;">
              
              <button class="btn btn-primary btn-sm" id="filterBtn">Filter</button>
            </div>
          </div>
          <div class="card-body row">
            <div class="chart-container col-md-4" id="pieContainer1">
              <canvas
                id="pieChart1"
                style="width: 50%; height: 50%"
              ></canvas>
            </div>
            <div class="chart-container col-md-4" id="pieContainer2">
              <canvas
                id="pieChart2"
                style="width: 50%; height: 50%"
              ></canvas>
            </div>
            <div class="chart-container col-md-4" id="pieContainer3">
              <canvas
                id="pieChart3"
                style="width: 50%; height: 50%"
              ></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="card-title">Document Management</div>
            <div class="d-flex align-items-center">
              {{-- <label for="start_date" class="me-2 mb-0">Tanggal Awal:</label> --}}
              <input type="date" id="start_date_document_management" class="form-control form-control-sm me-3" style="width: 100%;">
              
              {{-- <label for="end_date" class="me-2 mb-0">Tanggal Akhir:</label> --}}
              <input type="date" id="end_date_document_management" class="form-control form-control-sm me-3" style="width: 100%;">
              
              <button class="btn btn-primary btn-sm" id="filterBtnDocumentMangement">Filter</button>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container"  id="documentManagementContainer">
              <canvas id="barChartDocumentManagement"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="card-title">Procurement & Logistic</div>
            <div class="d-flex align-items-center">
              {{-- <label for="start_date" class="me-2 mb-0">Tanggal Awal:</label> --}}
              <input type="date" id="start_date_procurement_logistic" class="form-control form-control-sm me-3" style="width: 100%;">
              
              {{-- <label for="end_date" class="me-2 mb-0">Tanggal Akhir:</label> --}}
              <input type="date" id="end_date_procurement_logistic" class="form-control form-control-sm me-3" style="width: 100%;">
              
              <button class="btn btn-primary btn-sm" id="filterBtnProcurementLogistic">Filter</button>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container" id="procurementLogisticContainer">
              <canvas id="barChartProcurementLogistic"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title">Drawings</div>
            <div class="d-flex align-items-center">
              <label for="start_date" class="me-2 mb-0">Tanggal Awal:</label>
              <input type="date" id="start_date_drawing" class="form-control form-control-sm me-3" style="width: 150px;">
              
              <label for="end_date" class="me-2 mb-0">Tanggal Akhir:</label>
              <input type="date" id="end_date_drawing" class="form-control form-control-sm me-3" style="width: 150px;">
              
              <button class="btn btn-primary btn-sm" id="filterBtnDrawings">Filter</button>
            </div>
          </div>
        </div>
        <div class="row d-flex justify-content-between p-0" id="drawing-container">
       {{-- Drawing disini --}}
        </div>
        
      </div>
    </div>
   
  </div>
@endsection

@push('bottom')
<script src="{{ asset('assets/js/plugin/chart.js/chart.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
function resetChartContainer(container,id_canvas) {
    const chartContainer = document.getElementById(container);
    // Hapus semua elemen dalam chart container
    chartContainer.innerHTML = "";
    // Buat elemen canvas baru
    const newCanvas = document.createElement("canvas");
    newCanvas.setAttribute("id", id_canvas);
    chartContainer.appendChild(newCanvas);
}

//DOCUMENT MANAGEMENT
$("#filterBtnDocumentMangement").click(function() { 
      resetChartContainer('documentManagementContainer','barChartDocumentManagement'); 
      getDataDocumentManagement($("#start_date_document_management").val(),$("#end_date_document_management").val());
});

getDataDocumentManagement('','');
function getDataDocumentManagement(start_date, end_date){
  barChartDocumentManagement = document.getElementById("barChartDocumentManagement").getContext("2d");

  
  $.ajax({
      url: "{{route('dashboard-document-management')}}",
      data: {
        start_date: start_date,
        end_date: end_date,
      },
      method:"GET",
      success:function(response){
        console.log(response);
      const chartDataDocumentManagement = response;

      // Mengonversi struktur data ke format Chart.js
      const labelsDocumentManagement = chartDataDocumentManagement.map(item => item.label);
      const dataValuesDocumentManagement = chartDataDocumentManagement.map(item => item.value);

        new Chart(barChartDocumentManagement, {
          type: "bar",
          data: {
            labels: labelsDocumentManagement,
            datasets: [
              {
                backgroundColor: "rgb(200,39,57)",
                borderColor: "rgb(200,39,57)",
                data: dataValuesDocumentManagement,
              },
            ],
          },
          options: {
            legend: {
          display: false, // Ini akan menyembunyikan legenda
        },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              yAxes: [
                {
                  ticks: {
                    beginAtZero: true,
                  },
                },
              ],
            },
          },
        });
      }
    })
}
 


//PROCUREMENT LOGISTIC
$("#filterBtnProcurementLogistic").click(function() { 
      resetChartContainer('procurementLogisticContainer','barChartProcurementLogistic'); 
      getDataProcurementLogistic($("#start_date_procurement_logistic").val(),$("#end_date_procurement_logistic").val());
});

getDataProcurementLogistic('','');
function getDataProcurementLogistic(start_date, end_date){
  barChartProcurementLogistic = document.getElementById("barChartProcurementLogistic").getContext("2d");

  
  $.ajax({
      url: "{{route('dashboard-procurement-logistic')}}",
      data: {
        start_date: start_date,
        end_date: end_date,
      },
      method:"GET",
      success:function(response){
        // console.log(response);
        const chartDataProcurementLogisctic = response;

      // Mengonversi struktur data ke format Chart.js
      const labelsProcurementLogistic = chartDataProcurementLogisctic.map(item => item.label);
      const dataValuesProcurementLogistic = chartDataProcurementLogisctic.map(item => item.value);
      const colorProcurementLogistic = chartDataProcurementLogisctic.map(item => item.color);
        new Chart(barChartProcurementLogistic, {
          type: "bar",
          data: {
            labels: labelsProcurementLogistic,
            datasets: [
              {
                backgroundColor: colorProcurementLogistic,
                borderColor: colorProcurementLogistic,
                data: dataValuesProcurementLogistic,
              },
            ],
          },
          options: {
            legend: {
          display: false, // Ini akan menyembunyikan legenda
        },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              yAxes: [
                {
                  ticks: {
                    beginAtZero: true,
                  },
                },
              ],
            },
          },
        });
      }
    })
}
 

//DRAWINGS

$("#filterBtnDrawings").click(function() {
  const chartContainer = document.getElementById("drawing-container");
  chartContainer.innerHTML = "";
    let valid = true;
    if(valid) {
      getDataDrawings($("#start_date_drawing").val(),$("#end_date_drawing").val());
    }
  });

  getDataDrawings('','') 
  
  function getDataDrawings(start_date, end_date){

    $.ajax({
      url: "{{route('dashboard-drawings')}}",
      data: {
        start_date: start_date,
        end_date: end_date,
      },
      method:"GET",
      success:function(response){
        response.forEach((item) =>{
          createCardElement(item.jumlah,item.title)
        })
        
      }
    })
  }

  function createCardElement(param,title){
      
    const createCardElement = () => {
        const colDiv = document.createElement("div");
        colDiv.className = "col-6 col-md-6 col-lg-2";

        const cardDiv = document.createElement("div");
        cardDiv.className = "card";

        const cardBodyDiv = document.createElement("div");
        cardBodyDiv.className = "card-body p-3 text-center";

        const textEndDiv = document.createElement("div");
        textEndDiv.className = "text-end text-success";
        textEndDiv.innerHTML = "6% <i class='fa fa-chevron-up'></i>";

        const h1Element = document.createElement("div");
        h1Element.className = "h1 m-0";
        h1Element.textContent = param;

        const textMutedDiv = document.createElement("div");
        textMutedDiv.className = "text-muted mb-3 ";
        textMutedDiv.textContent = title.replace("Drawing","");

     
        cardBodyDiv.appendChild(h1Element);
        cardBodyDiv.appendChild(textMutedDiv);
        cardDiv.appendChild(cardBodyDiv);
        colDiv.appendChild(cardDiv);

        return colDiv;
    };

    // Menambahkan elemen ke dalam container tertentu di halaman
    const container = document.getElementById("drawing-container"); // Ganti dengan ID elemen target
    if (container) {
        container.appendChild(createCardElement());
    }
  }


//S_CURVE  
    

  $("#filterBtnSCurve").click(function() {
    let valid = true;    
    if(valid) {
      resetTable();
      resetChartContainer('sCurveContainer','sCurveChart'); 
  
      showScurve($("#tanggal_awal").val(),$("#tanggal_akhir").val(),$("#category").val());
    }
   
  });
  

  showScurve('', '','all')
  function showScurve(param_tgl_awal, param_tgl_akhir,param_category){
  
    $.ajax({
      url: "{{route('s-curve-chart-data')}}",
      data: {
        start_date: param_tgl_awal,
        end_date: param_tgl_akhir,
        category: param_category,
      },
      method:"GET",
      success:function(response){
       
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
          const td = document.createElement("td");
          td.innerText = value;
          tr.appendChild(td);
        });
  
        tableBody.appendChild(tr);
      }
  
    function curveChart(param){
      var multipleLineChart;
      
  
      multipleLineChart = document
      .getElementById('sCurveChart')
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

//PIE CHART CORRESPONDENCE
  document.getElementById("filterBtn").addEventListener('click', function(){
    resetChartContainer('pieContainer1','pieChart1'); 
    resetChartContainer('pieContainer2','pieChart2'); 
    resetChartContainer('pieContainer3','pieChart3'); 
    if($("#start_date").val() != "" && $("#end_date").val() != ""){
     
      PieChartProcess($("#start_date").val(),$("#end_date").val())
    }else{
      // console.log("DISANA")
      PieChartProcess("","")
    }
  })
  PieChartProcess("","")
  function PieChartProcess(start_date_param,end_date_param){
    // console.log(start_date_param, end_date_param)
    $.ajax({
      url: "{{ route('dashboard-pie-surat') }}",
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        start_date: $("#start_date").val(),
        end_date: $("#end_date").val()
      },
      success: function (response,color) {
        // console.log(response)
        if (response.status == 'ok'){
          
          response.data_pie_surat.map((item) =>{
           
            PieChart(item.id,item.color,item.value,item.legend,item.title)
          })
        }else{
          msg_swal = "Failed";
          color = "btn btn-danger";
          swal(msg_swal, {
                buttons: {
                  confirm: {
                    className: color,
                  },
                },
              });
            
        }
      },
      error: function (xhr) {
        alert('An error occurred: ' + xhr.responseText);
      }
    });
  }
  function PieChart(param_id,param_color, param_data, param_legend, param_title){
        var 
      pieChart1 = document.getElementById(param_id).getContext("2d");
      new Chart(param_id, {
  type: "pie",
  data: {
    datasets: [
      {
        data: param_data,
        backgroundColor: param_color,
        borderWidth: 0,
      },
    ],
    labels: param_legend,
  },
  
  options: {
    responsive: true,
    maintainAspectRatio: false,
    title: {
      display: true,
      text: param_title,
      fontSize: 12,
      fontColor: "#333",
    },
    legend: {
      position: "bottom",
      labels: {
        fontColor: "rgb(154, 154, 154)",
        fontSize: 11,
        usePointStyle: true,
        padding: 20,
      },
    },
    pieceLabel: {
      render: function (args) {
        return args.value; // Tampilkan jumlah data di label
      },
      fontColor: "white",
      fontSize: 14,
    },

    plugins: {
      datalabels: {
        color: 'white',
        font: {
          size: 14,
          weight: 'bold',
        },
        formatter: function(value, context) {
          return value;  // Menampilkan jumlah data saja
        }
      }
    },
    tooltips: false,  
    layout: {
      padding: {
        left: 20,
        right: 20,
        top: 20,
        bottom: 20,
      },
    },
  },
});
                    
   }


</script>
@endpush