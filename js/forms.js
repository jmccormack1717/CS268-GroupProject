// Client-side checks only. The server still validates on POST where implemented.
document.addEventListener('DOMContentLoaded', function () {
    setupLoginForm();
    setupRegisterForm();
    setupContactForm();
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

function setupContactForm() {
    var form = document.querySelector('.contact-form');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        validateContactForm();
    });
}

function validateContactForm() {
    var form = document.querySelector('.contact-form');
    if (!form) {
        return false;
    }

    var name = getValue(form, '[name="name"]');
    var email = getValue(form, '[name="email"]');
    var comment = getValue(form, '[name="comment"]');

    if (name === '') {
        showFormMessage(form, 'Enter your name.', false);
        return false;
    }
    if (!looksLikeEmail(email)) {
        showFormMessage(form, 'Enter a valid email address.', false);
        return false;
    }
    if (comment.length < 10) {
        showFormMessage(form, 'Comment must be at least 10 characters.', false);
        return false;
    }

    showFormMessage(
        form,
        'Input is valid. This form does not send mail because no server handler is set up.',
        true
    );
    form.reset();
    return true;
}

window.validateContactForm = validateContactForm;
