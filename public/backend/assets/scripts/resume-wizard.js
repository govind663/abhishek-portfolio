$(function () {

    let resumeId = localStorage.getItem('resume_id') || null;
    let isSubmitting = false;
    let currentStep = 1;

    let draftTimer = null;
    let lastSavedData = null;
    let isAutoSaving = false;

    let offlineQueue = [];
    let isOnline = navigator.onLine;

    let saveStatusEl = null;

    console.log('Resume Wizard Loaded:', resumeId);

    // ===============================
    // SAVE STATUS UI
    // ===============================
    function initSaveIndicator() {
        if (!$('#saveStatus').length) {
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
        if (saveStatusEl) {
            saveStatusEl.text(text).css('background', color);
        }
    }

    initSaveIndicator();

    // ===============================
    // LOAD DRAFT (IMPROVED)
    // ===============================
    function loadDraft() {

        if (!resumeId || !window.resumeRoutes?.getDraft) return;

        $.get(window.resumeRoutes.getDraft.replace('__ID__', resumeId))
            .done(function (res) {

                if (!res?.data) return;

                Object.entries(res.data).forEach(([key, value]) => {

                    let field = $(`[name="${key}"]`);

                    if (!field.length) return;

                    // 🔥 Handle array inputs better
                    if (Array.isArray(value)) {
                        field.each(function (i) {
                            $(this).val(value[i] ?? '');
                        });
                    } else {
                        field.val(value ?? '');
                    }
                });

                console.log('Draft loaded ✅');
            })
            .fail(() => console.warn('Draft load failed'));
    }

    setTimeout(loadDraft, 400);

    // ===============================
    // ALERT
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

        if (type === 'success') {
            if (typeof toastr !== 'undefined') {
                toastr.success(message);
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: message,
                    timer: 1200,
                    showConfirmButton: false
                });
            }
        }
    }

    // ===============================
    // STEP CONTROL (FIXED)
    // ===============================
    $(document).on('click', '.nextBtn', function () {
        currentStep = Number($(this).data('step') || 1); // ✅ FIXED
    });

    $('#resumeForm').on('submit', function (e) {
        e.preventDefault();

        if (isSubmitting) return;

        handleStep(currentStep);
    });

    function handleStep(step) {

        if (isSubmitting) return;
        isSubmitting = true;

        let form = $('#resumeForm')[0];
        let formData = new FormData(form);

        let url = '';

        if (step === 1) {
            url = window.resumeRoutes.step1;
        } else {

            if (!resumeId) {
                showAlert('error', 'Step 1 complete karein');
                isSubmitting = false;
                return;
            }

            url =
                step === 2 ? window.resumeRoutes.step2.replace('__ID__', resumeId) :
                step === 3 ? window.resumeRoutes.step3.replace('__ID__', resumeId) :
                step === 4 ? window.resumeRoutes.step4.replace('__ID__', resumeId) :
                null;
        }

        if (!url) {
            isSubmitting = false;
            return;
        }

        let btn = $(`#resumeForm button[data-step="${step}"]`);
        btn.prop('disabled', true).text('Please wait...');

        clearErrors();

        $.ajax({
            url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (res) {

                if (step === 1 && res?.resume_id) {
                    resumeId = res.resume_id;
                    localStorage.setItem('resume_id', resumeId);

                    loadDraft();
                }

                showAlert('success', res.message || 'Saved');
                goToNextTab(step);
            },

            error: function (xhr) {

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    handleValidationErrors(xhr.responseJSON.errors);
                    return;
                }

                showAlert('error', xhr.responseJSON?.message || 'Server Error');
            },

            complete: function () {
                isSubmitting = false;
                btn.prop('disabled', false)
                   .text(step === 4 ? 'Final Submit' : 'Save & Next');
            }
        });
    }

    // ===============================
    // VALIDATION FIX
    // ===============================
    function handleValidationErrors(errors) {

        let first = null;

        Object.entries(errors).forEach(([key, msgs]) => {

            let name = key.replace(/\.(\d+)\./g, '[$1][').replace(/\./g, ']');

            let input = $(`[name="${name}"]`);

            if (!input.length) return;

            input.addClass('is-invalid');

            if (!input.next('.invalid-feedback').length) {
                input.after(`<span class="invalid-feedback">${msgs[0]}</span>`);
            }

            if (!first) first = input;
        });

        if (first) {
            $('html, body').animate({
                scrollTop: first.offset().top - 100
            }, 400);
        }
    }

    function clearErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    }

    // ===============================
    // AUTO SAVE (IMPROVED)
    // ===============================
    function autoSaveDraftSmart() {

        if (!resumeId || isAutoSaving) return;

        let data = $('#resumeForm').serialize();

        if (data === lastSavedData) return;

        lastSavedData = data;
        isAutoSaving = true;

        setSaveStatus("Saving...", "#3498db");

        $.ajax({
            url: window.resumeRoutes.draft.replace('__ID__', resumeId),
            type: 'POST',
            data,

            success: function () {
                setSaveStatus("Saved ✓", "#2ecc71");
            },

            error: function () {
                offlineQueue.push({ data, retry: 0 });
                setSaveStatus("Queued ⏳", "#e67e22");
            },

            complete: function () {
                isAutoSaving = false;
            }
        });
    }

    // ===============================
    // INPUT LISTENER (DEBOUNCE FIX)
    // ===============================
    $(document).on('input change', '#resumeForm input, #resumeForm textarea, #resumeForm select', function () {

        if (!resumeId) return;

        if (draftTimer) clearTimeout(draftTimer);

        draftTimer = setTimeout(autoSaveDraftSmart, 1000);
    });

    // ===============================
    // OFFLINE SUPPORT (RETRY SAFE)
    // ===============================
    function flushOfflineQueue() {

        if (!isOnline || !resumeId || offlineQueue.length === 0) return;

        let item = offlineQueue.shift();

        $.post(window.resumeRoutes.draft.replace('__ID__', resumeId), item.data)
            .done(() => console.log('Retry success'))
            .fail(() => {
                item.retry++;
                if (item.retry < 3) offlineQueue.push(item);
            })
            .always(flushOfflineQueue);
    }

    window.addEventListener('online', () => {
        isOnline = true;
        flushOfflineQueue();
    });

    window.addEventListener('offline', () => {
        isOnline = false;
    });

    // ===============================
    // NAVIGATION
    // ===============================
    function goToNextTab(step) {

        let next = step === 1 ? '#step2' :
                   step === 2 ? '#step3' :
                   step === 3 ? '#step4' : '';

        if (step < 4) {
            $('.nav-tabs a[href="' + next + '"]').tab('show');
        } else {

            localStorage.removeItem('resume_id');

            showAlert('success', 'Resume Completed');

            setTimeout(() => {
                window.location.href = window.resumeRoutes.index;
            }, 1000);
        }
    }

});