// Quiz UI only; scoring is server-side.
document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('form.q-form');

    forms.forEach(function (form) {
        var hidden = form.querySelector('input[name="form"][value="submit_quiz"]');
        if (!hidden) {
            return;
        }

        var questions = Array.from(form.querySelectorAll('.quiz-fieldset'));
        if (questions.length === 0) {
            return;
        }

        var progress = document.createElement('p');
        progress.className = 'quiz-progress';
        form.insertBefore(progress, form.firstElementChild.nextSibling);

        function isAnswered(fieldset) {
            return fieldset.querySelector('input[type="radio"]:checked') !== null;
        }

        function updateProgress() {
            var answered = questions.filter(isAnswered).length;
            progress.textContent = 'Answered ' + answered + ' of ' + questions.length + ' questions.';

            questions.forEach(function (fieldset) {
                fieldset.classList.toggle('missing-question', !isAnswered(fieldset));
            });
        }

        form.addEventListener('change', function (event) {
            if (event.target.matches('input[type="radio"]')) {
                var fieldset = event.target.closest('.quiz-fieldset');
                if (fieldset) {
                    fieldset.querySelectorAll('.block-choice').forEach(function (label) {
                        label.classList.remove('selected-choice');
                    });
                    var selectedLabel = event.target.closest('.block-choice');
                    if (selectedLabel) {
                        selectedLabel.classList.add('selected-choice');
                    }
                }
                updateProgress();
            }
        });

        form.addEventListener('submit', function (event) {
            var firstMissing = null;
            questions.forEach(function (fieldset) {
                if (!isAnswered(fieldset) && firstMissing === null) {
                    firstMissing = fieldset;
                }
            });

            if (firstMissing !== null) {
                event.preventDefault();
                updateProgress();
                firstMissing.scrollIntoView({ behavior: 'smooth', block: 'center' });
                var firstInput = firstMissing.querySelector('input[type="radio"]');
                if (firstInput) {
                    firstInput.focus();
                }
                alert('Answer every question before you submit.');
            }
        });

        updateProgress();
    });
});
