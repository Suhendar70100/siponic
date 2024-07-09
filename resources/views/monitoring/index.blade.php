@extends("layouts.main")

@push('my-scripts')
    <script src="{{ asset('javascripts/monitoring/global.js') }}"></script>
@endpush

@section("content")
    <div id="cover-spin"></div>

    <div class="row gy-4">
        <div class="col-6">
            <div class="card h-100">
                <div class="d-flex align-items-end row">
                    <div class="col-12 order-2 order-md-1">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Pilih Device</h4>
                        </div>
                        <div class="card-body">
                            <label for="device">Pilih Device</label>
                            <select class="form-control select2" name="device" id="device">
                                <option value=""></option>
                                @foreach($devices as $item)
                                    <option value="{{ $item->id }}">{{ $item->guid }} - {{ $item->note }}</option>
                                @endforeach
                            </select>
                            <div class="mt-3">
                                <label for="select-month">Pilih Bulan</label>
                                <input type="month" class="form-control" id="select-month"/>
                            </div>
                            <button type="button" class="btn btn-primary mt-3 btn-filter">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 chart" id="totalAverageCard">
            <div class="card">
                <div class="card-body">
                    <div id="totalAverage"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-6 mb-4 chart" id="barPpm">
            <div class="card">
                <div class="card-body">
                    <div id="chartPpm"></div>
                </div>
            </div>
        </div>

        <div class="col-6 mb-4 chart" id="barWaterPh">
            <div class="card">
                <div class="card-body">
                    <div id="chartWaterPh"></div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4 chart" id="barTemperature">
            <div class="card">
                <div class="card-body">
                    <div id="chartTemperature"></div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4 chart" id="barHumidity">
            <div class="card">
                <div class="card-body">
                    <div id="chartHumidity"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
