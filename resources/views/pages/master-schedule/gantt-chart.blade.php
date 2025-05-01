@extends('layouts.app')

@section('content')
    @push('top')
        <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
        <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">

        <style type="text/css">
            html,
            body {
                height: 100%;
                padding: 0px;
                margin: 0px;
                /* overflow: hidden; */
            }

            .gantt_grid_head_cell {
                /* background-color: orange; Warna biru */
                /* color: white; */
                font-weight: 600;
                text-align: center;
                border: none;
                font-size: 10px;
            }

            .gantt_scale_cell {
                /* background-color: orange; Warna biru */
                /* color: white; */
                font-weight: 600;
                text-align: center;
                border: none;
                font-size: 10px;
            }

            .gantt_scale_row {
                background-color: #7db2eb;
                /* Biru untuk seluruh baris header timeline */
                color: white;
                font-weight: bold;
                text-align: center;
                font-size: 10px;

            }

            .gantt_grid_data,
            .gantt_task_row,
            .gantt_scale_row {
                font-size: 11px;
                /* Atur ukuran sesuai kebutuhan */
            }

            .gantt_task_content {
                font-size: 10px;
                /* Ukuran teks di dalam task bar */
            }


            .status-complete {
                padding: 5px;
                font-size: 10px;
                font-weight: 400;
                text-align: center;
                border-radius: 3px;
                color: white;
                background: green;
            }

            .status-inprogress {
                padding: 5px;
                font-size: 10px;
                font-weight: 400;
                text-align: center;
                border-radius: 3px;
                color: white;
                background: blue;
            }

            .status-notstarted {
                background: #6c757d;
                /* Abu-abu */
                font-weight: 500;
                color: #fff;
                padding: 2px;
                border-radius: 10%;
            }
        </style>
    @endpush
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row  pb-2">
            <div class="d-flex align-items-center gap-4">

                <h6 class="op-7 mb-2">Master Schedule / Progress</h6>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0 d-flex">
                                <div class="numbers">
                                    <h4 class="card-title">Progress</h4>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-2">
                            <div class="col d-flex justify-content-center">
                                <input type="text" id="ganttSearch" placeholder="Search task"
                                    class="form-control form-control-sm" style="height: 32px;">
                                {{-- <button type="button" class="btn btn-primary btn-sm">Search</button> --}}
                            </div>
                        </div>
                        <div id="gantt_here" style='margin-top:20px;width:100%; height:100vh;'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')
    <script type="text/javascript">
        // gantt.config.layout = {
        //     css: "gantt_container",
        //     rows: [
        //         {
        //             cols: [
        //                 {
        //                     view: "grid",
        //                     scrollX: "scrollHor",
        //                     scrollY: "scrollVer",
        //                     width: () => document.body.offsetWidth * 0.5 // Lebar setengah dari viewport
        //                 },
        //                 { resizer: true, width: 1 },
        //                 {
        //                     view: "timeline",
        //                     scrollX: "scrollHor",
        //                     scrollY: "scrollVer"
        //                 }
        //             ]
        //         },
        //         { view: "scrollbar", id: "scrollHor" },
        //         { view: "scrollbar", id: "scrollVer" }
        //     ]
        // };

        gantt.config.date_format = "%Y-%m-%d"; // Format tanggal tanpa waktu
        gantt.config.scales = [{
                unit: "year",
                step: 1,
                format: "%Y"
            }, // Baris pertama: Tahun
            {
                unit: "day",
                step: 1,
                format: "%d %M"
            } // Baris kedua: Bulan
        ];

        gantt.config.columns = [{
                name: "text",
                label: "Task Name",
                width: 200,
                tree: true
            },
            {
                name: "start_date",
                label: "Start Date",
                align: "center",
                width: 100
            },
            {
                name: "duration",
                label: "Duration",
                align: "center",
                width: 80
            },
            {
                name: "progress",
                label: "%",
                template: (task) => `${Math.round(task.progress * 100)}%`,
                align: "center",
                width: 50
            },
            {
                name: "status",
                label: "Status",
                template: (task) => {
                    let statusText = task.progress >= 1 ? "Complete" : task.progress > 0 ? "In Progress" :
                        "Not Started";
                    let statusClass = task.progress >= 1 ? "status-complete" : task.progress > 0 ?
                        "status-inprogress" : "status-notstarted";
                    return `<span class='${statusClass}'>${statusText}</span>`;
                },
                align: "center",
                width: 120
            }
        ];


        gantt.init("gantt_here");
        gantt.config.date_format = "%Y-%m-%d";
        gantt.load("api/data");

        document.getElementById("ganttSearch").addEventListener("keyup", function() {
            var searchTerm = this.value.toLowerCase();

            gantt.clearAll();
            var apiUrl = searchTerm ?
                `api/data?search=${encodeURIComponent(searchTerm)}` :
                "api/data";

            gantt.load(apiUrl);
        });
    </script>
@endpush
