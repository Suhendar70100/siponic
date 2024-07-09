<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
  data-assets-path="{{asset('assets')}}/" data-template="horizontal-menu-template-no-customizer">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Siponic</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{asset('/assets/img/favicon/favicon.ico')}}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="{{asset('/assets/vendor/fonts/materialdesignicons.css')}}" />
  <link rel="stylesheet" href="{{asset('/assets/vendor/fonts/fontawesome.css')}}" />
  <!-- Menu waves for no-customizer fix -->
  <link rel="stylesheet" href="{{asset('/assets/vendor/libs/node-waves/node-waves.css')}}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{asset('/assets/vendor/css/rtl/core.css')}}" />
  <link rel="stylesheet" href="{{asset('/assets/vendor/css/rtl/theme-default.css')}}" />
  <link rel="stylesheet" href="{{asset('/assets/css/demo.css')}}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
  <link rel="stylesheet" href="{{asset('/assets/vendor/libs/typeahead-js/typeahead.css')}}" />
  <!-- Vendor -->
  <link rel="stylesheet" href="{{asset('/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="{{asset('/assets/vendor/css/pages/page-auth.css')}}" />
  <!-- Helpers -->
  <script src="{{asset('/assets/vendor/js/helpers.js')}}"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="{{asset('/assets/js/config.js')}}"></script>
  <style>
    .navbar-logo {
            width: 400px;
            height: 100px;
            object-fit: contain;
        }
  </style>
</head>

<body>
  <!-- Content -->

  <div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-4">
        <!-- Login -->
        <div class="card p-2">
          <!-- Logo -->
          <div class="app-brand justify-content-center mt-5">
            <a href="index.html" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">
                <span style="color: #666cff">
                  <img src="{{ asset('assets/img/logo3.png') }}" alt="logo" class="navbar-logo" />
                </span>
              </span>
            </a>
          </div>
          <!-- /Logo -->

          <div class="card-body mt-2">
            @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif

            <form id="formAuthentication" method="POST" action="{{ route('login') }}">
              @csrf
              <div class="form-floating form-floating-outline mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                  autofocus />
                <label for="email">Email</label>
              </div>
              <div class="mb-3">
                <div class="form-password-toggle">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input type="password" id="password" class="form-control" name="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password" />
                      <label for="password">Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer" id="toggle-password">
                      <i class="mdi mdi-eye-off-outline" id="toggle-icon"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
              </div>
            </form>
          </div>
        </div>
        <!-- /Login -->
        <img alt="mask" src="{{asset('/assets/img/illustrations/auth-basic-login-mask-light.png')}}"
          class="authentication-image d-none d-lg-block"
          data-app-light-img="illustrations/auth-basic-login-mask-light.png"
          data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
      </div>
    </div>
  </div>

  <!-- / Content -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="{{asset('/assets/vendor/libs/jquery/jquery.js')}}"></script>
  <script src="{{asset('/assets/vendor/libs/popper/popper.js')}}"></script>
  <script src="{{asset('/assets/vendor/js/bootstrap.js')}}"></script>
  <script src="{{asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
  <script src="{{asset('/assets/vendor/libs/node-waves/node-waves.js')}}"></script>

  <script src="{{asset('/assets/vendor/libs/hammer/hammer.js')}}"></script>
  <script src="{{asset('/assets/vendor/libs/i18n/i18n.js')}}"></script>
  <script src="{{asset('/assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>

  <script src="{{asset('/assets/vendor/js/menu.js')}}"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="{{asset('/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
  <script src="{{asset('/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
  <script src="{{asset('/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>

  <!-- Main JS -->
  <script src="{{asset('/assets/js/main.js')}}"></script>

  <!-- Page JS -->
  <script src="{{asset('/assets/js/pages-auth.js')}}"></script>
</body>

</html>