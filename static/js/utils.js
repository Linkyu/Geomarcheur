function get_user(id, callback) {
    $.getJSON( "getUser/" + id, "", function( result ) {
        $.each(result, function(i, users) {
            // If somehow the user doesn't exist
            if (users.length === 0) {
                console.error('The user at id:' + id + ' does not exist.');
                callback(null);   // TODO: Create dedicated exceptions
            }

            callback(users[0]);
        });
    });
}

function get_place(id, callback) {
    $.getJSON( "getPlace/" + id, "", function( result ) {
        $.each(result, function(i, places) {
            // If somehow the place doesn't exist
            if (places.length === 0) {
                console.error('The place at id:' + id + ' does not exist.');
                callback(null);   // TODO: Create dedicated exceptions
            }

            callback(places[0]);
        });
    });
}

function get_user_places(id, callback) {
    $.getJSON( "getUserPlaces/" + id, "", function( result ) {
        $.each(result, function(i, places) {
            callback(places);
        });
    });
}

// Adapted by Joe Freeman from Christian Sanchez's port, 2010-2013
// Made compliant to ECMAScript6 by Kévin Guiraud, 2017
function stringToHSLA(str, alpha=1) {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }

    return "hsl(" + hash % 360 + ", 100%, 40%, " + alpha + ")";
}