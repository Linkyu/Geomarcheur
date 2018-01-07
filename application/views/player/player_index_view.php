<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Géomarcheur</title>

    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/materialize.min.css"
          media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/animate.css"
          media="screen,projection"/>
    <!--Import custom css files-->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/input_color_override.css"
          media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/style.css"
          media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


    <style>
        body {
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        #map {
            height: 83vh;
            width: 100%;
        }

        #player_page_footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            white-space: nowrap;
        }

        .big_symbol {
            font-size: 1.3em;
            font-weight: bold;
        }

        .gold {
            text-shadow: -1px -1px 0 #ff6d00,
            1px -1px 0 #ff6d00,
            -1px 1px 0 #ff6d00,
            1px 1px 0 #ff6d00;
        }

        .profile_modal-header {
            padding: 4px 6px;
            height: 64px;
            width: 100%;
            background-color: #ad1457;
        }

        #profile_modal_quote {
            font-size: 1.2em;
            font-style: italic;
        }

        #profile_modal_bio {
            color: #777;
        }
    </style>
</head>
<body class="pink darken-3">
<!-- Header navbar -->
<header class="navbar-fixed">
    <nav>
        <div class="nav-wrapper pink darken-3">
            <a href="#!" class="brand-logo align-center">Géomarcheur</a>
        </div>
    </nav>
</header>

<main class="center-align">
    <div id="map"></div>
</main>

<!-- Footer navbar -->
<footer id="player_page_footer" class="page-footer pink darken-3">
    <div class="container">
        <div class="row">
            <div class="col s3 center-align">
                <a class="waves-effect waves-light btn-flat modal-trigger white-text gold" href="#ranking_modal"
                   id="ranking_button">
                    <sup class="big_symbol">#</sup>
                    <span id="player_rank_footer"></span>
                </a>
            </div>

            <div class="col s3 center-align">
                <a class="waves-effect waves-light btn-flat modal-trigger white-text" href="#place_list_modal"
                   id="place_list_button">
                    <sup><i class="material-icons">place</i></sup>3
                </a>
            </div>
            <div class="col s3 center-align">
                <a class="waves-effect waves-light btn-flat modal-trigger white-text gold" href="#modal_detail_user"
                   id="credits_button" onclick="display_profile(<?php echo $_SESSION['user_id'] ?>)">
                    <sup class="big_symbol">¢</sup>
                    <span id="player_credits_footer"></span>
                </a>
            </div>
            <div class="col s3 right-align">
                <a href="#" class="waves-effect waves-light circle white-text">
                    <a class="waves-effect waves-light btn-flat modal-trigger white-text" id="logout_button"
                       onclick="logout();">
                        <i class="material-icons" style="font-size: large">power_settings_new</i>
                    </a>
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- ### Modals ### -->
<div id="ranking_modal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <div class="card-panel hoverable">
            <div class="row">
                <table id="ranking_datatable" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Crédits</th>
                        <th>Lieux possédés</th>
                    </tr>
                    </thead>
                    <tbody id="datatable_leaderboard">
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Crédits</th>
                        <th>Lieux possédés</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- la modale des détails d'un utilisateur -->
<div id="modal_detail_user" class="modal modal-fixed-footer">
    <div class="modal-content">
        <div class="row">
            <div class="col s3">
                <div id="card_user_pic" class="card small modal_place_stats_block">
                    <div class="card-content">
                        <p class="bold">Photo de l'utilisateur</p>
                    </div>
                </div>
                <div style="text-align: center;">
                    <span id="player_quote"></span>
                </div>
            </div>

            <div class="col s9">
                <div class="row">
                    <div class="col s6">
                        <span class="bold">Nom</span>
                        <br>
                        <span id="player_name"></span>
                    </div>
                    <div class="col s2">
                        <span class="bold">#</span>
                        <br>
                        <span id="player_position"></span>
                    </div>
                    <div class="col s2">
                        <span class="bold">Crédits</span>
                        <br>
                        <span id="player_credits"></span> <span id="little_credit_symbol"
                                                                class="credit_symbol prefix">¢</span>
                    </div>
                </div>
                <div class="row">

                    <div class="row"></div>

                    <div class="col s8">
                        <span class="bold">Bio</span>
                        <br>
                        <span id="player_bio"></span>
                    </div>

                    <div class="col s4">
                        <span class="bold">Lieu(x) possédé(s)</span>
                        <br>
                        <span id="player_places"></span>
                        <!-- liste sur les lieux possédés -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <a class="waves-effect waves-light btn-large #f44336 red">BANNIR</a>
        <a href="#!" class="modal-action modal-close waves-effect btn-flat pink-text text-darken-3"
           onclick="idPlace = '';">Retour</a>

    </div>


</div>

<!-- Places -->
<div id="place_list_modal" class="modal modal-fixed-footer">
    <div class="modal-header">
        <!-- Standard header -->
        <div class="row valign-wrapper" id="searchbar-switch1">
            <div class="col s10">
                <h5>Liste de vos lieux</h5>
            </div>
            <div class="col s1">
                <a href="#" class="waves-effect circle pink-text text-darken-3" id="search-place">
                    <i class="material-icons" style="font-size: large">search</i>
                </a>
            </div>
            <div class="col s1">
                <a href="#" class="dropdown-button waves-effect circle pink-text text-darken-3"
                   data-activates="sort_dropdown">
                    <i class="material-icons" style="font-size: large">sort</i>
                </a>

                <!-- Dropdown Structure for sorting -->
                <ul id="sort_dropdown" class="dropdown-content">
                    <li><a href="#!" class="grey-text text-darken-4" id="sort_by_name"><i
                                    class="material-icons pink-text text-darken-3">sort_by_alpha</i>Trier par nom</a>
                    </li>
                    <li><a href="#!" class="grey-text text-darken-4" id="sort_by_value"><i
                                    class="material-icons pink-text text-darken-3">format_list_numbered</i>Trier par
                            valeur</a></li>
                </ul>
            </div>
        </div>

        <!-- Search header -->
        <div class="row valign-wrapper hide animated flipOutX" id="searchbar-switch2">
            <div class="col s1">
                <a href="#" class="waves-effect circle pink-text text-darken-3 searchbar-switch" id="search-place-back">
                    <i class="material-icons" style="font-size: large">arrow_back</i>
                </a>
            </div>
            <div id="search-place-searchbar" class="col s10">
                <form>
                    <div class="input-field">
                        <i class="material-icons prefix">search</i>
                        <input id="search-input" type="text" class="validate">
                        <label for="search-input">Search</label>
                    </div>
                </form>
            </div>
            <!-- No sorting on search page for now -->
            <!--
            <div class="col s1">
                <a href="#" class="dropdown-button waves-effect circle pink-text text-darken-3" data-activates="sort_dropdown">
                    <i class="material-icons" style="font-size: large">sort</i>
                </a>

                <!-- Dropdown Structure for sorting ->
                <ul id="sort_dropdown" class="dropdown-content">
                    <li><a href="#!" class="grey-text text-darken-4 sort_by_name"><i class="material-icons pink-text text-darken-3">sort_by_alpha</i>Trier par nom</a></li>
                    <li><a href="#!" class="grey-text text-darken-4 sort_by_value"><i class="material-icons pink-text text-darken-3">format_list_numbered</i>Trier par valeur</a></li>
                </ul>
            </div>-->
        </div>
    </div>
    <div class="modal-content">
        <div class="col s12 m7" id="place_list_table"></div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect btn-flat">Fermer</a>
    </div>
</div>

<!-- Profile -->
<div id="profile_modal" class="modal modal-fixed-footer">
    <div class="profile_modal-header center-align valign-wrapper">
        <i class="material-icons white-text" style="width: 100%;">account_circle</i>

        <h5 class="right white-text nowrap"><sup class="big_symbol">#</sup><span id="profile_modal_rank"></span></h5>
    </div>
    <div class="modal-content">


        <div class="col s10 m10">
            <label for="input_profile_modal_pseudo">Pseudo</label>
            <input required="required" readonly id="input_profile_modal_pseudo" name="input_profile_modal_pseudo"
                   type="text" function="check_form(this.value)">
        </div>

        <div class="col s2 m2">
            <p class="right"><span><i class="material-icons">place</i></span><span id="profile_modal_places"></span></p>
        </div>

        <label for="input_profile_modal_quote">Quote</label>
        <input id="input_profile_modal_quote" name="input_profile_modal_quote" type="text">

        <label for="input_profile_modal_bio">Bio</label>
        <input id="input_profile_modal_bio" name="input_profile_modal_bio" type="text">


    </div>
    <div class="modal-footer">

        <a href="#!" onclick='update_profile(<?php echo $_SESSION['user_id'] ?>)' class="waves-effect btn-flat"
           id="profile_modal_save_button">Sauvegarder</a>

        <a href="#!" class="modal-action modal-close waves-effect btn-flat">Fermer</a>
    </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="<?php echo base_url(); ?>static/js/materialize.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>static/js/utils.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY ?>&libraries=geometry"
        type="text/javascript"></script>

<!-- Custom local scripts -->
<script type="text/javascript">

    // Global constants and variables
    let PlayerData = {
        id: "<?php echo $_SESSION['user_id'] ?>",
        loc: {
            lat: 43.6,
            lng: 1.44   // Placeholder
        }
    };

    let markers = [];
    let places_ids = [];
    let mapClickListenerSet = false;

    const PlayerMarkerIcon = {
        url: "<?php echo base_url(); ?>static/img/pin.svg",
        anchor: new google.maps.Point(25, 50),
        scaledSize: new google.maps.Size(50, 50)
    };
    const OthersMarkerIcon = {
        url: "<?php echo base_url(); ?>static/img/search_pin.svg",
        anchor: new google.maps.Point(25, 50),
        scaledSize: new google.maps.Size(50, 50)
    };
    const FreeMarkerIcon = {
        url: "<?php echo base_url(); ?>static/img/free_pin.svg",
        anchor: new google.maps.Point(25, 50),
        scaledSize: new google.maps.Size(50, 50)
    };

    $(document).ready(function () {
        get_user(PlayerData['id'], function (player) {
            PlayerData['pseudo'] = player['pseudo'];
            PlayerData['credits'] = player['credits'];
        });

        const place_list_table = $("#place_list_table");
        const divs = place_list_table.find("div.card");
        let alpha_order = false;

        const starting_position = new google.maps.LatLng(43.6000, 1.44333);  // Set to Toulouse for development
        const options = {
            center: starting_position,
            zoom: 14,
            mapTypeId: google.maps.MapTypeId.roadmap,
            backgroundColor: "#AD1457",
            disableDefaultUI: true,
            clickableIcons: false,
            styles: [
                {
                    "featureType": "all",
                    "stylers": [
                        {"visibility": "off"}
                    ]
                }, {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [
                        {"color": "#A18A95"},
                        {"visibility": "on"}
                    ]
                }, {
                    "featureType": "landscape.man_made",
                    "elementType": "geometry",
                    "stylers": [
                        {"visibility": "on"},
                        {"hue": "#AD1457"},
                        {"saturation": "50"},
                        {"lightness": "-60"},
                        {"weight": "3"}
                    ]
                }, {
                    "featureType": "landscape.natural",
                    "elementType": "geometry",
                    "stylers": [
                        {"color": "#4E6D44"},
                        {"visibility": "on"}
                    ]
                }, {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [
                        {"color": "#FF7F00"},
                        {"visibility": "on"}
                    ]
                }, {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {"color": "#384E79"},
                        {"visibility": "on"}
                    ]
                }
            ]
        };
        let player_map = new google.maps.Map(document.getElementById("map"), options);

        // Initialise player marker
        PlayerData["marker"] = new google.maps.Marker(
            {
                position: new google.maps.LatLng(PlayerData["loc"]["lat"], PlayerData["loc"]["lng"]),
                map: player_map,
                icon: {
                    url: "<?php echo base_url(); ?>static/img/located.svg",
                    anchor: new google.maps.Point(50, 50),
                    scaledSize: new google.maps.Size(50, 50)
                }
            });

        refreshMarkers(player_map);

        refreshRanking();

        refreshCredits();

        refreshLocation();

        $("#place_list_modal").modal({
            dismissible: true, // Modal can be dismissed by clicking outside of the modal
            opacity: .5, // Opacity of modal background
            inDuration: 300, // Transition in duration
            outDuration: 200, // Transition out duration
            startingTop: '4%', // Starting top style attribute
            endingTop: '10%', // Ending top style attribute
            ready: function (modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
                $.getJSON("getPlace", "", function (result) {
                    $.each(result, function (i, places) {
                        console.log("lieu" + places);
                        // TODO: Find a way to keep the image at a consistent size => normaliser le fichier d'entrée pour le wrapper dans un div
                        // TODO: Sort function
                        // TODO: Clean the animation. See issue #59
                        if (places.length === 0) {
                            place_list_table.append('<p>Vous ne possédez aucun lieu actuellement! Pour capturer un lieu, approchez-vous de celui-ci, appuyez dessus sur la carte puis appuyez sur Acheter.</p>')
                        }
                        $.each(places, function (j, place) {
                            place_list_table.append(`
                            		<div class="card horizontal">
                            		<div class="card-image">
                            		<img src="` + ((place["picture"] === null) ? 'https://maps.googleapis.com/maps/api/streetview?size=150x250&fov=70&location=' + place["lat"] + ',' + place["lng"] + '&key=<?php echo GOOGLE_API_KEY ?>' : place["picture"]) + `">
                            		<a class="btn-floating halfway-fab-right waves-effect waves-light pink darken-3"><i class="material-icons">visibility</i></a>
                            		</div>
                            		<div class="card-stacked">
                            		<div class="card-content">
                            		<span class="card-title place_name">` + place["name"] + `</span>
                            		<p class="place_location">` + ((place["address"] === null) ? place["lat"] + ', ' + place["lng"] : place["address"]) + `</p>
                            		<p class="place_value">¢` + place["value"] + `</p>
                            		</div>
                            		<div class="card-action right-align">
                            		<a href="#" class="waves-effect btn-flat pink-text text-darken-3">Vendre</a>
                            		</div>
                            		</div>
                            		</div>`);
                        });
                    });
                });
            },
            complete: function () {
                place_list_table.text('');  // Empty the list
                $('#search-input').val(''); // Empty the search

                // Reset the header
                let switch1 = $('#searchbar-switch1');
                let switch2 = $('#searchbar-switch2');
                const animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

                switch2.addClass('animated flipOutX').one(animationEnd, function () {
                    $(this).removeClass('animated;');
                    $(this).addClass('hide');
                    switch1.removeClass('flipOutX hide');
                    switch1.addClass('flipInX').one(animationEnd, function () {
                        $(this).removeClass('animated');
                    });
                });
            }
        });

        $('#search-place').on('click', function () {
            let switch1 = $('#searchbar-switch1');
            let switch2 = $('#searchbar-switch2');
            const animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

            switch1.addClass('animated flipOutX').one(animationEnd, function () {
                $(this).removeClass('animated;');
                $(this).addClass('hide');
                switch2.removeClass('flipOutX hide');
                switch2.addClass('flipInX').one(animationEnd, function () {
                    $(this).removeClass('animated');
                });
            });
        });

        $('#search-place-back').on('click', function () {
            let switch1 = $('#searchbar-switch1');
            let switch2 = $('#searchbar-switch2');
            const animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

            switch2.addClass('animated flipOutX').one(animationEnd, function () {
                $(this).removeClass('animated;');
                $(this).addClass('hide');
                switch1.removeClass('flipOutX hide');
                switch1.addClass('flipInX').one(animationEnd, function () {
                    $(this).removeClass('animated');
                });
            });

            $('#search-input').val(''); // Empty the search

            // Show all the cards again
            $(".card").each(function () {
                $(this).fadeIn();
            });
        });

        $("#search-input").keyup(function () {

            // Retrieve the input field text and reset the count to zero
            let filter = $(this).val();

            // Loop through the list
            $(".card").each(function () {

                // If the list item does not contain the text phrase fade it out
                if ($(this).find(".place_name, .place_location").text().search(new RegExp(filter, "i")) < 0) {
                    $(this).fadeOut();

                    // Show the list item if the phrase matches
                } else {
                    $(this).fadeIn();
                }
            });
        });

        // These don't work well, they have to be replaced.
        $('#sort_by_name').on('click', function () {
            alpha_order = !alpha_order;
            let alphabeticallyOrderedDivs = divs.sort(function (a, b) {
                let name1 = $(a).find(".place_name").text().toLowerCase();
                let name2 = $(b).find(".place_name").text().toLowerCase();
                return (alpha_order ? name1 > name2 : name1 < name2);
            });
            console.log(alphabeticallyOrderedDivs);
            $("#place_list_table").html(alphabeticallyOrderedDivs);
        });

        $('#sort_by_value').on('click', function () {
            let numericallyOrderedDivs = $("div.card").sort(function (a, b) {
                return $(a).find(".place_value").text() > $(b).find(".place_value").text();
            });
            $("#container").html(numericallyOrderedDivs);
        });
    });


    function sellPlace(id) {
        if (confirm("Êtes-vous sûr?")) {
            $.ajax({
                url: "sellPlace/" + id,
                success: function () {
                    alert("Vendu!");
                    // TODO: Display these with modals? See issue #57
                    // TODO: Update infowindow bubble. See issue #58
                }
            });
        }
    }

    function stopAllBouncingMarkers() {
        $.each(markers, function (i, marker) {
            markers[i].setAnimation(null);
        })
    }

    function givePoint(placeId) {
        $.ajax({
            url: "givePoint/",
            type: "POST",
            data: {
                placeId: placeId,
                userId: PlayerData['id']
            }
        });
    }

    function eraseMarker(markerId) {
        markers[markerId].setMap(null);
        delete places_ids[markerId];
    }

    function eraseAllMarkers() {
        $.each(markers, function (i, marker) {
            markers[i].setMap(null);
        });
        markers = [];
    }

    function refreshMarkers(map) {
        let infowindow = new google.maps.InfoWindow();
        const playerLoc = new google.maps.LatLng(PlayerData["loc"]["lat"], PlayerData["loc"]["lng"]);

        $.getJSON("getPlace", "", function (result) {
            $.each(result, function (i, places) {
                $.each(places, function (j, place) {
                    // No point in creating markers if they are out of scope (be it range, ownership, or value)
                    const placeLoc = new google.maps.LatLng(place.lat, place.lng);
                    const markerDistance = google.maps.geometry.spherical.computeDistanceBetween(placeLoc, playerLoc);
                    //console.log("distance to " + place.name + ": " + markerDistance);

                    if (place.id_User === PlayerData['id'] ||
                        ((place.id_User !== null || (place.id_User === null && Number(place.value) <= Number(PlayerData["credits"]))) && markerDistance < 30000)) {

                        // Give a point to the owner
                        if (place.id_User !== PlayerData['id'] && place.id_User !== null && markerDistance < 50) {
                            givePoint(place.id);
                        }


                        // Apply the correct marker icon
                        let marker_icon = "";
                        if (place.id_User === null) {
                            marker_icon = FreeMarkerIcon;
                        } else if (place.id_User === PlayerData['id']) {
                            marker_icon = PlayerMarkerIcon;
                        } else {
                            marker_icon = OthersMarkerIcon;
                        }

                        // Create the marker if it doesn't already exist
                        if (places_ids.find(function(item){return item === place.place_id}) === undefined) {
                            markers[place.place_id] = new google.maps.Marker(
                                {
                                    position: new google.maps.LatLng(place.lat, place.lng),
                                    title: 'Nom du lieu : ' + place.name,
                                    icon: marker_icon
                                }
                            );
                            markers[place.place_id].setMap(map);
                            places_ids.push(place.place_id);
                        } else {
                            // Update the marker
                            markers[place.place_id].setIcon(marker_icon);
                        }


                        // Closure => création de la function au moment de la création du marqueur
                        let openMarkerInfowindow = function (ev) {
                            let action = "";
                            get_user(place.id_User, function (owner) {
                                get_user(PlayerData['id'], function (player) {
                                    // Determine the action on this place
                                    if (place.id_User === null && Number(player["credits"]) >= Number(place.value)) {
                                        action = "<a href='#' id='buy-button' onclick='buyPlace(" + place.id + ")' class='btn waves-effect pink darken-3'>Acheter</a> ";

                                    } else if (PlayerData['id'] === place.id_User) {
                                        action = "<a href='#' id='sell-button' onclick='sellPlace(" + place.id + ")' class='btn waves-effect pink darken-3'>Vendre</a> ";
                                    }

                                    const contentString =
                                        '<div id="content">' +
                                        '<div class="row">' +
                                        '<div class="col s12"><p><span style="font-weight: bold; font-size: large">' + place.name + '</span><br/>' +
                                        '<span style="font-style: italic; color: grey;">' + (place.address === null ? '' : place.address) + '</span></p></div>' +
                                        '</div>' +
                                        '<div class="row">' +
                                        (owner === null ? '' : '<div class="col s12"><p><i class="material-icons prefix pink-text text-darken-4">account_circle</i> <span class="pink-text text-darken-4" style="font-weight: bold;" onclick="display_profile(' + owner.id + ')">' + owner.pseudo + '</span></p></div>') + // TODO: Rewrite this; owner is still parsed by the checker at runtime
                                        '</div>' +
                                        '<div class="row"> ' +
                                        '<div class="col s6">' +
                                        '<p style="font-weight: bold" class="orange-text text-accent-4"><span class="credit_symbol prefix">¢</span>' + place.value + '</p>' +
                                        '<p>' + action + '</p>' +
                                        '</div>' +
                                        '<div class="col s6">' +
                                        '<img class="infowindow_place_picture" src="https://maps.googleapis.com/maps/api/streetview?size=100x150&fov=70&location=' + place.lat + ',' + place.lng + '&key=<?php echo GOOGLE_API_KEY ?>" style="width: 100px;">' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';
                                    map.panTo(markers[place.place_id].position);
                                    infowindow.setContent(contentString);
                                    infowindow.open(map, markers[place.place_id]);
                                });
                            });
                        };

                        let closeMarkerInfowindow = function (ev) {
                            markers[place.place_id].setAnimation(null);
                            infowindow.close();
                        };

                        // creation de listener qui apelle la function
                        google.maps.event.addListener(
                            markers[place.place_id], "click", openMarkerInfowindow
                        );
                        if (!mapClickListenerSet) {
                            google.maps.event.addListener(
                                map, "click", closeMarkerInfowindow
                            );
                            mapClickListenerSet = true;
                        }
                    } else {
                        google.maps.event.clearInstanceListeners(markers[place.place_id]);
                        eraseMarker(place.place_id);
                    }
                })
            })
        });

        setTimeout(refreshMarkers, 5000);
    }

    function updateBouncingMarkers() {
        const playerLoc = new google.maps.LatLng(PlayerData["loc"]["lat"], PlayerData["loc"]["lng"]);
        $.each(markers, function (i, marker) {
            if (google.maps.geometry.spherical.computeDistanceBetween(markers[i].position, playerLoc) <= 50) {
                markers[i].setAnimation(google.maps.Animation.BOUNCE);
            } else {
                markers[i].setAnimation(null);
            }
        })
    }

    function refreshLocation() {
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                PlayerData["loc"] = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                PlayerData["marker"].setPosition(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
                updateBouncingMarkers();

                setTimeout(refreshLocation, 1000);
            }, function () {
                handleLocationError(true);
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false);
        }
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
    }

    function display_profile(user_id) {
        get_user(user_id, function (user) {
            let player = user;
            $.getJSON("getUserRank/" + user_id, "", function (result) {
                player['rank'] = result['rank'];
                get_user_places(user_id, function (places) {
                    player['places'] = places;

                    let profile_modal = $('#profile_modal');
                    profile_modal.modal({
                        dismissible: true, // Modal can be dismissed by clicking outside of the modal
                        opacity: .5, // Opacity of modal background
                        inDuration: 300, // Transition in duration
                        outDuration: 200, // Transition out duration
                        startingTop: '4%', // Starting top style attribute
                        endingTop: '10%', // Ending top style attribute
                        ready: function (modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
                            $("#input_profile_modal_pseudo").val(player["pseudo"]);
                            $("#input_profile_modal_quote").val(player["quote"]);
                            $("#input_profile_modal_bio").val(player['bio']);

                            $("#profile_modal_pseudo").html(player['pseudo']);
                            $("#profile_modal_places").html(player['places'].length);
                            $("#profile_modal_quote").html('"' + player['quote'] + '"');
                            $("#profile_modal_bio").html(player['bio']);
                            $("#profile_modal_rank").html(player['rank']);

                            if (player['id'] !== PlayerData['id']) {
                                $("#profile_modal_save_button").addClass("hide");
                            }
                        },
                        complete: function (modal, trigger) {
                            $("#profile_modal_pseudo").html("");
                            $("#profile_modal_places").html("");
                            $("#profile_modal_quote").html("");
                            $("#profile_modal_bio").html("");
                            $("#profile_modal_rank").html("");
                            $("#profile_modal_save_button").removeClass("hide");
                        }
                    });

                    profile_modal.modal('open');
                })
            });
        });
    }

    function refreshCredits() {
        get_user(PlayerData['id'], function (player) {
            PlayerData['credits'] = player['credits'];
            $('#player_credits_footer').text(player['credits']);
        });
    }

    function refreshRanking() {
        $.getJSON("getUserRank/" + PlayerData['id'], "", function (result) {
            PlayerData['rank'] = result['rank'];
            $('#player_rank_footer').text(result['rank']);
        });
    }

    function logout() {
        $.ajax({url: "logout/"}
        ).done(function () {
            location.reload();
        });
    }


    //listAllUsers => fonction qui retourne les joueurs triés avec le classement....
    // envoyer ces données dans le datatable


    //@TODO : afficher la ligne du joueur connecté d'une autre couleur
    const users_detail_modal = $("#ranking_modal");
    users_detail_modal.modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        inDuration: 300, // Transition in duration
        outDuration: 200, // Transition out duration
        startingTop: '4%', // Starting top style attribute
        endingTop: '10%'
    });

    // Datatable setup
    let container = $('#ranking_datatable');
    container.DataTable({
        "language": {
            url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/French.json"
        },
        ajax: {
            url: 'getUser',
            dataSrc: "resultat"
        },
        columns: [
            {data: "id"},
            {data: "pseudo"},
            {data: "credits"},
            {data: "is_admin"}    // TODO: Change this to display the actual amount of places owned (probably a callback)
        ],
        "order": [[2, "desc"]]

    });

    let rows = $('#datatable_leaderboard');

    rows.on('click', 'tr', function () {

        let row = $(this);
        idUser = row[0].childNodes[0].textContent;
        console.log(idUser);

        let user_data;

        $.getJSON("getUser/" + idUser, "", function (result) {
            $.each(result, function (i, users) {
                $.each(users, function (j, user) {
                    //users_detail_modal.html("");
                    let player_name = $("#player_name").html(user.pseudo);
                    //TODO : creer la fonction + requete de qui va positionner le joueur
                    $("#player_position").html(user.credits);
                    $("#player_credits").html(user.credits);
                    $("#player_quote").html(user.quote);
                    $("#player_bio").html(user.bio);
                    // TODO : recupérer la photo des joueurs

                    //users_detail_modal.append(`
                    //<p>Nom de l'utilisateur : ` + user.pseudo + ` </p>
                    //<p>Nom de l'utilisateur : ` + user.credits + ` </p>
                    //`);
                    // recuperer les lieux de l'utilisateur where ID => machin
                    $.getJSON("getUserPlaces/" + idUser, "", function (result) {
                        let texte;

                        texte = "<ul>";
                        $.each(result, function (i, places) {
                            $.each(places, function (j, place) {
                                console.log("infos tableau" + places.length);

                                texte += "<li>" + place.name + "</li>";
                            });
                            if (places.length !== 0) {
                                return;
                            }
                            texte = "Aucun lieu."
                        });

                        texte += "</ul>";
                        $("#player_places").html(texte);
                        $("#player_number_place").html(result.length);

                    });
                    users_detail_modal.modal('open');

                })
            })
        });
    });

    function update_profile(id) {
        let pseudo = $("#input_profile_modal_pseudo").val();
        let quote = $("#input_profile_modal_quote").val();
        let bio = $("#input_profile_modal_bio").val();

        $.ajax({
            url: "editProfile/",
            type: "POST",
            data: {
                id: id, quote: quote, bio: bio
            },
            success: function () {
                alert("Profil modifié !");
            },
            error: function () {
                alert("Enregistrement échoué !");
            }
        }).done(function () {

            let profile_modal = $('#profile_modal');
            profile_modal.modal('close');
        });
    }

    function buyPlace(idPlace) {
        if (confirm("Êtes-vous sûr?")) {
             $.ajax({
                url: "buyPlace/",
                type: "GET",
                data: {
                    idUser: PlayerData["id"],
                    idPlace: idPlace
                },
                success: function () {
                    alert("Achat effectué !");
                },
                error: function () {
                    alert("Erreur lors de l'achat !");
                }
            });
        }
    }


</script>

</body>
</html>
