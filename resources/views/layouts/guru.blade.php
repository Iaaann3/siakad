<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Guru - Flexy Admin</title>

  <!-- Favicon & Styles -->
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets_/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('assets_/css/styles.min.css') }}" />
</head>

<body>
  <!-- Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <!-- App Topstrip -->
    <div class="app-topstrip bg-dark py-6 px-3 w-100 d-lg-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center justify-content-center gap-5 mb-2 mb-lg-0">
        <a class="d-flex justify-content-center" href="#">
          <img src="{{ asset('assets_/images/logos/logo-wrappixel.svg') }}" alt="Logo" width="150">
        </a>
      </div>
      <div class="d-lg-flex align-items-center gap-2">
        <h3 class="text-white mb-2 mb-lg-0 fs-5 text-center">Check Flexy Premium Version</h3>
        <div class="d-flex align-items-center justify-content-center gap-2">
          <div class="dropdown d-flex">
            <a class="btn btn-primary d-flex align-items-center gap-1" href="javascript:void(0)" id="drop4"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="ti ti-shopping-cart fs-5"></i>
              Buy Now
              <i class="ti ti-chevron-down fs-5"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar Start -->
    @include('layouts.components.guru.sidebar')
    <!-- Sidebar End -->

    <!-- Main Content -->
    <div class="body-wrapper">
      <!-- Navbar -->
      @include('layouts.components.guru.navbar')
      <!-- End Navbar -->

      <div class="body-wrapper-inner">
        <div class="container-fluid">

          <!-- ====== Tempat isi konten ====== -->
          @yield('content')

          <!-- Footer -->
          <div class="py-6 px-6 text-center">
            <p class="mb-0 fs-4">
              Design and Developed by 
              <a href="#" class="pe-1 text-primary text-decoration-underline">Wrappixel.com</a> 
              Distributed by 
              <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
            </p>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('assets_/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets_/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets_/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('assets_/js/app.min.js') }}"></script>
  <script src="{{ asset('assets_/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets_/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('assets_/js/dashboard.js') }}"></script>

  <!-- Iconify -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

  <!-- Script Tambahan Per Halaman -->
  @yield('scripts')

</body>

</html>
