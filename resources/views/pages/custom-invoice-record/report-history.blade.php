
<div class="modal-body p-0">

  <table class="table table-bordered" id="myTable">
    <thead>
      <tr>
        <th  class="bg-th">Download</th>
        <th  class="bg-th">No. Invoice</th>
        <th  class="bg-th">Description</th>
        <th  class="bg-th">Invoice Date</th>
        <th  class="bg-th">Status</th>
        <th  class="bg-th">Date</th>
        <th  class="bg-th">Extension</th>
      </tr>
    </thead>
    <tbody>

     @foreach($documents as $document)
     <tr>
      <td><u><a class="dropdown-item text-success" href="{{ asset('storage/' . $document->path)}}" download>Download</a></u></td>
      <td>{{ $document->no_invoice}}</td>
      <td>{{ $document->description}}</td>
       <td>{{ $document->invoice_date}}</td>
       <td>{{ $document->status}}</td>
      <td>{{ $document->ext}}</td>
      
    </tr>
     @endforeach
    </tbody>
  </table>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
