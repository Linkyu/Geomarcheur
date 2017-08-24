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

        .map_block, .place_list_block {
            height: 400px !important;
        }

        .place_list_block {
            overflow-y: scroll;
        }

        .map_block h1{
            width: 100%;
        }

        .map {
            height: 100%;
        }

        .modal_place_picture_block{
            max-width: 250px;
            height: 100%;
            max-height: 500px;
            overflow: hidden;
            display:inline-block;
            background: linear-gradient(to right, rgba(0,0,0,0) 0%,rgba(250, 250, 250, .75) 100%); /* W3C version */
            border-right: rgba(0, 0, 0, 0.3) solid 1px;
        }

        .modal_place_picture {
            width: auto;
            height: auto;
            position:relative;
            z-index:-1;
            display:block;
        }

        .modal_place_stats_block {
            margin-top: 50px;
        }
    </style>
</head>
<body onload='geocoder.geocode({address:city}, geocodeCallback);'>
<header>
    <nav class="nav-extended pink darken-3">
        <div class="nav-wrapper">
            <a href="#" class="brand-logo">Géomarcheur</a>
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="#">Tableau de bord</a></li>
                <li><a href="#">Classement</a></li>
                <li><a href="#">Statistiques</a></li>
            </ul>
        </div>
        <div class="nav-content">
            <ul class="tabs tabs-transparent">
                <li class="tab"><a href="#dashboard">Tableau de bord</a></li>
                <li class="tab"><a href="#leaderboard">Classement</a></li>
                <li class="tab"><a href="#statistics">Statistiques</a></li>
            </ul>
        </div>
    </nav>
</header>

<div id="dashboard" class="container">
    <!-- Page Content goes here -->

    <div class="row">
        <div class="col s6"><div class="card-panel hoverable map_block">
                <div class="valign-wrapper center-align map"><h1>M A P</h1></div>
            </div></div>
        <div class="col s6"><div class="card-panel hoverable place_list_block">
                <div class="row">
                    <form class="fullwidth">
                        <div class="input-field">
                            <i class="material-icons prefix">search</i>
                            <input id="modal_place_name_input" type="text">
                            <label for="modal_place_name_input">Rechercher</label>
                        </div>
                    </form>
                    <div id="place_list_container" class="fullwidth">
                        <div id="place_list" class="collection">
                            <!-- Generated content; see script below -->
                        </div>
                    </div>
                    <div id="place_list_message"></div>
                </div>
            </div></div>
        <div class="col s6"><div class="card-panel hoverable">
                <table class="highlight responsive-table">
                    <thead>
                    <tr>
                        <th><!-- Avatar --></th>
                        <th>Nom</th>
                        <th>Nombre de lieux</th>
                        <th>Crédits possédés</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i></td>
                        <td>Dave Grohl</td>
                        <td>25</td>
                        <td>¢6985342</td>
                    </tr>
                    <tr>
                        <td><i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i></td>
                        <td>Eric Clapman</td>
                        <td>50</td>
                        <td>¢840</td>
                    </tr>
                    <tr>
                        <td><i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i></td>
                        <td>Bob Dylan</td>
                        <td>10</td>
                        <td>¢420</td>
                    </tr>
                    <tr>
                        <td><i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i></td>
                        <td>Slash</td>
                        <td>123</td>
                        <td>¢359</td>
                    </tr>
                    <tr>
                        <td><i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i></td>
                        <td>Christophe Mae</td>
                        <td>10</td>
                        <td>¢200</td>
                    </tr>
                    </tbody>
                </table>
                <div class="right-align"><a class="waves-effect waves-light btn indigo darken-4">Voir le classement</a></div>
            </div></div>
        <div class="col s6"><div class="card-panel hoverable">
                <div id="linechart_material"></div>
            </div></div>
    </div>


    <!-- Modal Placeholder Trigger -->
    <a class="waves-effect waves-light btn modal-trigger" href="#place_modal">Modal</a>

    <!-- Place detail Modal Structure -->
    <div id="place_modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <div class="row">
                <!-- Place picture -->
                <div class="col s3 modal_place_picture_block">
                    <img class="modal_place_picture" src="http://i.imgur.com/kzmgUyK.jpg">
                </div>

                <!-- Place form -->
                <form class="col s6">
                    <div class="input-field">
                        <i class="material-icons prefix">local_offer</i>
                        <input id="modal_place_name_input" type="text">
                        <label for="modal_place_name_input">Nom</label>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">place</i>
                        <input id="modal_place_address_input" type="text">
                        <label for="modal_place_address_input">Adresse</label>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">gps_fixed</i>
                            <input id="modal_place_latitude_input" type="text">
                            <label for="modal_place_latitude_input">Latitude</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="modal_place_longitude_input" type="text">
                            <label for="modal_place_longitude_input">Longitude</label>
                        </div>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">account_circle</i>
                        <input disabled id="modal_place_owner_input" type="text">
                        <label for="modal_place_owner_input">Propriétaire</label>
                    </div>
                    <div class="input-field">
                        <span class="credit_symbol prefix">¢</span>
                        <input id="modal_place_value_input" type="text">
                        <label for="modal_place_value_input">Valeur</label>
                    </div>
                </form>

                <!-- Place delete button + stats preview -->
                <div class="col s3">
                    <a href="#!" class="btn waves-effect waves-light red darken-4 grey-text text-lighten-5 fullwidth"><i class="material-icons grey-text text-lighten-5 left">delete</i>Supprimer</a>

                    <!-- Stats preview -->
                    <div class="card small modal_place_stats_block">
                        <div class="card-image">
                            <img src="http://i.imgur.com/0uABqwN.png">
                            <span class="card-title">_ 3,045 ¢</span> <!-- Current amount of credits gained from this place -->
                        </div>
                        <div class="card-content">
                            <p class="bold">Crédits obtenus ici</p>
                        </div>
                        <div class="card-action">
                            <a href="#" class="pink-text text-darken-3">Détails</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect btn-flat pink-text text-darken-3">Retour</a>
            <a href="#!" class="modal-action modal-close waves-effect btn-flat pink-text text-darken-3">Sauvegarder les modifications</a>
        </div>
    </div>

</div>

<div id="leaderboard" class="container">
    <div class="card-panel hoverable">
        <div class="row"><table class="highlight responsive-table col s12">
                <thead>
                <tr>
                    <th><!-- Avatar --></th>
                    <th>Nom</th>
                    <th>Nombre de lieux</th>
                    <th>Crédits possédés</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i></td>
                    <td>Dave Grohl</td>
                    <td>25</td>
                    <td>¢6985342</td>
                </tr>
                <tr>
                    <td><i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i></td>
                    <td>Eric Clapman</td>
                    <td>50</td>
                    <td>¢840</td>
                </tr>
                <tr>
                    <td><i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i></td>
                    <td>Bob Dylan</td>
                    <td>10</td>
                    <td>¢420</td>
                </tr>
                <tr>
                    <td><i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i></td>
                    <td>Slash</td>
                    <td>123</td>
                    <td>¢359</td>
                </tr>
                <tr>
                    <td><i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i></td>
                    <td>Christophe Mae</td>
                    <td>10</td>
                    <td>¢200</td>
                </tr>
                </tbody>
            </table>

            <ul class="pagination center-align col s12">
                <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
                <li class="active pink darken-3"><a href="#!">1</a></li>
                <li class="waves-effect"><a href="#!">2</a></li>
                <li class="waves-effect"><a href="#!">3</a></li>
                <li class="waves-effect"><a href="#!">4</a></li>
                <li class="waves-effect"><a href="#!">5</a></li>
                <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
            </ul></div>

    </div>
</div>

<div id="statistics" class="container">
    <div class="row">
        <div class="col s6"><div class="card-panel hoverable">
                <div id="linechart_material1"></div>
            </div></div>
        <div class="col s6"><div class="card-panel hoverable">
                <div id="linechart_material2"></div>
            </div></div>
        <div class="col s6"><div class="card-panel hoverable">
                <div id="linechart_material3"></div>
            </div></div>
        <div class="col s6"><div class="card-panel hoverable">
                <div id="linechart_material4"></div>
            </div></div>
    </div>
</div>

<footer class="page-footer pink darken-3">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="grey-text text-lighten-5">Footer Content</h5>
                <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
            </div>
            <div class="col l4 offset-l2 s12">
                <h5 class="grey-text text-lighten-5">Links</h5>
                <ul>
                    <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            © 2017 Kiantic
            <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
        </div>
    </div>
</footer>
</body>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!-- Compiled and minified Materialize JavaScript -->
<script src="<?php echo base_url(); ?>static/js/materialize.min.js"></script>
<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY ?>" type="text/javascript"></script>
<!-- Charts API + placeholder data -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['line']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        let data = new google.visualization.DataTable();
        data.addColumn('number', 'Day');
        data.addColumn('number', 'Dave Grohl');
        data.addColumn('number', 'Eric Clapman');
        data.addColumn('number', 'Bob Dylan');

        data.addRows([
            [1,  37.8, 80.8, 41.8],
            [2,  30.9, 69.5, 32.4],
            [3,  25.4,   57, 25.7],
            [4,  11.7, 18.8, 10.5],
            [5,  11.9, 17.6, 10.4],
            [6,   8.8, 13.6,  7.7],
            [7,   7.6, 12.3,  9.6],
            [8,  12.3, 29.2, 10.6],
            [9,  16.9, 42.9, 14.8],
            [10, 12.8, 30.9, 11.6],
            [11,  5.3,  7.9,  4.7],
            [12,  6.6,  8.4,  5.2],
            [13,  4.8,  6.3,  3.6],
            [14,  4.2,  6.2,  3.4]
        ]);

        const options = {
            chart: {
                title: 'Crédits des 3 meilleurs joueurs',
                subtitle: 'en crédits'
            },
            width: '100%',
            height: 'auto'
        };

        let chart = new google.charts.Line(document.getElementById('linechart_material'));

        chart.draw(data, google.charts.Line.convertOptions(options));
    }
</script>

<!-- Custom scripts -->
<script type="application/javascript">
    $(document).ready(function() {
        const place_list = $("#place_list");

        // Place data retrieval
        $.getJSON("getPlace/asc", "", function (result) {
            $.each(result, function (i, places) {
                // TODO: Make a better overfllow rule
                if (places.length === 0) {
                    $("#place_list_message").html("<p>Il n'existe aucun lieu actuellement! Pour créer un lieu, cliquez la où vous souhaitez créer un lieu sur la carte, ou entrez l'adresse directement dans le champ de recherche ci-dessus puis suivez les instructions.</p>")
                } else {
                    $.each(places, function (j, place) {
                        place_list.append(`
                        <a href="#" class="collection-item avatar grey-text text-darken-4 place_item">
                          <img class="place_picture circle" src="` + ((place["picture"] === null) ? 'https://maps.googleapis.com/maps/api/streetview?size=250x250&fov=70&location=' + place["lat"] + ',' + place["lng"] + '&key=<?php echo GOOGLE_API_KEY ?>' : place["picture"]) + `" alt="">
                          <span class="place_id">` + place["id"] + `</span>
                          <p class="place_name title">` + place["name"] + `</p>
                          <p class="place_location">` + ((place["address"] === null) ? place["lat"] + ', ' + place["lng"] : place["address"]) + `</p>
                          <p class="place_value secondary-content pink-text text-darken-3"><span class="credit_symbol">¢</span>` + place["value"] + `</p>
                        </a>`);
                    });
                }
            });
        });

        // Place details display
        $('.modal').modal();
        $('#place_modal').modal('open');

        // Search function
        $("#search-input").keyup(function(){

            // Retrieve the input field text and reset the count to zero
            let filter = $(this).val();
            let count = 0;
            const message_box = $("#place_list_message");

            // Loop through the list
            $(".place_item").each(function(){

                // If the list item does not contain the text phrase fade it out
                if ($(this).find(".place_name, .place_location").text().search(new RegExp(filter, "i")) < 0) {
                    $(this).fadeOut();

                    // Show the list item if the phrase matches
                } else {
                    $(this).fadeIn();
                    count++;
                }
            });
            if (count === 0) {
                message_box.html("<p>Aucun résultat.</p>");
                message_box.fadeIn();
            } else {
                message_box.html("");
            }
        });
    });
</script>
<!-- Map placeholder -->
<script>
    let geocoder = new google.maps.Geocoder();
    let map;
    let markers = [];
    let spots = [];
    const city = "Toulouse";
    let info = new google.maps.InfoWindow();

    function set_info_content(id) {
        return "<p style='font-weight: bold'>" + spots[id].name + "</p>" +
            "<p>" + spots[id].address +"</p>" +
            "<p>Vélos disponibles : " + spots[id].available_bikes + "/" + spots[id].bike_stands + "<br />" +
            "Places disponibles : " + spots[id].available_bike_stands + "</p>";
    }

    function add_velib_markers(data) {
        let spot;
        for(let i=0;i<data.length;i++) {
            spot = data[i];
            spots[spot.number.toString()] = spot;

            let color = get_marker_color(spot);

            let marker = new google.maps.Marker({
                position: new google.maps.LatLng(spot.position.lat, spot.position.lng),
                icon: color,
                title: spot.number.toString()});

            marker.setMap(map);

            markers[marker.title] = marker;

            marker.addListener('click', function() {
                info.setContent(set_info_content(this.title));
                //console.log(spots[this.title]);
                info.open(map, this);
            });
        }

        setTimeout(get_stations, 1000);
    }

    function update_velib_markers(data) {
        let spot;
        for(let i=0;i<data.length;i++) {
            spot = data[i];
            let color = get_marker_color(spot);
            let marker = markers[spot.number.toString()];
            marker.setIcon(color);
        }

        // setTimeout(get_stations, 1000);
    }

    function get_marker_color(spot) {
        if (spot.status !== "OPEN"){
            return 'http://maps.google.com/mapfiles/ms/icons/grey.png';
        } else {
            if (spot.available_bikes < "2") {
                return 'http://maps.google.com/mapfiles/ms/icons/red.png';
            } else if (spot.available_bike_stands < "2") {
                return 'http://maps.google.com/mapfiles/ms/icons/orange.png';
            } else {
                return 'http://maps.google.com/mapfiles/ms/icons/green.png';
            }
        }
    }

    function display_map(coords) {
        const mapOptions = {
            center: coords,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map"), mapOptions);

        get_stations();
    }

    function geocodeCallback(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            display_map(results[0].geometry.location);
        } else {
            alert("Geocode error: " + status);
        }
    }

    function get_stations() {
        $.ajax({
            url: "https://api.jcdecaux.com/vls/v1/stations?contract=" + city + "&apiKey=90866e021aaa6ed8790cd89b85864c9ab5dbfed3",
            type: "GET",
            dataType: "json", // optionnel : format que je souhaite en réponse. si pas le cas je partirai en erreur!
            success: get_stations_success, // fonction de callback
            error: function(data) {
                console.log("Erreur Ajax :" + data);
            }
        });
    }

    function get_stations_success(data) {
        // je récupere le résultat de l'appel webservices
        console.log(data);
        if (spots.length > 0) {
            update_velib_markers(data);
        } else {
            add_velib_markers(data);
        }
    }
</script>
</html>
