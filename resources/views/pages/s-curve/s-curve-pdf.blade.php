<div class="modal-body p-0">
    @if ($path == null)
        <div>
            <p>No file uploaded</p>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    @else
        @if (in_array($ext, ['docx', 'doc']))
            <iframe id="docIframe" src="https://docs.google.com/gview?url={{ asset('storage/' . $path) }}&embedded=true"
                style="width: 100%; height: 100vh; border: none;" frameborder="0">
            </iframe>
        @elseif(in_array($ext, ['xls', 'xlsx', 'ppt', 'pptx']))
            <iframe id="excelIframe"
                src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('storage/' . $path) }}"
                style="width: 100%; height: 100vh; border: none;" frameborder="0">
            </iframe>
        @else
            <iframe id="pdfIframe" src="{{ asset('storage/' . $path) }}"
                style="width: 100%; height: 100vh; border: none;">
            </iframe>
        @endif
    @endif
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
