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
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/input_color_override.css"  media="screen,projection"/>

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
                    <a class="waves-effect waves-light btn-flat modal-trigger white-text" href="#place_list">
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
    <div id="place_list" class="modal">
        <div class="modal-content">
            <h4>Liste de vos lieux</h4>
            <p>A bunch of text</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fermer</a>
        </div>
    </div>

    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- Compiled and minified JavaScript -->
    <script src="<?php echo base_url(); ?>static/js/materialize.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".modal").modal({
                ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
                    alert("Ready");
                    console.log(modal, trigger);
                }
            });
        });
    </script>
</body>
</html>