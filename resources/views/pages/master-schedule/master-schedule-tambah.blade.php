<div class="modal-header">
    <h5 class="modal-title" id="modalTambahLabel">Tambah Data</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <form action="">
      <div class="mb-3">
        <label for="activity" class="form-label">Activity </label>
        <input required type="text" class="form-control" id="activity" name="activity" placeholder="Enter activity name">
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Parent</label>
        <select class="form-select" id="parent" name="parent">
          @if($task_list)
          <option value="{{ $task_list->id}}">{{ $task_list->text }} </option>
          @endif
            <option value="0">---Utama---</option>
            @foreach($data_parent as $parent)
          
            <option value="{{ $parent->id }}">{{ $parent->text }}</option>
            @endforeach
        </select>
      </div>
      
      <div class="mb-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" class="form-control" id="start_date" name="start_date">
      </div>
      <div class="mb-3">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control" id="end_date" name="end_date">
      </div>
      <div class="mb-3">
        <label for="progress" class="form-label">Progress (%)</label>
        <input type="number" class="form-control" id="progress" name="progress" min="0" max="100" placeholder="Enter progress">
      </div>
      
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="saveData">Save</button>
  </div>
  <script>
    $(document).ready(function() {
    $('#status').select2({
        placeholder: 'Select a status',
        allowClear: true
    });
    });

    $(document).ready(function() {
      $("#saveData").on("click", function() {
        // Reset error messages
        $(".is-invalid").removeClass("is-invalid");
        let valid = true;
  
        let activity = $("#activity").val().trim();
        let startDate = $("#start_date").val();
        let endDate = $("#end_date").val();
        let progress = $("#progress").val()
        let parent = $("#parent").val()
  
        // Validasi Activity
        if (activity === "") {
          $("#activity").addClass("is-invalid");
          valid = false;
        }
  
        // Validasi Start Date
        if (startDate === "") {
          $("#start_date").addClass("is-invalid");
          valid = false;
        }
  
        // Validasi End Date
        if (endDate === "") {
          $("#end_date").addClass("is-invalid");
          valid = false;
        } else if (startDate !== "" && new Date(startDate) > new Date(endDate)) {
          $("#end_date").addClass("is-invalid");
          valid = false;
        }
  
        // Validasi Progress
        if (progress === "" || progress < 0 || progress > 100) {
          $("#progress").addClass("is-invalid");
          valid = false;
        }
  
        let duration = 0;
        if (startDate !== "" && endDate !== "") {
            const start = new Date(startDate);
            const end = new Date(endDate);
            duration = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) ;  // +1 agar termasuk hari pertama
        }
        // Submit via AJAX jika valid
        if (valid) {
          let formData = {
            activity: activity,
            start_date: startDate,
            end_date: endDate,
            progress: progress,
            duration: duration,
            parent:parent,
            _token: "{{ csrf_token() }}",
          };
  
          $.ajax({
            url: "{{ route('master-schedule-store') }}",  // Sesuaikan route-nya
            type: "POST",
            data: formData,
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
              alert("Data successfully saved.");
              $("#modalTambah").modal('hide');
              window.location.reload();
            },
            error: function(xhr) {
              alert("An error occurred: " + xhr.responseText);
            }
          });
        }
      });
    });
  </script>
  