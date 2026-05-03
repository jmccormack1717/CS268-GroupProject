// Admin question forms: required-field check and question length counter.
document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('form.q-form');

    forms.forEach(function (form) {
        var action = form.querySelector('input[name="action"]');
        if (!action || (action.value !== 'add' && action.value !== 'update')) {
            return;
        }

        addCounters(form);

        form.addEventListener('submit', function (event) {
            var missing = [];
            var requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');

            requiredFields.forEach(function (field) {
                if (field.type === 'radio') {
                    return;
                }
                if (field.value.trim() === '') {
                    missing.push(field);
                }
            });

            var answer = form.querySelector('input[name="correct_choice"]:checked');
            if (!answer) {
                var firstRadio = form.querySelector('input[name="correct_choice"]');
                if (firstRadio) {
                    missing.push(firstRadio);
                }
            }

            if (missing.length > 0) {
                event.preventDefault();
                alert('Fill in all required fields before saving.');
                missing[0].focus();
            }
        });
    });
});

function addCounters(form) {
    var textareas = form.querySelectorAll('textarea[name="question_text"]');
    textareas.forEach(function (box) {
        var counter = document.createElement('small');
        counter.className = 'admin-counter';
        box.insertAdjacentElement('afterend', counter);

        function update() {
            counter.textContent = box.value.length + ' characters';
        }

        box.addEventListener('input', update);
        update();
    });
}
