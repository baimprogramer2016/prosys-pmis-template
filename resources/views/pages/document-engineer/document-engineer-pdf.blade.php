<div class="modal-body p-0">
  @if($document->ext === 'docx')

  <!-- Loading Indicator -->
  <div id="loadingx" class="w-100 h-100 d-flex align-items-center justify-content-center">
    <strong>Loading...</strong>
  </div>

  <!-- Iframe untuk Google Docs Viewer (Awalnya disembunyikan) -->
  <iframe id="docIframe"
        src="https://docs.google.com/gview?url={{ asset('storage/' . $document->path) }}&embedded=true" 
        style="width:100%; height:600px; display: none;" 
        frameborder="0"
        onload="document.getElementById('loadingx').style.display='none'; this.style.display='block';">
  </iframe>

  @else
    <iframe id="pdfIframe" src="{{ asset('storage/' . $document->path)}}" style="width: 100%; height: 100vh; border: none;"></iframe>
  @endif
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
