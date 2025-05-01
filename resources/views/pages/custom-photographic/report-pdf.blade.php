<div class="modal-body p-0">
    @if (in_array($document->ext, ['docx', 'doc']))
        <iframe id="docIframe"
            src="https://docs.google.com/gview?url={{ asset('storage/' . $document->path) }}&embedded=true"
            style="width: 100%; height: 100vh; border: none;" frameborder="0">
        </iframe>
    @elseif(in_array($document->ext, ['xls', 'xlsx', 'ppt', 'pptx']))
        <iframe id="excelIframe"
            src="https://view.officeapps.live.com/op/embed.aspx?src={{ asset('storage/' . $document->path) }}"
            style="width: 100%; height: 100vh; border: none;" frameborder="0">
        </iframe>
    @else
        <iframe id="pdfIframe" src="{{ asset('storage/' . $document->path) }}"
            style="width: 100%; height: 100vh; border: none;">
        </iframe>
    @endif
</div>
<div class="modal-footer d-flex justify-content-between">
    <div>
        <p>
            <b><u>Description </u>:</b> {{ $document->description }}
        </p>
    </div>
    <div>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div>
