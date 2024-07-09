@include("layouts.header")
<!-- Layout wrapper -->
<div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
    <div class="layout-container">
        <!-- Menu -->
        @include("layouts.navbar")
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Navbar -->

                @include("layouts.menu")

                <div class="container-xxl flex-grow-1 container-p-y">
                    @yield("content")
                </div>
                <!-- / Content -->

                <!-- Footer -->
                @include("layouts.footer")
