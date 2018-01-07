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
    <!-- Import Snazzy CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/snazzy-info-window/snazzy-info-window.min.css">

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
            height: 600px !important;
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
            background-color: #f9c2f6;

        }

        .editable {
            background-color: #f5d9f4;
            border:1px solid gray;
            padding:5px;
            box-shadow:2px 2px 10px gray;
            -moz-box-shadow:2px 2px 10px gray;
            -webkit-box-shadow:2px 2px 10px gray;
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

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto, sans-serif;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto, serif;
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: rgba(0, 0, 0, .7);
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 100%;
            color: white
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        #stat_navbar {
            margin-top: 16px;
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
                <input id="pac-input" class="controls" type="text" placeholder="Chercher un lieu">
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
                <canvas id="linechart_material"></canvas>
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

                    <input id="modal_place_lat_input" name="modal_place_lat_input" type="hidden">
                    <input id="modal_place_lng_input" name="modal_place_lng_input" type="hidden">
                    <input id="modal_place_create_input" name="modal_place_create_input" type="hidden">

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
               onclick="savePlace()">Sauvegarder les modifications</a>
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
            <div class="row">
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
            <a class="waves-effect waves-light btn-large red" onclick="toggleBan()"><span
                        id="ban_button_message"></span></a>
            <a href="#!" class="modal-action modal-close waves-effect btn-large btn-flat pink-text text-darken-3"
               onclick="idPlace = '';">Retour</a>
        </div>
    </div>
</div>

<div id="statistics">
    <nav>
        <div class="nav-wrapper pink darken-3 row" id="stat_navbar">
            <span class="col s4 center" ><span id="stat_global_places">X/Y</span> lieux</span>
            <span class="col s4 center" ><span id="stat_global_credits">xxx</span> crédits en jeu</span>
            <span class="col s4 center" ><span id="stat_global_players">XY</span> joueurs</span>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <!-- 1 -->
            <div class="col s6">
                <div class="card-panel hoverable">
                    <canvas id="dashboard_chart_1"></canvas>
                </div>
            </div>

            <!-- 2 -->
            <div class="col s6">
                <div class="card-panel hoverable">
                    <canvas id="dashboard_chart_2"></canvas>
                </div>
            </div>

            <!-- 3 -->
            <div class="col s6">
                <div class="card-panel hoverable">
                    <canvas id="dashboard_chart_3"></canvas>
                </div>
            </div>

            <!-- 4 -->
            <div class="col s6">
                <div class="card-panel hoverable">
                    <canvas id="dashboard_chart_4"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="page-footer pink darken-3">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="grey-text text-lighten-5">Tableau de bord</h5>
                <p class="grey-text text-lighten-4">Espace d'administration.</p>
            </div>
            <div class="col l4 offset-l2 s12">
                <h5 class="grey-text text-lighten-5">Liens</h5>
                <ul>
                    <li><a class="grey-text text-lighten-3" href="#!">Aide</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Forum</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Mentions légales</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            © 2018 Kiantic
            <a class="grey-text text-lighten-4 right" href="#!">À propos</a>
        </div>
    </div>
</footer>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<script src="<?php echo base_url(); ?>static/js/materialize.min.js"></script>
<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?language=fr-FR&key=<?php echo GOOGLE_API_KEY ?>&libraries=places"
        type="text/javascript"></script>

<!-- Snazzy plugin -->
<script src="<?php echo base_url(); ?>static/js/snazzy-info-window/snazzy-info-window.min.js"></script>

<!-- Charts API + data -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.js" integrity="sha256-vyehT44mCOPZg7SbqfOZ0HNYXjPKgBCaqxBkW3lh6bg=" crossorigin="anonymous"></script>
<!-- Main -->
<script>
    let ctx = $("#linechart_material");
    let main_chart = myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
</script>
<!-- Headers -->
<script>
    let place_count = 0;
    let place_bought_count = 0;
    $.getJSON("getPlace/asc", "", function (result) {
        $.each(result, function (i, places) {
            $.each(places, function (j, place) {
                place_count++;
                if (place.id_User !== null) {
                    place_bought_count++;
                }
            })
        });

        $("#stat_global_places").text(place_bought_count + "/" + place_count);
    });
</script>
<!-- 1 -->
<script>
    let ctx1 = $("#dashboard_chart_1");
    let chart1 = myChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
</script>
<!-- 2 -->
<script>
    let ctx2 = $("#dashboard_chart_2");
    let chart2 = myChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
</script>
<!-- 3 -->
<script>
    let ctx3 = $("#dashboard_chart_3");
    let chart3 = myChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
</script>
<!-- 4 -->
<script>
    let ctx4 = $("#dashboard_chart_4");
    let chart4 = myChart = new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
</script>

<!-- DataTables API -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>

<!-- Custom tools -->
<script src="<?php echo base_url(); ?>static/js/utils.js"></script>

<!-- Custom local scripts -->
<script>
    // TODO: Clean this up
    $(document).ready(function () {

        // Leaderboards
        const userListDatatable = $("#datatable_leaderboard");
        const user_list = $("#user_list");

        $.getJSON("getUser", "", function (result) {
            //console.log(result);
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
                       <td class="leaderboard_credits"><span class="credit_symbol">¢</span> ` + user["credits"] + `</td>
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
                // TODO: Make a better overflow rule
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

    // Map setup
    const place_list_table = $("#place_list_table");
    const divs = place_list_table.find("div.card");
    let alpha_order = false;
    let idUser;
    let markers = [];
    let initial_latlng = new google.maps.LatLng(43.600000, 1.433333);   // Toulouse
    let options = {
        center: initial_latlng,
        zoom: 14,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        backgroundColor: "#AD1457",
        disableDefaultUI: true,
        fullscreenControl: false,
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
    let dashboard_map = new google.maps.Map(document.getElementById("map"), options);
    let infowindow = new google.maps.InfoWindow();
    // TODO: Find a way to customize the infowindows with Snazzy
    //let infowindow = new SnazzyInfoWindow();
    const marker_icon = {
        url: "<?php echo base_url(); ?>static/img/pin.svg",
        anchor: new google.maps.Point(25, 50),
        scaledSize: new google.maps.Size(50, 50)
    };

    // Set the place markers on the map
    $.getJSON("getPlace", "", function (result) {
        $.each(result, function (i, places) {
            $.each(places, function (j, place) {
                markers[j] = new google.maps.Marker({
                    position: new google.maps.LatLng(place.lat, place.lng),
                    title: place.name,
                    icon: marker_icon
                });
                markers[j].setMap(dashboard_map);

                // Closure => création de la function au moment de la création du marqueur
                let openMarkerInfowindow = function (ev) {
                    let contentString =
                        '<div class="marker_infowindow">' +
                        '<p><b>' + place.name + '</b></p>' +
                        '<p>' + place.value + '<span class="credit_symbol">¢</span></p>' +
                        '<p><a class="waves-effect waves-light btn pink darken-3" onclick="display_place(\'' + place.id + '\', false)">Plus de détails</a></p>' +
                        '</div>';

                    infowindow.setContent(contentString);
                    infowindow.open(dashboard_map, markers[j]);
                };

                // Linking the infoview to a click event
                google.maps.event.addListener(
                    markers[j], "click", openMarkerInfowindow
                );
            })
        })
    });

    // Create the search box and link it to the UI element.
    let input = document.getElementById('pac-input');
    let searchBox = new google.maps.places.SearchBox(input);
    dashboard_map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    dashboard_map.addListener('bounds_changed', function () {
        searchBox.setBounds(dashboard_map.getBounds());
    });

    let search_markers = [];
    const search_marker_icon = {
        url: "<?php echo base_url(); ?>static/img/search_pin.svg",
        anchor: new google.maps.Point(25, 50),
        scaledSize: new google.maps.Size(50, 50)
    };
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function () {
        let places = searchBox.getPlaces();

        if (places.length === 0) {
            return;
        }

        // Clear out the old markers.
        search_markers.forEach(function (marker) {
            marker.setMap(null);
        });
        search_markers = [];

        // For each place, get the icon, name and location.
        let bounds = new google.maps.LatLngBounds();
        places.forEach(function (place, i) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }

            // Create a marker for each place.
            search_markers[i] = new google.maps.Marker({
                icon: search_marker_icon,
                title: place.name,
                position: place.geometry.location
            });
            search_markers[i].setMap(dashboard_map);

            // Create an infowindow for each marker
            let openMarkerInfowindow = function (ev) {
                let typeChips = "";
                place.types.forEach(function (type) {
                    typeChips += "<div class='chip'>" + type + "</div>";
                });
                let contentString =
                    '<div class="marker_infowindow">' +
                    '<img src="' + place.icon + '" />' +
                    '<p><b>' + place.name + '</b><br>' +
                    place.formatted_address + '<br>' +
                    place.geometry.location + '</p>' +
                    typeChips + '<br>' +
                    '<p><a class="waves-effect waves-light btn pink darken-3" onclick="createPlace(\'' + place.place_id + '\')">Créer ce lieu</a></p>' +
                    '</div>';

                infowindow.setContent(contentString);
                infowindow.open(dashboard_map, search_markers[i]);
            };

            // Linking the infoview to a click event
            google.maps.event.addListener(
                search_markers[i], "click", openMarkerInfowindow
            );

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        dashboard_map.fitBounds(bounds);
    });


    function createPlace(place_id) {
        // Build the request for the place resolution
        let request = {
            placeId: place_id
        };

        // Send the request
        let service = new google.maps.places.PlacesService(dashboard_map);
        service.getDetails(request, displayCreatePlaceModal);
    }

    function displayCreatePlaceModal(place, status) {
        // TODO: Handle other error codes (see https://developers.google.com/maps/documentation/javascript/places#place_details_responses )
        // TODO: DRY this up with displayPlace()
        if (status === google.maps.places.PlacesServiceStatus.OK) {
            const place_modal = $("#place_modal");
            place_modal.modal({
                dismissible: true, // Modal can be dismissed by clicking outside of the modal
                opacity: .5, // Opacity of modal background
                inDuration: 300, // Transition in duration
                outDuration: 200, // Transition out duration
                startingTop: '4%', // Starting top style attribute
                endingTop: '10%', // Ending top style attribute
                ready: function (modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
                    $("#idPlace").val('');
                    $("#modal_place_name_input").val(place.name);
                    $("#modal_place_address_input").val(place.formatted_address);
                    $("#modal_place_owner_input").val();
                    $("#modal_place_value_input").val();
                    $("#modal_place_lat_input").val(place.geometry.location.lat());
                    $("#modal_place_lng_input").val(place.geometry.location.lng());
                    $("#modal_place_create_input").val(true);

                    let manage_button = $("#modal_place_manage_button");
                    manage_button.addClass("hide");

                    Materialize.updateTextFields();
                },
                complete: function (modal, trigger) {
                    $("#modal_place_name_input").val("");
                    $("#modal_place_address_input").val("");
                    $("#modal_place_owner_input").val("");
                    $("#modal_place_value_input").val("");
                    $("#modal_place_manage_button").removeClass("hide");

                    Materialize.updateTextFields();
                }
            });
            place_modal.modal('open');
        }
    }

    function logout() {
        $.ajax({url: "logout/"}
        ).done(function () {
            location.reload();
        });
    }

    // Place details display
    function display_place(id, create = false) {
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
                            $("#modal_place_create_input").val(create);

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

    function savePlace() {
        const place_create = $("#modal_place_create_input").val();
        const place_id = $("#idPlace").val();
        const place_name = $("#modal_place_name_input").val();
        const place_address = $("#modal_place_address_input").val();
        const place_value = $("#modal_place_value_input").val();

        let place_url = "";
        let place_data = {};

        if (place_create === 'true') {
            const place_lng = $("#modal_place_lng_input").val();
            const place_lat = $("#modal_place_lat_input").val();

            place_url = "createPlace/";
            place_data = {
                name: place_name,
                address: place_address,
                value: place_value,
                lat: place_lat,
                lng: place_lng
            }
        } else {
            place_url = "editPlace/";
            place_data = {
                id: place_id,
                name: place_name,
                address: place_address,
                value: place_value
            }
        }

        $.ajax({
            type: "POST",
            url: place_url,
            data: place_data,
            statusCode: {
                200: function (data) {
                    Materialize.toast(data, 3000, 'green rounded');
                    // TODO: Update the map and list
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

    const users_detail_modal = $("#modal_detail_user");
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
        "order": [[2, "desc"]]

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

                    //si ban = 0
                    if (user.is_banned === '0') {
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
                        $("#player_number_place").html(result.length);

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
        }).done(function () {
            users_detail_modal.modal('close');
        });;
    }

    function toggleBan() {
        // TODO: Change this to POST
        // TODO: Adapt the message for both banning and unbanning
        // TODO: Refresh leaderboard
        if (confirm("Voulez vous bannir " + $("#player_name").text() + " ?")) {
            $.ajax({
                url: "toggleBan",
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
