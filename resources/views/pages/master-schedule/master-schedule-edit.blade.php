<div class="modal-header">

    <h5 class="modal-title" id="modalTambahLabel">Update Data</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <form id="formData">
      <input type="hidden" id="data_id" name="data_id" value="{{ $data_parent->id }}"> <!-- Hidden input for ID -->
      <div class="mb-3">
        <label for="activity" class="form-label">Activity</label>
        <input type="text" class="form-control" id="activity" name="activity" placeholder="Enter activity name" value="{{ $data_parent->text}}">
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Parent</label>
        <select class="form-select" id="parent" name="parent">
          @if($data_parent->r_parent)
            <option value="{{$data_parent->parent}}"> {{ $data_parent->r_parent->text }} </option>
          @endif
            <option value="0">--- Utama ---</option>
          @foreach($parent_list as $parent)
            <option value="{{ $parent->id }}">{{ $parent->text }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" class="form-control" id="start_date" name="start_date"  value="{{ \Carbon\Carbon::parse($data_parent->start_date)->format('Y-m-d') }}">
      </div>
      <div class="mb-3">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ \Carbon\Carbon::parse($data_parent->end_date)->format('Y-m-d') }}">
      </div>
      <div class="mb-3">
        <label for="progress" class="form-label">Progress (%)</label>
        <input type="number" class="form-control" id="progress" name="progress" min="0" max="100" placeholder="Enter progress"  value="{{ $data_parent->progress * 100}}">
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="updateData">Update</button>
  </div>
  
  <script>
  
    // Update data
    $("#updateData").on("click", function() {
      $(".is-invalid").removeClass("is-invalid");
      let valid = true;
  
      let activity = $("#activity").val().trim();
      let startDate = $("#start_date").val();
      let endDate = $("#end_date").val();
      let progress = $("#progress").val();
      let parent = $("#parent").val();
      let dataId = $("#data_id").val();
  
      if (activity === "") {
        $("#activity").addClass("is-invalid");
        valid = false;
      }
      if (startDate === "") {
        $("#start_date").addClass("is-invalid");
        valid = false;
      }
      if (endDate === "" || (startDate !== "" && new Date(startDate) > new Date(endDate))) {
        $("#end_date").addClass("is-invalid");
        valid = false;
      }
      if (progress === "" || progress < 0 || progress > 100) {
        $("#progress").addClass("is-invalid");
        valid = false;
      }
  
      let duration = 0;
      if (startDate !== "" && endDate !== "") {
        const start = new Date(startDate);
        const end = new Date(endDate);
        duration = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
      }
  
      if (valid) {
        let formData = {
          id: dataId,
          activity: activity,
          start_date: startDate,
          end_date: endDate,
          progress: progress,
          duration: duration,
          parent: parent,
          _token: "{{ csrf_token() }}",
        };
  
        $.ajax({
          url: "{{ route('master-schedule-update', ':id') }}".replace(':id', dataId), // Ganti dengan route yang sesuai
    
          type: "POST",  // HTTP PUT untuk update
          data: formData,
          success: function(response) {
            alert("Data successfully updated.");
            $("#modal").modal('hide');
            window.location.reload();
          },
          error: function(xhr) {
            alert("An error occurred: " + xhr.responseText);
          }
        });
      }
    });
  </script>
  