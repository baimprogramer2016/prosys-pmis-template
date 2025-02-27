<div class="modal-body p-0">
  @if(in_array($document->ext, ['docx', 'doc']))
    <iframe id="docIframe" src="https://docs.google.com/gview?url={{ asset('storage/' . $document->path)}}&embedded=true"
            style="width: 100%; height: 100vh; border: none;" frameborder="0">
    </iframe>
  @else
    <iframe id="pdfIframe" src="{{ asset('storage/' . $document->path)}}" style="width: 100%; height: 100vh; border: none;"></iframe>
  @endif
</div>

<div class="modal-footer">
  <span id="loadingText">Sedang Memuat Dokumen...</span> <!-- Indikator Loading -->
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function () {
      var iframe = document.getElementById("docIframe") || document.getElementById("pdfIframe");
      var loadingText = document.getElementById("loadingText");

      if (iframe) {
          iframe.onload = function () {
              loadingText.style.display = "none"; // Hilangkan teks "Sedang Memuat Dokumen..."
          };
      }
  });
</script>
