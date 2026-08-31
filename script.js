// The only JavaScript on the site: the eye button next to a password box.
//
// The buttons are written with the "hidden" attribute on them, so a visitor
// whose browser has JavaScript switched off never sees a button that would
// not do anything. This code is what takes that attribute off.
//
// The two eye pictures are set in style.css. Adding the "on" class is what
// swaps the plain eye for the crossed out one.

document.querySelectorAll('.reveal').forEach(function (button) {

    button.hidden = false;

    button.addEventListener('click', function () {

        // the password box is the element right before the button
        var field = button.previousElementSibling;

        if (field.type === 'password') {
            field.type = 'text';
            button.classList.add('on');
            button.setAttribute('aria-label', 'Hide the password');
        } else {
            field.type = 'password';
            button.classList.remove('on');
            button.setAttribute('aria-label', 'Show the password');
        }
    });
});
