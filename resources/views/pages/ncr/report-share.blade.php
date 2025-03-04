<div class="modal-header">

    <h5 class="modal-title" id="modalTambahLabel">Document Share</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <form id="formData">
      <div class="input-group">
        <input type="text" readonly class="form-control" id="pathInput" value="{{ url('storage/' . $document->path) }}">
        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">
          <i class="fas fa-copy me-2"></i>Copy
        </button>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  </div>
  
  <script>
  function copyToClipboard() {
    var copyText = document.getElementById("pathInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999); // Untuk mobile devices
    navigator.clipboard.writeText(copyText.value).then(function() {
      alert("Copied to clipboard");
    }, function() {
      alert("Failed to copy text.");
    });
  }
  </script>
  