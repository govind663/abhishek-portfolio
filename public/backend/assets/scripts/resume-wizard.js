$(document).ready(function () {

    let resumeId = localStorage.getItem('resume_id') || null;
    let isSubmitting = false;
    let currentStep = null;

    let autoSaveTimer = null;
    let draftTimer = null;
    let lastSavedData = null;
    let isAutoSaving = false;

    console.log('Resume Wizard Loaded ✅');

    // ===============================
    // 🔥 ALERT + TOASTER SYSTEM (IMPROVED)
    // ===============================
    function showAlert(type, message) {

        // ❗ ERROR → SweetAlert
        if (type === 'error') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            } else {
                alert(message);
            }
        }

        // ✅ SUCCESS → Toaster
        else if (type === 'success') {

            if (typeof toastr !== 'undefined') {
                toastr.success(message);
            } 
            else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: message,
                    timer: 1500,
                    showConfirmButton: false
                });
            } 
            else {
                alert(message);
            }
        }

        // ℹ️ INFO
        else {
            if (typeof toastr !== 'undefined') {
                toastr.info(message);
            } else {
                console.log(message);
            }
        }
    }

    // ===============================
    // TAB CONTROL
    // ===============================
    $(document).on('show.bs.tab', 'a[data-toggle="tab"]', function (e) {

        let target = $(e.target).attr("href");
        let current = $('.nav-tabs .nav-link.active').attr("href");

        const stepOrder = {
            '#step1': 1,
            '#step2': 2,
            '#step3': 3,
            '#step4': 4
        };

        let targetStep = stepOrder[target];
        let currentStepNum = stepOrder[current];

        if (!resumeId && target !== '#step1') {
            e.preventDefault();
            showAlert('error', 'Please complete Step 1 first!');
            return false;
        }

        if (targetStep > currentStepNum + 1) {
            e.preventDefault();
            showAlert('error', 'Please complete previous step first!');
            return false;
        }
    });

    // ===============================
    // RESTORE FORM
    // ===============================
    let savedData = localStorage.getItem('resume_form_data');

    if (savedData) {
        let formData = JSON.parse(savedData);

        $.each(formData, function (key, value) {

            let input = $(`[name="${key}"]`);

            if (!input.length) {
                input = $(`[name^="${key}"]`);
            }

            if (input.length) {
                input.val(value);
            }
        });

        $('.custom-select2').trigger('change');
        console.log('Form Restored ✅');
    }

    $('.nextBtn').on('click', function () {
        currentStep = $(this).val();
    });

    // ===============================
    // FORM SUBMIT
    // ===============================
    $('#resumeForm').on('submit', function (e) {
        e.preventDefault();

        if (isSubmitting || !currentStep) return;

        handleStep(currentStep);
    });

    function handleStep(step) {

        isSubmitting = true;

        let url = '';
        let formData = new FormData(document.getElementById('resumeForm'));

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
                step == 4 ? window.resumeRoutes.step4.replace('__ID__', resumeId) : '';
        }

        let btn = $('#resumeForm button[value="' + step + '"]');
        btn.prop('disabled', true).text('Please wait...');

        $('#resumeForm .invalid-feedback').remove();
        $('#resumeForm .is-invalid').removeClass('is-invalid');
        $('.select2-selection').removeClass('is-invalid');
        $('.note-editor, .tox, .cke').removeClass('is-invalid');

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

                if (res.current_step) {
                    localStorage.setItem('current_step', res.current_step);
                }

                showAlert('success', res.message || 'Success');
                goToNextTab(step);
            },

            error: function (xhr) {

                if ([403, 409, 422].includes(xhr.status)) {

                    if (xhr.responseJSON?.message && !xhr.responseJSON?.errors) {
                        showAlert('error', xhr.responseJSON.message);

                        if (xhr.status === 403) {
                            setTimeout(() => location.reload(), 1200);
                        }

                        return;
                    }
                }

                if (xhr.status === 422 && xhr.responseJSON?.errors) {

                    let errors = xhr.responseJSON.errors;
                    let firstErrorElement = null;

                    $.each(errors, function (key, value) {

                        let input = $(`[name="${key}"]`);

                        if (!input.length) {
                            input = $(`[name^="${key}"]`);
                        }

                        let formGroup = input.closest('.form-group, .education-item, .skill-item, .experience-item');

                        input.addClass('is-invalid');

                        if (input.hasClass('custom-select2')) {

                            let select2Box = input.next('.select2-container');
                            select2Box.find('.select2-selection').addClass('is-invalid');

                            if (!select2Box.next('.invalid-feedback').length) {
                                select2Box.after(`<span class="invalid-feedback d-block fw-bold">${value[0]}</span>`);
                            }

                            if (!firstErrorElement) firstErrorElement = select2Box;
                        }

                        else if (input.hasClass('textarea_editor')) {

                            let editorBox = formGroup.find('.note-editor, .tox, .cke');

                            editorBox.addClass('is-invalid');

                            if (!formGroup.find('.invalid-feedback').length) {
                                formGroup.append(`<span class="invalid-feedback d-block fw-bold">${value[0]}</span>`);
                            }

                            if (!firstErrorElement) firstErrorElement = editorBox;
                        }

                        else {

                            if (!input.next('.invalid-feedback').length) {
                                input.after(`<span class="invalid-feedback d-block fw-bold">${value[0]}</span>`);
                            }

                            if (!firstErrorElement) firstErrorElement = input;
                        }
                    });

                    if (firstErrorElement) {
                        $('html, body').animate({
                            scrollTop: firstErrorElement.offset().top - 120
                        }, 400);
                    }

                    return;
                }

                showAlert('error', xhr.responseJSON?.message || 'Server Error');
            },

            complete: function () {
                isSubmitting = false;
                btn.prop('disabled', false).text(step == 4 ? 'Final Submit' : 'Save & Next');
            }
        });
    }

    // ===============================
    // AUTO SAVE
    // ===============================
    function getFormData() {
        let obj = {};
        $('#resumeForm').find('input, textarea, select').each(function () {
            let name = $(this).attr('name');
            if (name) obj[name] = $(this).val();
        });
        return obj;
    }

    function autoSaveDraftSmart() {

        if (!resumeId || isAutoSaving) return;

        let currentData = getFormData();
        let currentString = JSON.stringify(currentData);

        if (currentString === lastSavedData) return;

        lastSavedData = currentString;
        isAutoSaving = true;

        $.ajax({
            url: window.resumeRoutes.draft.replace('__ID__', resumeId),
            type: 'POST',
            data: currentData,

            success: function () {
                console.log('Smart Draft Saved ✅');
            },

            complete: function () {
                isAutoSaving = false;
            }
        });
    }

    $(document).on('input change keyup', '#resumeForm input, #resumeForm textarea, #resumeForm select', function () {

        let input = $(this);

        if ($.trim(input.val()) !== '') {
            input.removeClass('is-invalid');
            input.next('.invalid-feedback').remove();
        }

        let formDataObj = getFormData();
        localStorage.setItem('resume_form_data', JSON.stringify(formDataObj));

        clearTimeout(draftTimer);

        draftTimer = setTimeout(() => {
            autoSaveDraftSmart();
        }, 1500);
    });

    // ===============================
    // SELECT2 FIX
    // ===============================
    $(document).on('change', '.custom-select2', function () {

        let input = $(this);
        let select2Box = input.next('.select2-container');

        if ($.trim(input.val()) !== '') {
            input.removeClass('is-invalid');
            select2Box.find('.select2-selection').removeClass('is-invalid');
            select2Box.next('.invalid-feedback').remove();
        }
    });

    // ===============================
    // TEXTAREA FIX
    // ===============================
    $(document).on('keyup change', '.textarea_editor', function () {

        let input = $(this);
        let formGroup = input.closest('.form-group');
        let editorBox = formGroup.find('.note-editor, .tox, .cke');

        if ($.trim(input.val()) !== '') {
            input.removeClass('is-invalid');
            editorBox.removeClass('is-invalid');
            formGroup.find('.invalid-feedback').remove();
        }
    });

    function goToNextTab(step) {

        let nextTab = '';

        if (step == 1) nextTab = '#step2';
        if (step == 2) nextTab = '#step3';
        if (step == 3) nextTab = '#step4';

        if (step < 4) {
            $('.nav-tabs a[href="' + nextTab + '"]').tab('show');
        } else {

            localStorage.removeItem('resume_id');
            localStorage.removeItem('resume_form_data');
            localStorage.removeItem('current_step');

            showAlert('success', 'Resume Created Successfully');

            setTimeout(() => {
                window.location.href = window.resumeRoutes.index;
            }, 1200);
        }
    }

});