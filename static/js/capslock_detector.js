// Written by Stephen Long - 16/11/16
// Adapted to ECMAScript 6 by Linkyu - 31/10/17
// TODO: Document all this
$(function() {
    let capsLockEnabled = null;
    document.msCapsLockWarningOff = true; // Set this to true to turn off default IE behavior.
    let isCheckEnabled = document.msCapsLockWarningOff === undefined || document.msCapsLockWarningOff;

    let checkWarning = function () {
        if (capsLockEnabled) {
            $("#capslock_warning").removeClass("hide");
        } else {
            $("#capslock_warning").addClass("hide");
        }
    };

    if (isCheckEnabled) {
        $(document).keydown(function(e) {
            if (e.which === 20 && capsLockEnabled !== null) {
                capsLockEnabled = !capsLockEnabled;
                console.log("Keydown. CapsLock enabled: " + capsLockEnabled.toString());
            } else if (e.which === 20) {
                console.log("Keydown. Initial state not set.");
            }
        });

        $(document).keypress(function(e) {
            const str = String.fromCharCode(e.which);
            if (!str || str.toLowerCase() === str.toUpperCase()) {
                console.log("Keypress. Some control key pressed.");
                return;
            }
            capsLockEnabled = (str.toLowerCase() === str && e.shiftKey) || (str.toUpperCase() === str && !e.shiftKey);
            console.log("Keypress. CapsLock enabled: " + capsLockEnabled.toString());
        });

        const passwordField = $("#password");

        passwordField.keyup(function(e) {
            checkWarning();
        });

        passwordField.on("focus", function(e) {
            checkWarning();
        });

        passwordField.on("blur", function(e) {
            console.log("Hiding warning");
            $("#warning").hide();
        });
    }
});