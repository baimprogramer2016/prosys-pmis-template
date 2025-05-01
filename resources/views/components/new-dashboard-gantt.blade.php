<script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
<link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">

<h5 style="text-align:left; margin: 20px;">Progress Milestone</h5>
<div id="gantt_content" style='width:100%; height:65%;'></div>
{{-- <p style="text-align:right; margin-top: 10px;margin-right:20px" class="fw-bold">Cut Off 6 Maret 2025</p> --}}
<script type="text/javascript">
    gantt.config.date_format = "%Y-%m-%d";

    // Konfigurasi tampilan Gantt chart per tahun dan bulan
    // Konfigurasi tampilan Gantt chart per tahun dan bulan
    gantt.config.scale_unit = "year"; // Menampilkan unit waktu utama per tahun
    gantt.config.date_scale = "%Y"; // Menampilkan tahun (misalnya, 2025)

    // Subscale menampilkan bulan dalam format angka di bawah tahun
    gantt.config.subscales = [{
            unit: "month",
            step: 1,
            date: "%m"
        } // Menampilkan bulan dalam angka (01, 02, 03, dst.)
    ];

    gantt.init("gantt_content");
    gantt.load("/api/new-dashboard-gantt");

    gantt.config.columns = [{
        name: "text",
        label: "Activity",
        width: 4,
        tree: true
    }];
    gantt.templates.task_text = function(start, end, task) {
        return `${task.raw_progress} %`;
    };
    gantt.config.min_column_width = 10; // default 70, ubah jadi lebih kecil
    gantt.config.scale_height = 50;

    gantt.render();

    // Pastikan task progress dimasukkan dalam bentuk yang benar (2.75)
</script>

<style>
    /* CSS untuk menampilkan teks dengan ellipsis jika terlalu panjang */
    .task-name-cell {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /*
    .gantt_scale_cell {
        background-color: rgb(195, 197, 215);
        color: #fff;
    } */


    .gantt_cell gantt_last_cell {
        background-color: rgb(42, 53, 138);
        color: #fff;
    }

    .gantt_task_line {
        background-color: #909497;
        /* Change background color */
        border-color: rgba(0, 0, 0, 0.25);
        /* Change border color */
    }

    .gantt_task_line .gantt_task_progress {
        background-color: rgb(42, 53, 138);
        /* Change progress bar color */
    }


    /* Menebalkan border tabel grid */
</style>
