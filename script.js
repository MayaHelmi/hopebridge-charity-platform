// The JavaScript on the site. There are three small pieces:
//   1. the eye button next to a password box,
//   2. the shimmer that stops once a picture has loaded,
//   3. the note shown when somebody presses a button that needed the server.
//
// Piece one: the eye button.
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


// The shimmer behind a photograph is a background, so the photograph covers it
// on arrival - but covering it does not stop it. Left alone it would keep
// animating for as long as the page is open, out of sight and for no reason.
// This switches it off the moment the image is actually there.

document.querySelectorAll('.photo img, .figure > img').forEach(function (image) {

    var box = image.parentElement;

    function done() {
        box.classList.add('loaded');
    }

    if (image.complete) {
        done();                                  // already cached
    } else {
        image.addEventListener('load', done);
        image.addEventListener('error', done);   // a missing file must not shimmer for ever either
    }
});


// ---------------------------------------------------------------------------
// This is the HTML and CSS version of the site, so there is nothing behind the
// forms any more. Rather than let a button look broken, anything that used to
// send something to the server now says so.
// ---------------------------------------------------------------------------

function explain(afterThis) {

    // only ever one note on the page at a time
    var existing = document.querySelector('.static-note');
    if (existing) {
        existing.remove();
    }

    var note = document.createElement('p');
    note.className = 'notice info static-note';
    note.setAttribute('role', 'status');
    note.textContent = 'This is the HTML and CSS version of HopeBridge, so this button has nothing to send to. The pages show saved example information.';

    afterThis.insertAdjacentElement('afterend', note);
    note.scrollIntoView({ block: 'nearest' });
}

document.querySelectorAll('form[data-static]').forEach(function (form) {

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        explain(form);
    });
});

document.querySelectorAll('a[data-static]').forEach(function (link) {

    link.addEventListener('click', function (event) {
        event.preventDefault();
        explain(link.closest('.social') || link);
    });
});
