<style>
 <style>
    #pdf-viewer-modal .modal-dialog {
        max-width: 90%;
        margin: 1.75rem auto;
    }
    #pdf-viewer-modal .modal-body {
        height: 80vh;
        overflow: hidden;
    }
    #pdf-canvas {
        width: 100%;
        height: 100%;
        border: 1px solid #ccc;
        display: block;
        object-fit: contain;
    }
</style>

</style>
<div class="modal-header">
    <h5 class="modal-title" id="modalTambahLabel">View Surat</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <canvas id="pdf-canvas"></canvas>
    <div id="pdf-viewer" style="height: 100%;"></div>


  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  </div>
  <script>
  

    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.13.216/pdf.worker.min.js';
     pdfUrl = "{{ asset('storage/surat/f3zQ8v8TavuumeBSLOJJHKG2nK6J5Opr5jcYXios.pdf') }}";  // URL PDF

    const container = document.getElementById('pdf-viewer');

    pdfjsLib.getDocument(pdfUrl).promise.then((pdf) => {
        for (let i = 1; i <= pdf.numPages; i++) {
            pdf.getPage(i).then((page) => {
                const viewport = page.getViewport({ scale: 1.2 });
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                container.appendChild(canvas);

                page.render({
                    canvasContext: context,
                    viewport: viewport,
                });
            });
        }
    });
</script>