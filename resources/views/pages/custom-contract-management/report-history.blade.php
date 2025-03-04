
<div class="modal-body p-0">

  <table class="table table-bordered" id="myTable">
    <thead>
      <tr>
        <th  class="bg-th">Download</th>
        <th  class="bg-th">Contract No</th>
        <th  class="bg-th">Title</th>          
        <th  class="bg-th">Description</th>          
        <th  class="bg-th">Date</th>
        <th  class="bg-th">Extension</th>
      </tr>
    </thead>
    <tbody>

     @foreach($documents as $document)
     <tr>
      <td><u><a class="dropdown-item text-success" href="{{ asset('storage/' . $document->path)}}" download>Download</a></u></td>
      <td>{{ $document->no_contract}}</td>
      <td>{{ $document->title}}</td>
      <td>{{ $document->description}}</td>

      <td>{{ $document->created_at}}</td>
      <td>{{ $document->ext}}</td>
      
    </tr>
     @endforeach
    </tbody>
  </table>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
