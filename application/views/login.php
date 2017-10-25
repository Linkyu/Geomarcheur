<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$temp_user_id = 2;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Géomarcheur</title>

    <!-- TODO: Include all these from a separate file to prevent missing links -->

    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/animate.css"  media="screen,projection"/>
    <!--Import custom css files-->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>static/css/input_color_login_override.css"  media="screen,projection"/>
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

        .full {
            width: 100%;
            text-shadow: 0 0 5px #FFF;
        }
    </style>
</head>
<body>

<main class="valign-wrapper pink darken-3">
    <div class="container">
        <h1 class="white-text center-align full">Géomarcheur</h1>
        <div class="row animated fadeInUp">
            <form class="col s12 m8 l6 offset-m2 offset-l3">
                <div class="row">
                    <div class="input-field col s12 white-text">
                        <i class="material-icons prefix">account_circle</i>
                        <input id="pseudo" type="text" class="validate" autofocus>
                        <label for="pseudo">Pseudo</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12  white-text">
                        <i class="material-icons prefix">lock</i>
                        <input id="password" type="password" class="validate">
                        <label for="password">Password</label>
                    </div>
                </div>
                <div class="row">
                    <div class="switch col s12 white-text">
                        <!-- TODO: Make this functional -->
                        <input type="checkbox" id="showPassword" class="white-text" />
                        <label for="showPassword">Afficher le mot de passe</label>
                    </div>
                </div>
            </form>
            <p class="col s12 m8 l6 offset-m2 offset-l3"><a href="#" class="btn right indigo darken-4 waves-effect waves-light ">Connection</a></p>
            <p class="col s12 m8 l6 offset-m2 offset-l3"><a href="#" class="white-text underline">S'inscrire</a></p>
        </div>
    </div>

</main>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!-- Compiled and minified Materialize JavaScript -->
<script src="<?php echo base_url(); ?>static/js/materialize.min.js"></script>

<script type="text/javascript">
    // Custom scripts
</script>
</body>
</html>