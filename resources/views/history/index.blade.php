@extends("layouts.main")

@section("content")
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold mb-4"><span class="text-muted fw-light">Histori /</span> Histori Sensor</h4>
        <!-- Column Search -->
        <div class="card">
            <div class="card-datatable table-responsive text-nowrap">
                <table class="dt-column-search table table-bordered">
                    <thead>
                    <tr>
                        <th>GUID</th>
                        <th>Ph Air</th>
                        <th>Suhu</th>
                        <th>Kelembaban</th>
                        <th>Nilai PPM</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Column Search -->
    </div>
@endsection
@push("my-scripts")
    <script src="{{asset('javascripts/history/global.js')}}"></script>
@endpush