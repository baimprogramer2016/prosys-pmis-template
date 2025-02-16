@extends('layouts.app')

@section('content')
@push('top')
    
<style type="text/css">
    html, body{
        height:100%;
        padding:0px;
        margin:0px;
        
    }
</style>
@endpush
<div class="page-inner">
 
    <div class="row">
   
      <div class="col-sm-12 col-md-12">
      <div class="card ">
               
        <div class="card-body">
            <iframe src="{{ url('laravel-filemanager?type=file') }}" style="width: 100%; height: 100vh; border: none;"></iframe>

        </div>
      </div> 
    </div>
    </div>
  </div>

  <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
     
      </div>
    </div>
  </div>
  
@endsection

@push('bottom')
@endpush