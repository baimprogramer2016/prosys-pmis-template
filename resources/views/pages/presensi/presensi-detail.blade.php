<div class="modal-body">

    {{-- Flex container untuk gambar --}}
    <h2 class="text-center">Detail</h2>
    <div class="d-flex flex-column flex-md-row justify-content-center align-items-start gap-4 text-center">
        {{-- Clock In --}}
        @if ($document->photo_in)
            <div class="flex-fill">
                <h6 class="fw-bold">Clock In</h6>
                <img src="{{ asset('storage/' . $document->photo_in) }}" alt="Clock In"
                    class="img-fluid rounded border mx-auto d-block" style="max-height: 300px;">
            </div>
        @endif
        {{-- Clock Out --}}
        @if ($document->photo_out)
            <div class="flex-fill">
                <h6 class="fw-bold">Clock Out</h6>

                <img src="{{ asset('storage/' . $document->photo_out) }}" alt="Clock Out"
                    class="img-fluid rounded border mx-auto d-block" style="max-height: 300px;">
            </div>
        @endif


    </div>

    {{-- List Break & Work Start --}}
    <div class="mt-4">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Break Start</th>
                    <th scope="col">Break End</th>
                </tr>
            </thead>
            <tbody>
                @if ($document->r_presensi_break && $document->r_presensi_break->count() > 0)
                    @foreach ($document->r_presensi_break as $item_break)
                        <tr>
                            <td>#{{ $loop->iteration }}</td>
                            <td>{{ $item_break->break_time }}</td>
                            <td>{{ $item_break->work_time }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="text-center text-muted fst-italic">No break record available</td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>


</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
