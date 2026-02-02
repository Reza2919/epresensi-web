
<!-- BEGIN: Vendor JS-->
<script src="{{ asset('assets') }}/app-assets/vendors/js/vendors.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('assets') }}/app-assets/vendors/js/charts/apexcharts.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/extensions/toastr.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/extensions/moment.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/responsive.bootstrap.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/sweetalert2/sweetalert2@11.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets') }}/app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('assets') }}/app-assets/js/core/app-menu.js"></script>
<script>
    let assetPath = '{{ asset("assets/app-assets") }}/'
</script>
<script src="{{ asset('assets') }}/app-assets/js/core/app.js"></script>
<!-- END: Theme JS-->
<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>
@stack('js')