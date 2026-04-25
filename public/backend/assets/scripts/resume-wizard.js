$(document).ready(function () {

    let resumeId = localStorage.getItem('resume_id') || null;
    let isSubmitting = false;
    let currentStep = null;

    let draftTimer = null;
    let lastSavedData = null;
    let isAutoSaving = false;

    // ===============================
    // 🚀 NEW FEATURES STATE
    // ===============================
    let offlineQueue = [];
    let isOnline = navigator.onLine;

    let versionHistory = [];
    let maxHistory = 10;

    let saveStatusEl = null;

    console.log('Resume Wizard Loaded ✅');

    // ===============================
    // 🔥 SAVE STATUS UI (NEW)
    // ===============================
    function initSaveIndicator() {

        if ($('#saveStatus').length === 0) {
            $('body').append(`
                <div id="saveStatus"
                     style="position:fixed;bottom:20px;right:20px;
                     background:#333;color:#fff;padding:8px 12px;
                     border-radius:6px;font-size:13px;z-index:9999;">
                    Ready
                </div>
            `);
        }

        saveStatusEl = $('#saveStatus');
    }

    function setSaveStatus(text, color = '#333') {
        if (!saveStatusEl) return;

        saveStatusEl.text(text);
        saveStatusEl.css('background', color);
    }

    initSaveIndicator();

    // ===============================
    // ALERT SYSTEM (UNCHANGED)
    // ===============================
    function showAlert(type, message) {

        message = message || 'Something went wrong';

        if (type === 'error') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Error', text: message });
            } else {
                alert(message);
            }
        }

        else if (type === 'success') {
            if (typeof toastr !== 'undefined') {
                toastr.success(message);
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: message,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                alert(message);
            }
        }

        else {
            console.log(message);
        }
    }

    // ===============================
    // STEP CONTROL (UNCHANGED + SAFE FIX)
    // ===============================
    $('.nextBtn').on('click', function () {
        currentStep = parseInt($(this).val() || 1);
    });

    $('#resumeForm').on('submit', function (e) {
        e.preventDefault();
        if (isSubmitting || !currentStep) return;
        handleStep(currentStep);
    });

    function handleStep(step) {

        if (isSubmitting) return;

        isSubmitting = true;

        let formEl = document.getElementById('resumeForm');

        if (!formEl) {
            showAlert('error', 'Form not found');
            isSubmitting = false;
            return;
        }

        let formData = new FormData(formEl);

        let url = '';

        if (step == 1) {
            url = window.resumeRoutes.step1;
        } else {

            if (!resumeId) {
                showAlert('error', 'Please complete Step 1 first!');
                isSubmitting = false;
                return;
            }

            url =
                step == 2 ? window.resumeRoutes.step2.replace('__ID__', resumeId) :
                step == 3 ? window.resumeRoutes.step3.replace('__ID__', resumeId) :
                step == 4 ? window.resumeRoutes.step4.replace('__ID__', resumeId) :
                null;

            if (!url) {
                showAlert('error', 'Invalid step');
                isSubmitting = false;
                return;
            }
        }

        let btn = $('#resumeForm button[value="' + step + '"]');
        btn.prop('disabled', true).text('Please wait...');

        clearErrors();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (res) {

                if (step == 1 && res.resume_id) {
                    resumeId = res.resume_id;
                    localStorage.setItem('resume_id', resumeId);
                }

                showAlert('success', res.message);
                goToNextTab(step);
            },

            error: function (xhr) {

                let msg = xhr.responseJSON?.message || 'Server Error';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    handleValidationErrors(xhr.responseJSON.errors);
                    return;
                }

                showAlert('error', msg);
            },

            complete: function () {
                isSubmitting = false;
                btn.prop('disabled', false).text(step == 4 ? 'Final Submit' : 'Save & Next');
            }
        });
    }

    // ===============================
    // VALIDATION (UNCHANGED FIXED)
    // ===============================
    function handleValidationErrors(errors) {

        let first = null;

        $.each(errors, function (key, value) {

            let input = $(`[name="${key}"]`);
            if (!input.length) return;

            input.addClass('is-invalid');

            if (!input.next('.invalid-feedback').length) {
                input.after(`<span class="invalid-feedback d-block">${value[0]}</span>`);
            }

            if (!first) first = input;
        });

        if (first) {
            $('html, body').animate({ scrollTop: first.offset().top - 100 }, 400);
        }

        showAlert('error', 'Please fix validation errors');
    }

    function clearErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    }

    // ===============================
    // 📦 VERSION HISTORY (NEW)
    // ===============================
    function saveVersion(data) {
        versionHistory.push(JSON.stringify(data));
        if (versionHistory.length > maxHistory) {
            versionHistory.shift();
        }
    }

    function undoLastChange() {
        if (versionHistory.length < 2) return;

        versionHistory.pop();
        let prev = versionHistory[versionHistory.length - 1];

        let obj = JSON.parse(prev);

        $.each(obj, function (k, v) {
            $(`[name="${k}"]`).val(v);
        });

        setSaveStatus("Reverted ⏪", "#f39c12");
    }

    // ===============================
    // FORM DATA
    // ===============================
    function getFormData() {
        let obj = {};

        $('#resumeForm').find('input, textarea, select').each(function () {
            let name = $(this).attr('name');
            if (name) obj[name] = $(this).val() || '';
        });

        return obj;
    }

    // ===============================
    // 🌐 OFFLINE SAVE QUEUE (NEW)
    // ===============================
    function flushOfflineQueue() {

        if (!isOnline || offlineQueue.length === 0) return;

        let item = offlineQueue.shift();

        $.ajax({
            url: window.resumeRoutes.draft.replace('__ID__', resumeId),
            type: 'POST',
            data: JSON.stringify(item),
            contentType: "application/json",
            processData: false,
            success: function () {
                console.log('Offline sync done ✅');
                flushOfflineQueue();
            },
            error: function () {
                offlineQueue.unshift(item);
            }
        });
    }

    window.addEventListener('online', function () {
        isOnline = true;
        flushOfflineQueue();
    });

    window.addEventListener('offline', function () {
        isOnline = false;
    });

    // ===============================
    // 💾 AUTO SAVE (UPGRADED)
    // ===============================
    function autoSaveDraftSmart() {

        if (!resumeId || isAutoSaving) return;

        let data = getFormData();
        let str = JSON.stringify(data);

        if (str === lastSavedData) return;

        lastSavedData = str;

        saveVersion(data);

        isAutoSaving = true;

        setSaveStatus("Saving...", "#3498db");

        let requestData = JSON.stringify(data);

        function sendRequest() {

            $.ajax({
                url: window.resumeRoutes.draft.replace('__ID__', resumeId),
                type: 'POST',
                data: requestData,
                contentType: "application/json",
                processData: false,

                success: function () {
                    setSaveStatus("Saved ✓", "#2ecc71");
                    console.log('Draft saved ✅');
                },

                error: function () {

                    setSaveStatus("Queued ⏳", "#e67e22");

                    offlineQueue.push(data);
                    console.log('Queued for offline sync');
                },

                complete: function () {
                    isAutoSaving = false;
                }
            });
        }

        if (isOnline) {
            sendRequest();
        } else {
            offlineQueue.push(data);
            setSaveStatus("Offline ⛔", "#e74c3c");
        }
    }

    // ===============================
    // INPUT LISTENER
    // ===============================
    $(document).on('input change', '#resumeForm input, #resumeForm textarea, #resumeForm select', function () {

        if (!resumeId) return;

        clearTimeout(draftTimer);

        draftTimer = setTimeout(() => {
            autoSaveDraftSmart();
        }, 1200);
    });

    // ===============================
    // NAVIGATION (UNCHANGED)
    // ===============================
    function goToNextTab(step) {

        let next = '';

        if (step == 1) next = '#step2';
        if (step == 2) next = '#step3';
        if (step == 3) next = '#step4';

        if (step < 4) {
            $('.nav-tabs a[href="' + next + '"]').tab('show');
        } else {

            localStorage.clear();

            showAlert('success', 'Resume Created Successfully');

            setTimeout(() => {
                window.location.href = window.resumeRoutes.index;
            }, 1200);
        }
    }

});