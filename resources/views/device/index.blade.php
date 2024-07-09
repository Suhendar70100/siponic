@extends("layouts.main")

@section("content")
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold mb-4"><span class="text-muted fw-light">Pengaturan /</span> Perangkat</h4>
        <!-- Column Search -->
        <div class="card">
            <div class="d-flex justify-content-between me-3 align-items-center">
                <h5 class="card-header">Daftar Perangkat</h5>
                <button type="button"
                class="btn btn-primary" id="buttonAdd"><i class="mdi mdi-plus"></i>Tambah
                </button>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table table-bordered dataTable">
                    <thead>
                    <tr>
                        <th>GUID</th>
                        <th>Nama Perkebunan</th>
                        <th>Max PPM (Nutrisi)</th>
                        <th>Min PPM (Nutrisi)</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Column Search -->
    </div>
    @include('device.modal')
@endsection
@push("my-scripts")
    <script src="{{asset('javascripts/device/global.js')}}"></script>
@endpush