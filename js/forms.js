// Simple front-end form checks. PHP still does the real validation.
document.addEventListener('DOMContentLoaded', function () {
    setupLoginForm();
    setupRegisterForm();
    setupContactForm();
});

function showFormMessage(form, message, good) {
    var box = form.querySelector('.js-form-message');
    if (!box) {
        box = document.createElement('p');
        box.className = 'js-form-message';
        form.insertBefore(box, form.firstElementChild);
    }
    box.textContent = message;
    box.className = 'js-form-message ' + (good ? 'form-success' : 'form-error');
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
            showFormMessage(form, 'Please enter your username/email and password.', false);
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
            showFormMessage(form, 'Username should be 3-64 letters, numbers, or underscores.', false);
            return;
        }
        if (!looksLikeEmail(email)) {
            event.preventDefault();
            showFormMessage(form, 'Please enter a valid email address.', false);
            return;
        }
        if (password.length < 8) {
            event.preventDefault();
            showFormMessage(form, 'Password needs at least 8 characters.', false);
            return;
        }
        if (password !== confirm) {
            event.preventDefault();
            showFormMessage(form, 'The two passwords do not match.', false);
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
        checkit();
    });
}

// This function is kept because the contact page originally called checkit().
function checkit() {
    var form = document.querySelector('.contact-form') || document.forms['Contact Us!'];
    if (!form) {
        return false;
    }

    var name = getValue(form, '[name="name"]');
    var email = getValue(form, '[name="email"]');
    var comment = getValue(form, '[name="comment"]');

    if (name === '') {
        showFormMessage(form, 'Please enter your name.', false);
        return false;
    }
    if (!looksLikeEmail(email)) {
        showFormMessage(form, 'Please enter a valid email address.', false);
        return false;
    }
    if (comment.length < 10) {
        showFormMessage(form, 'Please write a little more in the comment box.', false);
        return false;
    }

    showFormMessage(form, 'Thanks! Your message is ready. Since this is a class demo, it is not actually emailed.', true);
    form.reset();
    return true;
}

window.checkit = checkit;
