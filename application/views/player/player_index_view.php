<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$temp_user_id = 2;
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
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/animate.css"  media="screen,projection"/>
    <!--Import custom css files-->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/input_color_override.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/style.css"  media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>



    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        #map {
            height: 800px;
            width: 100%;
        }
    </style>
</head>
<body>
<!-- Header navbar -->
<header class="navbar-fixed">
    <nav>
        <div class="nav-wrapper pink darken-3">
            <a href="#!" class="brand-logo align-center">Géomarcheur</a>
        </div>
    </nav>
</header>

<main class="center-align">

    <div id="map">

    </div>


</main>

<!-- Footer navbar -->
<footer class="page-footer pink darken-3">
    <div class="container">
        <div class="row">
            <div class="col s3 center-align">
                Classement
            </div>
            <div class="col s4 center-align">
                <a class="waves-effect waves-light btn-flat modal-trigger white-text" href="#place_list_modal" id="place_list_button">
                    <i class="material-icons">place</i> 3
                </a>
            </div>
            <div class="col s4 center-align">
                Crédits
            </div>
            <div class="col s1 right-align">
                <a href="#" class="waves-effect waves-light circle white-text">
                    <i class="material-icons" style="font-size: large">more_vert</i>
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- Modals -->
<div id="place_list_modal" class="modal modal-fixed-footer">
    <div class="modal-header">
        <!-- Standard header -->
        <div class="row valign-wrapper" id="searchbar-switch1">
            <div class="col s10">
                <h4>Liste de vos lieux</h4>
            </div>
            <div class="col s1">
                <a href="#" class="waves-effect circle pink-text text-darken-3" id="search-place">
                    <i class="material-icons" style="font-size: large">search</i>
                </a>
            </div>
            <div class="col s1">
                <a href="#" class="dropdown-button waves-effect circle pink-text text-darken-3" data-activates="sort_dropdown">
                    <i class="material-icons" style="font-size: large">sort</i>
                </a>

                <!-- Dropdown Structure for sorting -->
                <ul id="sort_dropdown" class="dropdown-content">
                    <li><a href="#!" class="grey-text text-darken-4" id="sort_by_name"><i class="material-icons pink-text text-darken-3">sort_by_alpha</i>Trier par nom</a></li>
                    <li><a href="#!" class="grey-text text-darken-4" id="sort_by_value"><i class="material-icons pink-text text-darken-3">format_list_numbered</i>Trier par valeur</a></li>
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

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!-- Compiled and minified Materialize JavaScript -->
<script src="<?php echo base_url(); ?>static/js/materialize.min.js"></script>

<!-- Custom tools -->
<script src="<?php echo base_url(); ?>static/js/utils.js"></script>

<!-- Custom local scripts -->
<script type="text/javascript">


    function sellPlace(id) {
        if (confirm("Êtes-vous sûr?")) {
            $.ajax({
                url: "sellPlace/" + id,
                success: function () {
                    alert("Vendu!");
                    // TODO: Display these with modals?
                    // TODO: Update infowindow bubble
                }
            });
        }
    }
    $(document).ready(function() {
        const place_list_table = $("#place_list_table");
        const divs = place_list_table.find("div.card");
        let alpha_order = false;

        // WIP: Remove this once authentification is developped
        const playerId = "<?php echo $temp_user_id ?>";

        let map;
        let marqueur = [];
        const latlng = new google.maps.LatLng(43.600000, 1.433333);
        const options = {
            center: latlng,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.roadmap
        };
        map = new google.maps.Map(document.getElementById("map"), options);

        let infowindow = new google.maps.InfoWindow();


        function actualisePoint() {

            $.getJSON( "getPlace", "", function( result ) {
                    $.each(result, function(i, places) {
                        $.each(places, function(j, place) {

                                marqueur[j] = new google.maps.Marker (
                                    {
                                        position: new google.maps.LatLng(place.lat, place.lng),
                                        title:'Nom du lieu : ' + place.name
                                    }
                                );
                                marqueur[j].setMap(map);


                                // Closure => création de la function au moment de la création du marqueur
                                let macallback = function callbackSpecificiqueMarqueur(ev) {
                                    let action = "";
                                    get_user(place.id_User, function (owner) {
                                        get_user(playerId, function (player) {
                                            /*console.log("ownerId : " + ownerId);
                                            console.log("place.id_User : " + place.id_User);
                                            console.log("owner['credits'] : " + owner["credits"]);
                                            console.log("place.value : " + place.value);
                                            console.log("");
                                            console.log("place.id_User === null : " + place.id_User === null);
                                            console.log("owner['credits'] >= place.value : " + owner['credits'] >= place.value);
                                            console.log("ownerId === place.id_User : " + ownerId === place.id_User);
                                            console.log("###");*/
                                            if (place.id_User === null && Number(player["credits"]) >= Number(place.value)) {
                                                action = "<a href='#' class='btn waves-effect pink darken-3'>Acheter</a>";
                                            } else if (playerId === place.id_User) {
                                                action = "<a href='#' id='sell-button' onclick='sellPlace(" + place.id + ")' class='btn waves-effect pink darken-3'>Vendre</a> ";
                                            }

                                            const contentString =
                                                '<div id="content">' +
                                                    '<div class="row">' +
                                                        '<div class="col s12"><p><span style="font-weight: bold; font-size: large">' + place.name + '</span><br/>' +
                                                        '<span style="font-style: italic; color: grey;">' + (place.address === null ? '' : place.address) + '</span></p></div>' +
                                                    '</div>' +
                                                    '<div class="row">' +
                                                        (owner === null ? '' : '<div class="col s12"><p><i class="material-icons prefix pink-text text-darken-4">account_circle</i> <span class="pink-text text-darken-4" style="font-weight: bold;">' + owner.pseudo + '</span></div>') +
                                                    '</div>' +
                                                    '<div class="row"> ' +
                                                        '<div class="col s6">' +
                                                            '<p style="font-weight: bold"><span class="credit_symbol prefix">¢</span>' + place.value + '</p>' +
                                                            '<p>' + action + '</p>' +
                                                        '</div>' +
                                                        '<div class="col s6">' +
                                                            '<img class="infowindow_place_picture" src="http://i.imgur.com/kzmgUyK.jpg" style="width: 100px;">' +
                                                        '</div>' +
                                                    '</div>' +
                                                '</div>';

                                            infowindow.setContent(contentString);
                                            infowindow.open(map, marqueur[j]);
                                        });
                                    });
                                };

                                // creation de listener qui apelle la function ...
                                //console.log("Creation du listener", carte);
                                google.maps.event.addListener(
                                    marqueur[j], "click", macallback
                                );

                            }
                        )
                    })
                }
            )
        }

        actualisePoint();


        $(".modal").modal({
            dismissible: true, // Modal can be dismissed by clicking outside of the modal
            opacity: .5, // Opacity of modal background
            inDuration: 300, // Transition in duration
            outDuration: 200, // Transition out duration
            startingTop: '4%', // Starting top style attribute
            endingTop: '10%', // Ending top style attribute
            ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
                $.getJSON( "getPlace", "", function( result ) {
                    $.each(result, function(i, places) {
                        console.log("lieu" + places);
                        // TODO: Find a way to keep the image at a consistent size => normaliser le fichier d'entrée pour le wrapper dans un div
                        // TODO: Sort function
                        // TODO: Clean the animation
                        if (places.length === 0) {
                            place_list_table.append('<p>Vous ne possédez aucun lieu actuellement! Pour capturer un lieu, approchez-vous de celui-ci, appuyez dessus sur la carte puis appuyez sur Acheter.</p>')
                        }
                        $.each(places, function(j, place){
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
            complete: function() {
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
            $(".card").each(function(){
                $(this).fadeIn();
            });
        });

        $("#search-input").keyup(function(){

            // Retrieve the input field text and reset the count to zero
            let filter = $(this).val();

            // Loop through the list
            $(".card").each(function(){

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


</script>

<div id="carte" style="height: 800px;width:800px"></div>
<div id="resultat" style="height: 500px;width:500px"></div>
<!-- Todo : verifier la taille de la page  -->

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY ?>" type="text/javascript"></script>



</body>
</html>
