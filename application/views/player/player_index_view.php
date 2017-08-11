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
        map
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
            <h4>Liste de vos lieux</h4>
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
    <!-- Compiled and minified JavaScript -->
    <script src="<?php echo base_url(); ?>static/js/materialize.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            const place_list_table = $("#place_list_table");

            $(".modal").modal({
                dismissible: true, // Modal can be dismissed by clicking outside of the modal
                opacity: .5, // Opacity of modal background
                inDuration: 300, // Transition in duration
                outDuration: 200, // Transition out duration
                startingTop: '4%', // Starting top style attribute
                endingTop: '10%', // Ending top style attribute
                ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
                    $.getJSON( "getUserPlaces/<?php echo $temp_user_id ?>", "", function( result ) {
                        $.each(result, function(i, places) {
                            // TODO: Retrieve picture from streetview (https://developers.google.com/maps/documentation/streetview/intro)
                            // TODO: Find a way to keep the image at a consistent size
                            // TODO: Search function
                            // TODO: Sort function
                            if (places.length === 0) {
                                place_list_table.append('<p>Vous ne possédez aucun lieu actuellement! Pour capturer un lieu, approchez-vous de celui-ci, appuyez dessus sur la carte puis appuyez sur Acheter.</p>')
                            }
                            $.each(places, function(j, place){
                                place_list_table.append(`
                                    <div class="card horizontal">
                                        <div class="card-image">
                                            <img src="` + ((place["picture"] === null) ? '<?php echo base_url(); ?>static/img/house.png' : place["picture"]) + `">
                                            <a class="btn-floating halfway-fab-right waves-effect waves-light pink darken-3"><i class="material-icons">visibility</i></a>
                                        </div>
                                        <div class="card-stacked">
                                            <div class="card-content">
                                                <span class="card-title">` + place["name"] + `</span>
                                                <p><b>` + ((place["address"] === null) ? place["lat"] + ', ' + place["lng"] : place["address"]) + `</b></p>
                                                <p>¢` + place["value"] + `</p>
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
                complete: function() { place_list_table.text(''); } // Empty the div
            });
        });
    </script>
</body>
</html>