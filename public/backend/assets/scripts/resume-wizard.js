class ResumeWizard {

    constructor() {
        this.resumeId = window.resumeId || window.resumeConfig?.id || null;
        this.mode = window.resumeConfig?.mode || "create";

        this.routes = window.resumeRoutes || {};

        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        this.csrf = csrfMeta ? csrfMeta.getAttribute('content') : "";

        this.debounceTimer = null;
        this.localKey = "resume_draft_local";
        this.isSavingDraft = false; // ✅ FIX: prevent multiple API calls

        this.currentStep = 1;

        this.init();
    }

    init() {
        this.bindAllSteps();
        this.bindBackButtons();
        this.lockTabs();

        this.unlockTabs(this.currentStep);

        this.initBlurAutoSave();
        this.restoreLocalDraft();
        this.loadDraft();
    }

    /* ================= TOAST ================= */
    showToast(message, type = "warning") {

        let toast = document.createElement("div");

        toast.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span>${message}</span>
                <span style="margin-left:12px;cursor:pointer;font-weight:bold;font-size:20px;" onclick="this.closest('.custom-toast').remove()">×</span>
            </div>
        `;

        toast.className = "custom-toast";

        toast.style.cssText = `
            position:fixed;
            top:20px;
            right:20px;
            padding:14px 18px;
            background:${type === "error" ? "#dc3545" : type === "success" ? "#28a745" : "#ff9800"};
            color:#fff;
            border-radius:8px;
            z-index:9999;
            font-size:14px;
            box-shadow:0 6px 18px rgba(0,0,0,0.25);
        `;

        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }

    /* ================= API ================= */
    async request(url, method = "POST", data = {}) {

        if (!url) {
            console.warn("⚠️ API endpoint missing.");
            return;
        }

        try {
            const response = await fetch(url, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": this.csrf,
                    "Accept": "application/json"
                },
                body: JSON.stringify(data)
            });

            let result = {};
            try { result = await response.json(); } catch {}

            if (!response.ok) throw result;

            return result;

        } catch (error) {
            this.handleError(error);
            throw error;
        }
    }

    /* ================= ERROR ================= */
    handleError(error) {

        this.clearErrors();

        if (error?.errors) {
            Object.keys(error.errors).forEach(field => {

                let input =
                    document.querySelector(`[name="${field}"]`) ||
                    document.querySelector(`[name="${field}[]"]`);

                if (!input) return;

                input.classList.add("is-invalid");

                let div = document.createElement("div");
                div.className = "invalid-feedback";
                div.innerText = error.errors[field][0];

                input.closest(".form-group, div")?.appendChild(div);
            });

        } else {
            this.showToast(error?.message || "Something went wrong", "error");
        }
    }

    clearErrors() {
        document.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
        document.querySelectorAll(".invalid-feedback").forEach(el => el.remove());
    }

    /* ================= TAB LOCK ================= */
    lockTabs() {

        document.querySelectorAll('.nav-tabs .nav-link').forEach((tab, index) => {

            tab.addEventListener('click', (e) => {

                if ((index + 1) > this.currentStep || (!this.resumeId && index !== 0)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    this.showToast(`Complete Step ${this.currentStep} first`);
                }
            });

            tab.addEventListener('show.bs.tab', (e) => {

                if ((index + 1) > this.currentStep || (!this.resumeId && index !== 0)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                }
            });
        });
    }

    unlockTabs(step = 1) {
        this.currentStep = step;

        document.querySelectorAll('.nav-tabs .nav-link')
            .forEach((tab, index) => {

                if ((index + 1) <= step) {
                    tab.classList.remove('disabled-tab');
                } else {
                    tab.classList.add('disabled-tab');
                }
            });
    }

    /* ================= STEPS ================= */
    bindAllSteps() {

        document.querySelectorAll(".nextBtn").forEach(btn => {

            btn.addEventListener("click", async (e) => {
                e.preventDefault();

                const step = parseInt(btn.dataset.step);
                this.clearErrors();

                try {
                    if (step === 1) await this.submitStep1();
                    if (step === 2) await this.submitStep2(this.getEducations());
                    if (step === 3) await this.submitStep3(this.getSkills());
                    if (step === 4) await this.submitStep4(this.getExperiences());
                } catch {}
            });
        });
    }

    bindBackButtons() {
        document.querySelectorAll(".prevBtn").forEach(btn => {
            btn.addEventListener("click", (e) => {
                e.preventDefault();
                this.goToStep(parseInt(btn.dataset.step) - 1);
            });
        });
    }

    async submitStep1() {

        const form = document.getElementById("resumeForm");
        const data = Object.fromEntries(new FormData(form));

        const res = await this.request(this.routes.get(1), "POST", data);

        if (res?.status) {

            this.resumeId = res.resume_id || this.resumeId;

            window.resumeConfig = window.resumeConfig || {};
            window.resumeConfig.id = this.resumeId;
            window.resumeId = this.resumeId;

            this.mode = "update";

            console.log("✅ Resume ID:", this.resumeId);

            // ✅ immediate draft save
            await this.autoSaveDraft();

            this.unlockTabs(1);
            this.goToStep(2);
        }
    }

    async submitStep2(data) {
        const res = await this.request(this.routes.get(2), "POST", { educations: data });
        if (res?.status) {
            this.unlockTabs(2);
            this.goToStep(3);
        }
    }

    async submitStep3(data) {
        const res = await this.request(this.routes.get(3), "POST", { skills: data });
        if (res?.status) {
            this.unlockTabs(3);
            this.goToStep(4);
        }
    }

    async submitStep4(data) {
        const res = await this.request(this.routes.get(4), "POST", { experiences: data });
        if (res?.status) {
            this.showToast("Resume completed", "success");
            setTimeout(() => location.href = this.routes.index, 1500);
        }
    }

    goToStep(step) {
        this.currentStep = step;
        const tab = document.querySelector(`a[href="#step${step}"]`);
        if (tab) new bootstrap.Tab(tab).show();
        this.unlockTabs(step);
    }

    /* ================= AUTO SAVE ================= */
    initBlurAutoSave() {

        const form = document.getElementById("resumeForm");
        if (!form) return;

        form.querySelectorAll("input, textarea, select").forEach(field => {

            field.addEventListener("input", () => {

                // ❌ block before ID
                if (!this.resumeId) return;

                this.debounce(() => this.autoSaveDraft());
            });
        });
    }

    debounce(callback, delay = 800) {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(callback, delay);
    }

    async autoSaveDraft() {

        if (!this.resumeId || this.isSavingDraft) return;

        this.isSavingDraft = true;

        const data = this.collectDraftData();

        localStorage.setItem(this.localKey, JSON.stringify(data.step1));

        let url = this.routes.draft;
        if (typeof url === "function") url = url(this.resumeId);
        else if (typeof url === "string") url = url.replace(':id', this.resumeId);

        if (!url) {
            console.warn("⚠️ Draft URL missing");
            this.isSavingDraft = false;
            return;
        }

        try {
            console.log("📡 Draft saving...", url);

            await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": this.csrf
                },
                body: JSON.stringify(data)
            });

            console.log("✅ Draft saved");

        } catch (e) {
            console.error("❌ Draft error", e);
        }

        this.isSavingDraft = false;
    }

    /* ================= DRAFT ================= */
    restoreLocalDraft() {

        const saved = localStorage.getItem(this.localKey);
        if (!saved) return;

        try {
            const data = JSON.parse(saved);

            Object.keys(data).forEach(name => {
                const input = document.querySelector(`[name="${name}"]`);
                if (input && !input.value) input.value = data[name];
            });

        } catch {}
    }

    async loadDraft() {

        if (!this.resumeId || !this.routes.getDraft) return;

        try {
            const res = await fetch(this.routes.getDraft());
            const json = await res.json();

            if (json?.status && json?.data) {
                this.fillDraft(json.data);
            }

        } catch {}
    }

    fillDraft(data) {

        if (data.step1) {
            Object.keys(data.step1).forEach(name => {
                const input = document.querySelector(`[name="${name}"]`);
                if (input) input.value = data.step1[name];
            });
        }

        window.educations = data.step2 || [];
        window.skills = data.step3 || [];
        window.experiences = data.step4 || [];

        if (this.resumeId) this.unlockTabs(1);
    }

    /* ================= DATA ================= */
    collectDraftData() {
        return {
            step1: this.getStep1(),
            step2: this.getEducations(),
            step3: this.getSkills(),
            step4: this.getExperiences()
        };
    }

    getStep1() {
        const form = document.getElementById("resumeForm");
        return form ? Object.fromEntries(new FormData(form)) : {};
    }

    getEducations() { return window.educations || []; }
    getSkills() { return window.skills || []; }
    getExperiences() { return window.experiences || []; }
}

/* INIT */
document.addEventListener("DOMContentLoaded", () => {
    window.resumeWizard = new ResumeWizard();
});