$(document).ready(function () {

    // ================= WIZARD INIT =================
    $("#resumeForm").steps({
        headerTag: "h5",
        bodyTag: "section",
        transitionEffect: "slideLeft",

        onStepChanging: function (event, currentIndex, newIndex) {

            let valid = true;

            $("section").eq(currentIndex).find(".required").each(function () {
                if ($(this).val() === '') {
                    $(this).addClass("is-invalid");
                    valid = false;
                } else {
                    $(this).removeClass("is-invalid");
                }
            });

            return valid;
        },

        onFinished: function () {
            $("#resumeForm").submit();
        }
    });

    // ================= EDUCATION =================
    let eduIndex = 0;

    function addEducation() {
        $("#education-wrapper").append(`
            <div class="border p-3 mb-2">
                <input type="text" name="educations[${eduIndex}][degree]" placeholder="Degree" class="form-control mb-2 required">
                <input type="text" name="educations[${eduIndex}][institution]" placeholder="Institution" class="form-control mb-2 required">
                <input type="date" name="educations[${eduIndex}][start_date]" class="form-control mb-2 required">
                <input type="date" name="educations[${eduIndex}][end_date]" class="form-control mb-2">
            </div>
        `);
        eduIndex++;
    }

    $("#add-education").click(addEducation);
    addEducation();

    // ================= SKILLS =================
    let skillIndex = 0;

    function addSkill() {
        $("#skills-wrapper").append(`
            <div class="border p-3 mb-2">
                <input type="text" name="skills[${skillIndex}][skill_name]" placeholder="Skill" class="form-control mb-2 required">
                <input type="text" name="skills[${skillIndex}][category]" placeholder="Category" class="form-control mb-2 required">
                <textarea name="skills[${skillIndex}][icon_path]" placeholder="SVG Path" class="form-control mb-2"></textarea>
            </div>
        `);
        skillIndex++;
    }

    $("#add-skill").click(addSkill);
    addSkill();

    // ================= EXPERIENCE =================
    let expIndex = 0;

    function addExperience() {
        $("#experience-wrapper").append(`
            <div class="border p-3 mb-2">
                <input type="text" name="experiences[${expIndex}][designation]" placeholder="Designation" class="form-control mb-2 required">
                <input type="text" name="experiences[${expIndex}][company]" placeholder="Company" class="form-control mb-2 required">
                <input type="date" name="experiences[${expIndex}][start_date]" class="form-control mb-2 required">
                <input type="date" name="experiences[${expIndex}][end_date]" class="form-control mb-2">
                <textarea name="experiences[${expIndex}][details][0][description]" placeholder="Description" class="form-control required"></textarea>
            </div>
        `);
        expIndex++;
    }

    $("#add-exp").click(addExperience);
    addExperience();

});