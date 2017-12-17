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