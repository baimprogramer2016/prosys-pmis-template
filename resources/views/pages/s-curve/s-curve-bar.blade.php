@extends('layouts.app')

@section('content')
    @push('top')
        <style type="text/css">
            html,
            body {
                height: 100%;
                padding: 0px;
                margin: 0px;

            }

            table {
                font-size: 12px;
                width: 100%;
                border-radius: 4px;
                text-align: center;
                font-family: Arial, sans-serif;
            }

            th,
            td {
                border: 1px solid #a18d8d;
                padding: 8px;
            }

            th {
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

            #myBarChart {
                height: 400px !important;
                /* Ubah ini sesuai keinginan */
                width: 50hv;
            }
        </style>
    @endpush
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row  pb-2">
            <div class="d-flex align-items-center gap-4">

                <h6 class="op-7 mb-2">Schedule Management / Progress</h6>

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
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title">Progress</div>
                        <div class="d-flex align-items-center">

                            <label for="end_date" class="me-2 mb-0">Sampai Tanggal:</label>
                            <input type="date" id="tanggal_akhir" class="form-control form-control-sm me-3"
                                style="width: 150px;">

                            <button class="btn btn-primary btn-sm" id="filterBtn">Filter</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" id="chart-container-1">
                            <canvas id="myBarChart"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

    <script>
        $("#filterBtn").click(function() {
            let valid = true;

            if ($("#tanggal_akhir").val() == "") {
                $("#tanggal_akhir").addClass("is-invalid");
                valid = false
            }

            if (valid) {

                resetChartContainer();

                showChart($("#tanggal_akhir").val());
            }

        });


        function resetChartContainer() {
            const chartContainer = document.getElementById("chart-container-1");

            // Hapus semua elemen dalam chart container
            chartContainer.innerHTML = "";

            // Buat elemen canvas baru
            const newCanvas = document.createElement("canvas");
            newCanvas.setAttribute("id", "myBarChart");
            chartContainer.appendChild(newCanvas);
        }

        showChart('')

        function showChart(param_tgl_akhir) {

            $.ajax({
                url: "{{ route('s-curve-bar-data') }}",
                data: {
                    end_date: param_tgl_akhir
                },
                method: "GET",
                success: function(response) {
                    // console.log(response)
                    if (response.status == 'ok') {
                        curveChart(response)


                    } else {
                        alert("Terjadi Kesalahan")
                    }
                }
            })
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

        function curveChart(param) {
            var myMultipleBarChart;


            ctx = document
                .getElementById("myBarChart")
                .getContext("2d");


            var myBarChart = new Chart(ctx, {
                type: "bar",
                data: {
                    // labels: param.title,
                    datasets: [{
                            label: "Planned",
                            backgroundColor: "#f3545d",
                            borderColor: "#f3545d",
                            data: param.planned,
                        },
                        {
                            label: "Actual",
                            backgroundColor: "blue",
                            borderColor: "blue",
                            data: param.actual,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            enabled: true,
                        },
                        datalabels: {

                            anchor: 'end',
                            align: 'top',
                            color: '#000',
                            font: {
                                weight: 'bold',
                            },
                            formatter: function(value, context) {
                                const index = context.dataIndex;
                                const plannedValue = param.planned[index];
                                if (!plannedValue || plannedValue === 0) return "0%";
                                const percent = (value / plannedValue) * 100;
                                return percent.toFixed(1) + "%";
                            },
                        },
                    },
                    scaleShowValues: true,
                    scales: {

                        xAxes: [{
                                stacked: false, // Pastikan ini false agar bar tidak ditumpuk
                                barPercentage: 0.9, // Mengatur lebar setiap bar
                                categoryPercentage: 0.5, // Memberi jarak antar kategori
                            },

                        ],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                            },
                        }, ],
                    },

                },

            });


        }
    </script>
@endpush
