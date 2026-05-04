document.addEventListener('DOMContentLoaded', function () {
    setupLoginForm();
    setupRegisterForm();
});

function showFormMessage(form, message, ok) {
    var box = form.querySelector('.js-form-message');
    if (!box) {
        box = document.createElement('p');
        box.className = 'js-form-message';
        form.insertBefore(box, form.firstElementChild);
    }
    box.textContent = message;
    box.className = 'js-form-message ' + (ok ? 'form-success' : 'form-error');
}

function getValue(form, selector) {
    var field = form.querySelector(selector);
    return field ? field.value.trim() : '';
}

function looksLikeEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function setupLoginForm() {
    var form = document.querySelector('form[action="login.php"]');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function (event) {
        var login = getValue(form, '#login');
        var password = getValue(form, '#password');
        if (login === '' || password === '') {
            event.preventDefault();
            showFormMessage(form, 'Enter your username or email and password.', false);
        }
    });
}

function setupRegisterForm() {
    var form = document.querySelector('form[action="register.php"]');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function (event) {
        var username = getValue(form, '#username');
        var email = getValue(form, '#email');
        var password = getValue(form, '#password');
        var confirm = getValue(form, '#password_confirm');

        if (!/^[A-Za-z0-9_]{3,64}$/.test(username)) {
            event.preventDefault();
            showFormMessage(form, 'Username must be 3 to 64 characters: letters, digits, or underscore.', false);
            return;
        }
        if (!looksLikeEmail(email)) {
            event.preventDefault();
            showFormMessage(form, 'Enter a valid email address.', false);
            return;
        }
        if (password.length < 8) {
            event.preventDefault();
            showFormMessage(form, 'Password must be at least 8 characters.', false);
            return;
        }
        if (password !== confirm) {
            event.preventDefault();
            showFormMessage(form, 'Password and confirmation do not match.', false);
        }
    });
}
