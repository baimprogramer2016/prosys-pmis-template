@extends('layouts.app')

@section('content')
@push('top')
   <style>
  
    .txt{
        font-size:13px;
    }
    .card-color:hover{
        background-color: rgb(162, 217, 244);
    }
   </style>
@endpush
<div class="page-inner">
    <div
      class="d-flex align-items-left align-items-md-center flex-column flex-md-row  pb-2"
    >
      <div class="d-flex align-items-center gap-4">

        <h6 class="op-7 mb-2">Custom Report</h6>
       
      </div>
      <div class="ms-md-auto py-2 py-md-0">
       </div>
    </div>
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              {{-- <div class="col-icon">
                <div
                  class="icon-big text-center icon-primary bubble-shadow-small"
                >
                <i class="fas fa-pen-square"></i>
                </div>
              </div> --}}
              <div class="col col-stats ms-3 ms-sm-0 d-flex">
                <div class="form-group">
                    <div class="form-group">
                        {{-- <label for="report">Input Report</label> --}}
                        <div class="input-group">
                            
                          <input
                            type="text"
                            class="form-control"
                           
                            aria-label=""
                            name="report"
                            id="report"
                            placeholder="Masukan Nama Report"
                            aria-describedby="basic-addon1"
                          />
                          <button
                          class="btn btn-success "
                          type="button"
                        >
                          Create
                        </button>
                        </div>
                        {{-- <small id="emailHelp2" class="form-text text-muted"
                        >Report Akan terbentuk secara otomatis</small
                      > --}}
                      </div>
                 
                  </div>
              </div>
            
            </div>
          </div>
        </div>
      </div>    
        <div class="col-sm-12 col-md-12">
            <div class="d-flex align-items-center gap-4">

                <h6 class="op-7 mb-2">List Of Report</h6>
               
              </div>
             <div class="row">
               
                    
             
                <div class="col-sm-6 col-lg-3">
                    <div class="card p-3 card-color">
                      <div class="d-flex align-items-center">
                        <span class="stamp stamp-md bg-secondary me-3">
                          <i class="fa fa-folder"></i>
                        </span>
                        <div>
                          <h5 class="mb-1">
                            <span class="txt"
                              >Report Custom 1</span>
                          </h5>
                          {{-- <small class="text-muted">12 waiting payments</small> --}}
                        </div>
                      </div>
                    </div>
                </div>
            
             </div>
        </div>
    </div>
  </div>

  
@endsection

@push('bottom')
@endpush