<div class="modal-body p-0">
  <!-- Loading Indicator -->
  <div id="loading" class="w-100 h-100 d-flex align-items-center justify-content-center">
    <strong>Loading...</strong>
  </div>

  @if(in_array($document->ext,['docx','doc']))
    <iframe id="docIframe" 
            src="https://docs.google.com/gview?url={{ asset('storage/' . $document->path) }}&embedded=true" 
            style="width:100%; height:600px; display: none;" 
            frameborder="0"
            onload="hideLoading()">
    </iframe>
  @else
    <iframe id="pdfIframe" 
            src="{{ asset('storage/' . $document->path)}}" 
            style="width: 100%; height: 100vh; border: none; display: none;" 
            onload="hideLoading()">
    </iframe>
  @endif
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>

<script>
  function hideLoading() {
    document.getElementById("loading").style.display = "none"; // Sembunyikan loading
    
    var docIframe = document.getElementById("docIframe");
    var pdfIframe = document.getElementById("pdfIframe");

    // Tampilkan iframe jika ditemukan
    if (docIframe) {
        docIframe.style.display = "block";
    }
    if (pdfIframe) {
        pdfIframe.style.display = "block";
    }
  }
</script>

