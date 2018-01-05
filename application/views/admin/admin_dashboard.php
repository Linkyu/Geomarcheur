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

        .map_block h1 {
            width: 100%;
        }

        .map {
            height: 100%;
        }

        .modal_place_picture_block {
            max-width: 250px;
            height: 100%;
            max-height: 500px;
            overflow: hidden;
            display: inline-block;
            background: linear-gradient(to right, rgba(0, 0, 0, 0) 0%, rgba(250, 250, 250, .75) 100%); /* W3C version */
            border-right: rgba(0, 0, 0, 0.3) solid 1px;
        }

        .modal_place_picture {
            width: auto;
            height: auto;
            position: relative;
            z-index: -1;
            display: block;
        }

        .modal_place_stats_block {
            margin-top: 50px;
        }

        #modal_place_manage_button {
            white-space: nowrap;
        }

        #card_user_pic {
            height: 150px;
        }

        #little_credit_symbol {
            font-size: 18px;
        }

        #player_bio {
            white-space: pre-wrap;
        }

        .editable:hover {
            cursor: pointer;
        }

        .editable:focus {
            background-color: #fff;
            box-shadow: 0 0 18px #B21745;
            padding: 4px;
        }

        .editable:hover::after {
            display: inline-block;
            font-size: inherit;
            text-rendering: auto;
            content: "\270E";
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<header>
    <nav class="nav-extended pink darken-3">
        <div class="nav-wrapper">
            <a href="#" class="brand-logo">Géomarcheur</a>
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="#">Tableau de bord</a></li>
                <li><a href="#">Classement</a></li>
                <li><a href="#">Statistiques</a></li>
                <li><a href="#" onclick="logout()">Se déconnecter</a></li>
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

    <!-- The 4 dashboard cards -->
    <div class="row">
        <!-- MAP -->
        <div class="col s6">
            <div class="card-panel hoverable map_block">
                <div class="valign-wrapper center-align map" id="map"><h1>MAP</h1></div>
            </div>
        </div>
        <!-- Place list -->
        <div class="col s6">
            <div class="card-panel hoverable place_list_block">
                <div class="row">
                    <form class="fullwidth">
                        <div class="input-field">
                            <i class="material-icons prefix">search</i>
                            <input id="place_input_search" type="text">
                            <label for="place_input_search">Rechercher</label>
                        </div>
                    </form>
                    <div id="place_list_container" class="fullwidth">
                        <div id="place_list" class="collection">
                            <!-- Generated content; see script below -->
                        </div>
                    </div>
                    <div id="place_list_message"></div>
                </div>
            </div>
        </div>
        <!-- Mini-leaderboard -->
        <div class="col s6">
            <div class="card-panel hoverable">
                <table class="highlight responsive-table">
                    <thead>
                    <tr>
                        <th><!-- Avatar --></th>
                        <th>Nom</th>
                        <th>Nombre de lieux</th>
                        <th>Crédits possédés</th>
                    </tr>
                    </thead>
                    <tbody id="user_list"></tbody>
                </table>
                <div class="right-align"><a class="waves-effect waves-light btn indigo darken-4">Voir le classement</a>
                </div>
            </div>
        </div>
        <!-- Stats -->
        <div class="col s6">
            <div class="card-panel hoverable">
                <div id="linechart_material"></div>
            </div>
        </div>
    </div>

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
                        <input id="modal_place_name_input" name="modal_place_name_input" type="text">
                        <label for="modal_place_name_input">Nom</label>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">place</i>
                        <input id="modal_place_address_input" name="modal_place_address_input" type="text">
                        <label for="modal_place_address_input">Adresse</label>
                    </div>

                    <!-- TODO: Refactor this so that it actually looks like it belongs in there -->
                    <input type="hidden" id="idPlace" value="">

                    <div class="input-field">
                        <i class="material-icons prefix">account_circle</i>
                        <input disabled id="modal_place_owner_input" name="modal_place_owner_input" type="text">
                        <label for="modal_place_owner_input">Propriétaire</label>
                    </div>
                    <div class="input-field">
                        <span class="credit_symbol prefix">¢</span>
                        <input id="modal_place_value_input" name="modal_place_value_input" type="text">
                        <label for="modal_place_value_input">Valeur</label>
                    </div>
                </form>

                <!-- Place delete button + stats preview -->
                <div class="col s3">
                    <!-- si le status du lieu === 1 (lieu actif) -->
                    <a href="#!" class="btn waves-effect waves-light red darken-4 grey-text text-lighten-5 fullwidth"
                       id="modal_place_manage_button"
                       onclick="managePlace();"></a>

                    <!-- Stats preview -->
                    <div class="card small modal_place_stats_block">
                        <div class="card-image">
                            <img src="http://i.imgur.com/0uABqwN.png">
                            <span class="card-title">_ 3,045 ¢</span>
                            <!-- Current amount of credits gained from this place -->
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
            <a href="#!" class="waves-effect btn-flat indigo-text text-darken-4"
               onclick="editPlace()">Sauvegarder les modifications</a>
            <a href="#!" class="modal-action modal-close waves-effect btn-flat red-text"
               onclick="idPlace = '';">Retour</a>
        </div>
    </div>
</div>


<div id="leaderboard" class="container">
    <div class="card-panel hoverable">
        <div class="row">
            <table id="leaderboard_container" cellspacing="0" width="100%">
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

    <!-- la modale des détails d'un utilisateur -->
    <div id="modal_detail_user" class="modal modal-fixed-footer">
        <div class="modal-content">
            <div class="row">²
                <div class="col s3">
                    <div id="card_user_pic" class="card small modal_place_stats_block">
                        <div class="card-content">
                            <p class="bold center">
                                <i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i><br/>
                                Photo de l'utilisateur
                            </p>
                        </div>
                    </div>
                    <div class="center">
                        <p id="player_quote" contenteditable="true" class="editable"></p>
                    </div>
                </div>

                <div class="col s9">
                    <div class="row">
                        <div class="col s6">
                            <p class="bold">Nom</p>
                            <p id="player_name"></p>
                        </div>
                        <div class="col s2">
                            <p class="bold">#</p>
                            <p id="player_position"></p>
                        </div>
                        <div class="col s2">
                            <span class="bold">Crédits</span>
                            <p><span id="player_credits"></span>&nbsp;<span id="little_credit_symbol"
                                                                            class="credit_symbol prefix">¢</span></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="row"></div>
                        <div class="col s8">
                            <p class="bold">Bio</p>
                            <p id="player_bio" contenteditable="true" class="editable"></p>
                        </div>
                        <div class="col s4">
                            <p class="bold">Lieu(x) possédé(s)</p>
                            <p id="player_places"></p>
                            <!-- liste des lieux possédés -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="hide" id="player_id"></div>
        </div>

        <div class="modal-footer">
            <a class="waves-effect waves-light btn-large btn-flat indigo-text text-darken-4" onclick="editProfile()"><i
                        class="material-icons">edit</i> Sauvegarder les changements</a>
            <a class="waves-effect waves-light btn-large red" onclick="manage_ban()" ><span id="ban_button_message"></span></a>
            <a href="#!" class="modal-action modal-close waves-effect btn-large btn-flat pink-text text-darken-3"
               onclick="idPlace = '';">Retour</a>
        </div>
    </div>
</div>
<footer class="page-footer pink darken-3">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="grey-text text-lighten-5">Footer Content</h5>
                <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer
                    content.</p>
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

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<script src="<?php echo base_url(); ?>static/js/materialize.min.js"></script>
<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY ?>"
        type="text/javascript"></script>
<!-- Charts API + placeholder data -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    google.charts.load('current', {'packages': ['line']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        let data = new google.visualization.DataTable();
        data.addColumn('number', 'Day');
        data.addColumn('number', 'Dave Grohl');
        data.addColumn('number', 'Eric Clapman');
        data.addColumn('number', 'Bob Dylan');

        data.addRows([
            [1, 37.8, 80.8, 41.8],
            [2, 30.9, 69.5, 32.4],
            [3, 25.4, 57, 25.7],
            [4, 11.7, 18.8, 10.5],
            [5, 11.9, 17.6, 10.4],
            [6, 8.8, 13.6, 7.7],
            [7, 42, 12.3, 9.6],
            [8, 342, 29.2, 10.6],
            [9, 5342, 42.9, 14.8],
            [10, 85342, 30.9, 25],
            [11, 985342, 342, 50],
            [12, 1985342, 5342, 500],
            [13, 3920342, 85342, 400],
            [14, 6985342, 985342, 420]
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

<!-- Charts API + placeholder data -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>

<!-- Custom tools -->
<script src="<?php echo base_url(); ?>static/js/utils.js"></script>

<!-- Custom local scripts -->
<script>
    $(document).ready(function () {

        // Leaderboards
        const userListDatatable = $("#datatable_leaderboard");
        const user_list = $("#user_list");

        $.getJSON("getUser", "", function (result) {
            console.log(result);
            $.each(result, function (i, users) {

                if (users.length === 0) {

                    $("#user_list_message").html("<p>Il n'existe aucun utilisateur actuellement!</p>")
                } else {
                    //userListDatatable.html("");
                    $.each(users, function (j, user) {
                            const user_data = `
                       <tr>
                       <td class="leaderboard_id"><i class="material-icons circle orange accent-4 grey-text text-lighten-5">account_circle</i></td>
                       <td class="leaderboard_pseudo">` + user["pseudo"] + `</td>
                       <td class="leaderboard_credits">¢ ` + user["credits"] + `</td>
                       <td class="leaderboard_is_admin">` + user["is_admin"] + `</td>
                       </tr>
                       `;
                            user_list.append(user_data);
                            //userListDatatable.append(user_data);
                        }
                    )
                }
            })
        });

        const place_list = $("#place_list");

        // Places list
        $.getJSON("getPlace/asc", "", function (result) {
            $.each(result, function (i, places) {
                // TODO: Make a better overfllow rule
                if (places.length === 0) {
                    $("#place_list_message").html("<p>Il n'existe aucun lieu actuellement! Pour créer un lieu, cliquez la où vous souhaitez créer un lieu sur la carte, ou entrez l'adresse directement dans le champ de recherche ci-dessus puis suivez les instructions.</p>")
                } else {
                    $.each(places, function (j, place) {
                        if (place.status === '0') {

                            place_list.append(`
                        <a class="collection-item avatar grey-text place_item modal-trigger" href="#" onclick="display_place(` + place["id"] + `)">
                        <img class="place_picture circle" src="` + ((place["picture"] === null) ? 'https://maps.googleapis.com/maps/api/streetview?size=250x250&fov=70&location=' + place["lat"] + ',' + place["lng"] + '&key=<?php echo GOOGLE_API_KEY ?>' : place["picture"]) + `" alt="">
                        <span class="place_id">` + place["id"] + `</span>
                        <p class="place_name title">` + place["name"] + `</p>
                        <p class="place_location">` + ((place["address"] === null) ? place["lat"] + ', ' + place["lng"] : place["address"]) + `</p>
                        <p class="place_value secondary-content pink-text text-darken-3"><span class="credit_symbol">¢</span>` + place["value"] + `</p>
                        </a>`);

                        } else {

                            place_list.append(`
                        <a class="collection-item avatar grey-text text-darken-4 place_item modal-trigger" href="#" onclick="display_place(` + place["id"] + `)">
                        <img class="place_picture circle" src="` + ((place["picture"] === null) ? 'https://maps.googleapis.com/maps/api/streetview?size=250x250&fov=70&location=' + place["lat"] + ',' + place["lng"] + '&key=<?php echo GOOGLE_API_KEY ?>' : place["picture"]) + `" alt="">
                        <span class="place_id">` + place["id"] + `</span>
                        <p class="place_name title">` + place["name"] + `</p>
                        <p class="place_location">` + ((place["address"] === null) ? place["lat"] + ', ' + place["lng"] : place["address"]) + `</p>
                        <p class="place_value secondary-content pink-text text-darken-3"><span class="credit_symbol">¢</span>` + place["value"] + `</p>
                        </a>`);
                        }
                    });
                }
            });
        });

        // TODO: améliorer la gestion de l'affichage des lieux supprimés
        // TODO: ajouter l'attribut hidden sur le bouton de suppression selon si le lieu est supprimé ou non


        // Place search function
        $("#place_input_search").keyup(function () {

            // Retrieve the input field text and reset the count to zero
            let filter = $(this).val();
            let count = 0;
            const message_box = $("#place_list_message");

            // Loop through the list
            $(".place_item").each(function () {

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

    function logout() {
        $.ajax({url: "<?php echo base_url(); ?>logout/"}
        ).done(function () {
            location.reload();
        });
    }

    // Place details display
    function display_place(id) {
        // TODO: Solve "TypeError: document.getElementById(...) is null". See issue #48
        const place_modal = $("#place_modal");

        // TODO: Use the same method as the User modal; don't use inputs, use editable divs. It's 2018 ffs
        idPlace = id;
        $("#idPlace").val(id);
        get_place(id, function (result) {
            const place = result;
            if (place === 1) {
                alert("Ce lieu n'existe pas!");
            } else {

                get_user(place["id_User"], function (result) {
                    const owner = result;

                    place_modal.modal({
                        dismissible: true, // Modal can be dismissed by clicking outside of the modal
                        opacity: .5, // Opacity of modal background
                        inDuration: 300, // Transition in duration
                        outDuration: 200, // Transition out duration
                        startingTop: '4%', // Starting top style attribute
                        endingTop: '10%', // Ending top style attribute
                        ready: function (modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
                            $("#modal_place_name_input").val(place["name"]);
                            if (place["address"] !== null) {
                                $("#modal_place_address_input").val(place["address"]);
                            }
                            if (place["id_User"] !== null) {
                                $("#modal_place_owner_input").val(owner["pseudo"]);
                            }
                            $("#modal_place_value_input").val(place["value"]);

                            let manage_button = $("#modal_place_manage_button");
                            if (place["status"] === '0') {
                                manage_button.removeClass("red");
                                manage_button.addClass("green");
                                manage_button.html('<i class="material-icons grey-text text-lighten-5 left">place</i>Activer');
                            } else {
                                manage_button.removeClass("green");
                                manage_button.addClass("red");
                                manage_button.html('<i class="material-icons grey-text text-lighten-5 left">delete</i>Désactiver');
                            }

                            Materialize.updateTextFields();
                        },
                        complete: function (modal, trigger) {
                            $("#modal_place_name_input").val("");
                            $("#modal_place_address_input").val("");
                            $("#modal_place_owner_input").val("");
                            $("#modal_place_value_input").val("");

                            Materialize.updateTextFields();
                        }
                    });
                    place_modal.modal('open');
                });
            }
        });
    }

    // TODO: Handle the reactivation
    function managePlace() {
        const place_modal = $("#place_modal");

        let id = document.getElementById('idPlace').value;
        if (confirm("Vous désirez vraiment supprimer?")) {
            $.ajax({
                url: "<?php echo base_url(); ?>disablePlace",
                type: "GET",
                data: {
                    id: id
                }
            }).done(function () {
                place_modal.modal('close');
            });
        }
    }

    // TODO : modifier la classe du lieu ou mettre un symbole pour signifier sa suppression
    // TODO : supprimer le moche "input text hidden"
    // si le lieu est supprimé => class deleted

    function editPlace() {
        const place_id = $("#idPlace").val();
        const place_name = $("#modal_place_name_input").val();
        const place_address = $("#modal_place_address_input").val();
        const place_value = $("#modal_place_value_input").val();

        $.ajax({
            type: "POST",
            url: "editPlace/",
            data: {
                id: place_id,
                name: place_name,
                address: place_address,
                value: place_value
            },
            statusCode: {
                200: function (data) {
                    Materialize.toast(data, 3000, 'green rounded');
                },
                400: function (data) {
                    Materialize.toast("An error was encountered. Error code: 400", 3000, 'red rounded');
                },
                401: function (data) {
                    Materialize.toast("An error was encountered. Error code: 401", 3000, 'red rounded');
                }
            }
        });
    }

    const place_list_table = $("#place_list_table");
    const divs = place_list_table.find("div.card");
    let alpha_order = false;
    let carte;
    let marqueur = [];
    let latlng = new google.maps.LatLng(43.600000, 1.433333);
    let options = {
        center: latlng,
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
    carte = new google.maps.Map(document.getElementById("map"), options);
    let infowindow = new google.maps.InfoWindow();
    $.getJSON("getPlace", "", function (result) {
            $.each(result, function (i, places) {
                $.each(places, function (j, place) {
                        marqueur[j] = new google.maps.Marker(
                            {
                                position: new google.maps.LatLng(place.lat, place.lng),
                                title: 'Nom du lieu : ' + place.name
                            }
                        );
                        marqueur[j].setMap(carte);
                        //console.log(marqueur[j]);
                        // Closure => création de la function au moment de la création du marqueur
                        let macallback = function callbackSpecificiqueMarqueur(ev) {
                            //console.log("Callback appelée", ev, marqueur[j]);
                            //console.log("la position est : " +marqueur[j].getPosition());

                            let contentString =
                                '<div id="content">' +
                                '<p> Nom du lieu : ' + place.name + '</p>' +
                                '<p> Valeur : ' + place.value + '</p>' +
                                '<a href="#">Plus de détails</a><br><br>' +
                                '<a href="#">Vendre le lieu</a>' +
                                '</div>';

                            infowindow.setContent(contentString);
                            infowindow.open(map, marqueur[j]);
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
    );

    const users_detail_modal = $("#modal_detail_user");
    let idUser;
    users_detail_modal.modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        inDuration: 300, // Transition in duration
        outDuration: 200, // Transition out duration
        startingTop: '4%', // Starting top style attribute
        endingTop: '10%'
    });

    // Datatable setup
    let container = $('#leaderboard_container');
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

    });

    // Display and fill out user detail modal
    let rows = $('#datatable_leaderboard');
    rows.on('click', 'tr', function () {

        let row = $(this);
        idUser = row[0].childNodes[0].textContent;
        //console.log(idUser);

        let user_data;

        $.getJSON("getUser/" + idUser, "", function (result) {
            $.each(result, function (i, users) {
                $.each(users, function (j, user) {
                    //users_detail_modal.html("");
                    currentUser = user;
                    let player_name = $("#player_name").html(user.pseudo);
                    //TODO : creer la fonction + requete de qui va positionner le joueur
                    $("#player_position").html(user.credits);
                    $("#player_credits").html(user.credits);
                    $("#player_quote").html(user.quote);
                    $("#player_bio").html(user.bio);
                    $("#player_id").html(user.id);
                    // TODO : recupérer la photo des joueurs

    console.log("etat banni = "+ user.is_banned);
                    //si ban = 0
                    if (user.is_banned == 0) {
                        $("#ban_button_message").html("Bannir");
                    } else {
                        $("#ban_button_message").html("Débannir");
                    }





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
                                //console.log("infos tableau" + places.length);

                                texte += "<li>" + place.name + "</li>";
                            });
                            if (places.length !== 0) {
                                return;
                            }
                            texte = "Aucun lieu."
                        });

                        texte += "</ul>";
                        $("#player_places").html(texte);
                        $("#player_number_place").html(places.length);

                    });
                    users_detail_modal.modal('open');

                })
            })
        });
    });

    // Save the modifications
    function editProfile() {
        const player_id = $("#player_id").text();
        const player_quote = $("#player_quote").text();
        const player_bio = $("#player_bio").html();

        $.ajax({
            type: "POST",
            url: "editProfile/",
            data: {
                id: player_id,
                quote: player_quote,
                bio: player_bio
            },
            statusCode: {
                200: function (data) {
                    Materialize.toast(data, 3000, 'green rounded');
                },
                400: function (data) {
                    Materialize.toast("An error was encountered. Error code: 400", 3000, 'red rounded');
                },
                401: function (data) {
                    Materialize.toast("An error was encountered. Error code: 401", 3000, 'red rounded');
                }
            }
        });
    };

    function manage_ban() {
        console.log(idUser);
// @TODO :get user where ID pour recupérer son nom
                let id = document.getElementById('idPlace').value;
                if (confirm("Voulez vous bannir le joueur ?")) {
                    $.ajax({
                        url: "<?php echo base_url(); ?>manage_ban",
                        type: "GET",
                        data: {
                            idUser: idUser
                        }
                    }).done(function () {
                        users_detail_modal.modal('close');
                    });
                }
            }





</script>


</body>
</html>
