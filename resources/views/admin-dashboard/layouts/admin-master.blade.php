<!DOCTYPE html>
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free"
>
@include('admin-dashboard.layouts.partials.head')

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        @include('admin-dashboard.layouts.partials.sidebar')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->
            @include('admin-dashboard.layouts.partials.header')
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->

                @yield('main-content')
                <!-- / Content -->

                <!-- Footer -->
                @include('admin-dashboard.layouts.partials.footer')

                <div class="content-backdrop fade"></div>
            </div>
        </div>
    </div>

    <div class="clear-cache-fixed">
        <button class="btn btn-danger btn-sm clear-cache">
            <i class="menu-icon tf-icons bx bx-trash"></i>
            <span class="btn-text">{{ t_db('general', 'clear_cache') }}</span>
            <span class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
        </button>
    </div>

    <div class="layout-overlay layout-menu-toggle"></div>
</div>
@include('notify::components.notify')
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on('click', '.clear-cache', function () {
        let btn = $(this);
        let spinner = btn.find('.spinner-border');
        let btnText = btn.find('.btn-text');

        Swal.fire({
            title: "{{ t_db('general','are_you_sure') }}",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "{{ t_db('general','yes') }}",
            cancelButtonText: "{{ t_db('general','cancel') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                spinner.removeClass('d-none');
                btn.prop('disabled', true);
                btnText.text("{{ t_db('general','processing') }}");

                $.ajax({
                    url: "{{ route('clear-cache') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function (response) {
                        Swal.fire({
                            title: response.message ?? "{{ t_db('general','success') }}",
                            icon: "success"
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: "{{ t_db('general','error') }}",
                            text: xhr?.responseJSON?.message ?? "{{ t_db('general','something_went_wrong') }}",
                            icon: "error"
                        });
                    },
                    complete: function() {
                        spinner.addClass('d-none');
                        btn.prop('disabled', false);
                        btnText.text("{{ t_db('general', 'clear_cache') }}");
                    }
                });
            }
        });
    });

</script>
@notifyJs
@yield('js-code')
</body>
</html>
