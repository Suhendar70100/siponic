<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
      <ul class="menu-inner">
        <!-- Dashboards -->
        <li class="menu-item @if (Request::is('/')) active @endif ">
          <a href="{{ url('/') }}" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
            <div data-i18n="Dashboard">Dashboards</div>
          </a>
        </li>

        @if (auth()->user()->usertype === "admin")
        <li class="menu-item @if (Request::is('garden')) active @endif ">
          <a href="{{ route('garden') }}" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-sprout"></i>
            <div data-i18n="Perkebunan">Perkebunan</div>
          </a>
        </li>
        @endif

        <li class="menu-item @if (Request::is('device')) active @endif ">
          <a href="{{ route('device') }}" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-devices"></i>
            <div data-i18n="Perangkat">Perangkat</div>
          </a>
        </li>

        <li class="menu-item @if (Request::is('history')) active @endif ">
          <a href="{{ route('history') }}" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-history"></i>
            <div data-i18n="Histori">Histori</div>
          </a>
        </li>

        <li class="menu-item @if (Request::is('monitoring')) active @endif ">
          <a href="{{ route('monitoring') }}" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-monitor-dashboard"></i>
            <div data-i18n="Monitoring">Monitoring</div>
          </a>
        </li>

        <li class="menu-item @if (Request::is('information')) active @endif ">
          <a href="{{ route('information') }}" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-alert-circle-outline"></i>
            <div data-i18n="Informasi">Informasi</div>
          </a>
        </li>
      </ul>
    </div>
  </aside>