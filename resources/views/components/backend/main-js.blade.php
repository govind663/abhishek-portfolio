{{-- ================= CORE (NO DEFER ❗) ================= --}}
<script src="{{ asset('backend/assets/vendors/scripts/core.js') }}"></script>

{{-- ================= PLUGINS ================= --}}
<script src="{{ asset('backend/assets/src/plugins/jquery-steps/jquery.steps.js') }}" defer></script>
<script src="{{ asset('backend/assets/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}" defer></script>

{{-- ================= DATATABLE ================= --}}
<script src="{{ asset('backend/assets/src/plugins/datatables/js/jquery.dataTables.min.js') }}" defer></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}" defer></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/dataTables.responsive.min.js') }}" defer></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/responsive.bootstrap4.min.js') }}" defer></script>

{{-- ================= DATATABLE BUTTONS ================= --}}
<script src="{{ asset('backend/assets/src/plugins/datatables/js/dataTables.buttons.min.js') }}" defer></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/buttons.bootstrap4.min.js') }}" defer></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/buttons.print.min.js') }}" defer></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/buttons.html5.min.js') }}" defer></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/pdfmake.min.js') }}" defer></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/vfs_fonts.js') }}" defer></script>

{{-- ================= TEMPLATE ================= --}}
<script src="{{ asset('backend/assets/vendors/scripts/script.min.js') }}" defer></script>
<script src="{{ asset('backend/assets/vendors/scripts/process.js') }}" defer></script>
<script src="{{ asset('backend/assets/vendors/scripts/layout-settings.js') }}" defer></script>

{{-- ================= INIT (LAST) ================= --}}
<script src="{{ asset('backend/assets/vendors/scripts/datatable-setting.js') }}" defer></script>
<script src="{{ asset('backend/assets/vendors/scripts/steps-setting.js') }}" defer></script>

{{-- ================= TOASTR CLEAN ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ✅ Common Toastr Config (single place)
    toastr.options = {
        positionClass: "toast-top-right",
        closeButton: true,
        progressBar: true,
        preventDuplicates: true,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "4000",
        extendedTimeOut: "800",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    };

    // ✅ Laravel Flash Messages
    @if(Session::has('message'))
        toastr.success("{{ session('message') }}");
    @endif

    @if(Session::has('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if(Session::has('info'))
        toastr.info("{{ session('info') }}");
    @endif

    @if(Session::has('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

});
</script>

{{-- ================= SERVICE WORKER + UPDATE ================= --}}
<script>
if ('serviceWorker' in navigator) {

  const VERSION = "v6"; // 🔥 हर deploy पर change करो
  const STORAGE_KEY = "app_version";

  let isRefreshing = false;

  navigator.serviceWorker.register('/backend/assets/scripts/service-worker.js')
    .then(reg => {

      const oldVersion = localStorage.getItem(STORAGE_KEY);

      if (oldVersion && oldVersion !== VERSION) {
        console.log("🔄 New Version Detected:", oldVersion, "→", VERSION);
      }

      localStorage.setItem(STORAGE_KEY, VERSION);

      reg.addEventListener('updatefound', () => {
        const newWorker = reg.installing;

        newWorker.addEventListener('statechange', () => {
          if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {

              const remindLater = localStorage.getItem("remind_update");

              if (!remindLater) {
                showUpdateToast(reg);
              }
          }
        });
      });

    });

  function showUpdateToast(reg) {

    toastr.options = {
      closeButton: true,
      progressBar: true,
      timeOut: 0,
      extendedTimeOut: 0,
      positionClass: "toast-bottom-right"
    };

    toastr.info(`
      🚀 <b>New version available!</b><br/><br/>
      <button id="updateNow" style="background:#28a745;color:#fff;border:none;padding:6px 12px;border-radius:5px;">Update</button>
      <button id="later" style="margin-left:5px;background:#ffc107;color:#000;border:none;padding:6px 12px;border-radius:5px;">Later</button>
    `);

    // ❗ FIX: multiple event binding issue
    document.removeEventListener('click', handleUpdateClick);
    document.addEventListener('click', handleUpdateClick);

    function handleUpdateClick(e) {

      if (e.target.id === 'updateNow') {
        if (reg.waiting) {
          reg.waiting.postMessage('SKIP_WAITING');
        }
      }

      if (e.target.id === 'later') {
        localStorage.setItem("remind_update", "true");

        setTimeout(() => {
          localStorage.removeItem("remind_update");
        }, 10 * 60 * 1000);

        toastr.clear();
      }
    }
  }

  navigator.serviceWorker.addEventListener('controllerchange', () => {
    if (isRefreshing) return;
    isRefreshing = true;
    window.location.reload();
  });

}
</script>