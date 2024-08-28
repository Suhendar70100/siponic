@extends("layouts.main")

@section('content')
<div class="row gy-4 mb-4">
  <!-- Sales Overview-->
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header">
        <div class="d-flex align-items-center">
          <div class="card-body">
          <h4 class="mb-2">Pilih Perangkat</h4>
            <select class="form-control select2" name="device" id="device">
              <option value=""></option>
              @foreach($devices as $item)
                <option value="{{ $item->id }}">{{ $item->guid }} - {{ $item->plants }}</option>
              @endforeach
            </select>
            <button type="button" class="btn btn-primary mt-3 btn-filter">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Sales Overview-->

  <!-- Ratings -->
  <div class="col-lg-3 col-sm-6 chart">
    <div class="card h-100">
        <div class="card-body text-center">
            <h4 class="card-title">Suhu</h4>
            <div id="temperatureChart" class="d-flex align-items-center"></div>
        </div>
    </div>
  </div>

  <!--/ Ratings -->

  <!-- Sessions -->
  <div class="col-lg-3 col-sm-6 chart">
    <div class="card h-100">
        <div class="card-body text-center">
            <h4 class="card-title">Kelembaban</h4>
            <div id="humidityChart" class="d-flex align-items-center"></div>
        </div>
    </div>
  </div>
  <!--/ Sessions -->

  <!-- Weekly Sales with bg-->
  <div class="col-lg-6 chart">
    <div class="swiper-container swiper-container-horizontal swiper text-bg-primary" id="swiper-weekly-sales-with-bg">
      <div class="swiper-wrapper">
        @forelse ($info as $item)
        <div class="swiper-slide">
            <div class="row">
                <div class="col-12">
                    <h5 class="text-white mb-2">Informasi Tanaman</h5>
                    <div class="d-flex align-items-center gap-2">
                        <small>{{ $item['device']['guid'] }}</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                        <h6 class="text-white mt-0 mt-md-3 mb-3 py-1">{{ $item['device']['plants'] }}</h6>
                        <div class="row">
                            <div class="col-12">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex mb-3 align-items-center">
                                        <p class="mb-0 me-2 weekly-sales-text-bg-primary">Penyemaian</p>
                                        <p class="mb-0">{{ $item['seeding_start_date'] }}</p>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <p class="mb-0 me-2 weekly-sales-text-bg-primary">Panen</p>
                                        <p class="mb-0">{{ $item['harvest_date'] }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="swiper-slide">
            <div class="row">
                <div class="col-12">
                    <h5 class="text-white mb-2">Informasi Tanaman</h5>
                    <div class="d-flex align-items-center gap-2">
                        <small>-</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                        <h6 class="text-white mt-0 mt-md-3 mb-3 py-1">-</h6>
                        <div class="row">
                            <div class="col-12">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex mb-3 align-items-center">
                                        <p class="mb-0 me-2 weekly-sales-text-bg-primary">Penyemaian</p>
                                        <p class="mb-0">-</p>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <p class="mb-0 me-2 weekly-sales-text-bg-primary">Panen</p>
                                        <p class="mb-0">-</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
    
        
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
  <!--/ Weekly Sales with bg-->

  <!-- Total Visits -->
  <div class="col-lg-3 col-sm-6 chart">
    <div class="card h-100">
        <div class="card-body text-center">
            <h4 class="card-title">PPM</h4>
            <div id="ppmChart" class="d-flex align-items-center"></div>
        </div>
    </div>
  </div>
  <!--/ Total Visits -->

  <!-- Sales This Months -->
  <div class="col-lg-3 col-sm-6 chart">
    <div class="card h-100">
        <div class="card-body text-center">
            <h4 class="card-title">Ph Air</h4>
            <div id="waterPhChart" class="d-flex align-items-center"></div>
        </div>
    </div>
  </div>
  <!--/ Sales This Months -->
  <div class=" col-md-6 monitoring" id="">
    <div class="card h-100">
        <div class="card-body">
            <div id="realTimeChart1"></div>
        </div>
    </div>
  </div>
  <div class=" col-md-6 monitoring" id="">
    <div class="card h-100">
        <div class="card-body">
            <div id="realTimeChart2"></div>
        </div>
    </div>
  </div>
</div>
@endsection

@push('my-scripts')
  <script src="{{asset('/assets/js/dashboards-ecommerce.js')}}"></script>
  <script src="{{ asset('javascripts/dashboard/global.js') }}"></script>
@endpush