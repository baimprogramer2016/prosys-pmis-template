<div class="col-sm-12 col-md-12">
    <div class="card">
        {{-- <div class="card-header d-flex flex-column flex-lg-row align-items-center justify-content-between">
            <div class="card-title fw-bold mb-2 mb-lg-0">Stargate Mineral Asia</div>
            <form action="{{ route('dashboard') }}">
                <div class="d-flex flex-wrap align-items-center w-70 justify-content-end">
                    <div class="me-3 mb-2 mb-lg-0 d-flex align-items-center">
                        <label for="category_new" class="me-2 mb-0 fw-bold">Category</label>
                        <select class="form-control form-control-sm" id="category_new" name="category_new">
                            <option value="all">All</option>
                            @foreach ($dataSubCategory as $item_sub_category)
                                <option value="{{ $item_sub_category->description }}">
                                    {{ $item_sub_category->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="me-3 mb-2 mb-lg-0 d-flex align-items-center">
                        <label for="tanggal_awal_new" class="me-2 mb-0 fw-bold">Tanggal Awal:</label>
                        <input type="date" id="tanggal_awal_new" name="tanggal_awal_new"
                            class="form-control form-control-sm" style="width: 150px;" value="{{ $minDate }}">
                    </div>
                    <div class="me-3 mb-2 mb-lg-0 d-flex align-items-center">
                        <label for="tanggal_akhir_new" class="me-2 mb-0 fw-bold">Tanggal Akhir:</label>
                        <input type="date" id="tanggal_akhir_new" name="tanggal_akhir_new"
                            class="form-control form-control-sm" style="width: 150px;" value="{{ $maxDate }}">
                    </div>
                    <div class="d-flex">
                        <button type="submit"class="btn btn-primary btn-sm" id="filterNew">Filter</button>
                    </div>
                </div>
            </form>
        </div> --}}
        <div class="card-body container-fluid p-0" style="height: 400px; width: 100%;">
            <div class="row h-100">
                <div class="col-md-12">
                    <x-new-dashboard-gantt />
                </div>
                <div class="col-md-12">

                </div>
            </div>

            {{-- <div class="new-dashboard" id="new-dashboard"> --}}
            {{-- <canvas id="sCurveChart" ></canvas> --}}

            {{-- </div> --}}

            {{-- <div class="table-wrapper bg-success" style="overflow-x: auto;">
          <table id="progressTable">
            <thead>
              <tr id="tableHeader"></tr>
            </thead>
            <tbody id="tableBody"></tbody>
          </table>
        </div> --}}

        </div>
    </div>
</div>
