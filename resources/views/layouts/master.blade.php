<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title')</title>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('kaiadmin-lite-1.2.0/assets/img/logo-usb1.jpg') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('kaiadmin-lite-1.2.0') }}/assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('kaiadmin-lite-1.2.0') }}/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('kaiadmin-lite-1.2.0') }}/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="{{ asset('kaiadmin-lite-1.2.0') }}/assets/css/kaiadmin.min.css" />
    @stack('css')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="purple2">
            @includeIf('layouts.sidebar')
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            @includeIf('layouts.header')

            <div class="container">
                @yield('container')
            </div>

            @includeIf('layouts.footer')
        </div>

        <!-- Custom template | don't include it in your project! -->

        <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/core/popper.min.js"></script>
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    {{-- <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/plugin/sweetalert/sweetalert.min.js"></script> --}}

    <!-- Kaiadmin JS -->
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/kaiadmin.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: {!! json_encode(session('success')) !!},
            confirmButtonText: 'OK'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: {!! json_encode(implode("\n", $errors->all())) !!},
            confirmButtonText: 'Tutup'
        });
    @endif
    </script>

    <script>
        @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        });
    @endif
    </script>

    <!-- Validator -->
    <script src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/js/validator.min.js"></script>

    <script>
        function preview(selector, temporaryFile, width = 200) {
            $(selector).empty();
            $(selector).append(`
                <img src="${window.URL.createObjectURL(temporaryFile)}"
                    class="foto-preview">
            `);
        }
    </script>
    @stack('scripts')

</body>

</html>