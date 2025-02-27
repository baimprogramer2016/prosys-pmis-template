
<div class="modal-body p-0">

  @if($document->ext === 'doxc')
    <iframe src="https://docs.google.com/gview?url={{ asset('storage/' . $document->path)}}&embedded=true" 
      style="width: 100%; height: 100vh; border: none;" frameborder="0">
    </iframe>
  @else
    <iframe id="pdfIframe" src="{{ asset('storage/' . $document->path)}}" style="width: 100%; height: 100vh; border: none;"></iframe>
  @endif
  

</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
