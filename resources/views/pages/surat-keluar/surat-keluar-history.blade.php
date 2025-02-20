
<div class="modal-body p-0">

  <table class="table table-bordered" id="myTable">
    <thead>
      <tr>
        <th  class="bg-th">Download</th>
        <th  class="bg-th">Document Number</th>
        <th  class="bg-th">Title</th>
        <th  class="bg-th">Version</th>
        <th  class="bg-th">Recipient</th>
        <th  class="bg-th">Attn</th>
        <th  class="bg-th">Hardcopy</th>
        <th  class="bg-th">Email</th>
        <th  class="bg-th">Category</th> 
        <th  class="bg-th">Date</th>
      </tr>
    </thead>
    <tbody>

     @foreach($documents as $document)
     <tr>
      <td><u><a class="dropdown-item text-success" href="{{ asset('storage/' . $document->path)}}" download>Download</a></u></td>
      <td>{{ $document->document_number}}</td>
      <td>{{ $document->description}}</td>
      <td>{{ $document->version}}</td>
      <td>{{ $document->recipient}}</td>
      <td>{{ $document->attn}}</td>
      <td>{{ ($document->hardcopy == 1) ? 'Done' : ""}}</td>
      <td>{{ ($document->email == 1) ? 'Done' : ""}}</td>
      <td>{{ $document->category}}</td>
      <td>{{ $document->tanggal}}</td>
      
    </tr>
     @endforeach
    </tbody>
  </table>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
