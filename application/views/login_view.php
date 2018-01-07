<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$temp_user_id = 2;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Géomarcheur</title>

    <link rel="icon" type="image/png" href="<?php echo base_url(); ?>static/img/icon.png">

    <!-- TODO: Include all these from a separate file to prevent missing links. See issue #54 -->

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
            text-shadow: 0 0 5px #FFF;
        }

        .warning-alert .text {
            margin: 15px; !important;
        }

        .warning-alert .material-icons {
            margin-left: 15px; !important;
        }
    </style>
</head>
<body>

<main class="valign-wrapper pink darken-3">
    <div class="container">
        <div class="row">
            <!-- TODO: Find a way to keep both at h1 for consistency. See issue #55 -->
            <h1 class="col m12 white-text center-align full hide-on-small-only">Géomarcheur</h1>    <!-- Desktop -->
            <h2 class="col s12 white-text center-align full hide-on-med-and-up">Géomarcheur</h2>    <!-- Mobile -->
        </div>
        <div class="row aanimated fadeInUp">
            <form class="col s12 m8 l6 offset-m2 offset-l3" method="post" action="login/">
                <div class="warning-alert valign-wrapper z-depth-2 orange white-text row hide" id="error_bar">
                    <i class="material-icons">warning</i>
                    <div class="text"></div>
                </div>
                <div class="row">
                    <div class="input-field col s12 white-text">
                        <i class="material-icons prefix">account_circle</i>
                        <input id="username" type="text" class="validate" autofocus>
                        <label for="username">Pseudo</label>
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
                        <input type="checkbox" id="showPassword" class="white-text" onclick="displayPassword()"/>
                        <label for="showPassword">Afficher le mot de passe</label>
                    </div>
                </div>
                <div class="warning-alert valign-wrapper z-depth-2 orange white-text row hide" id="capslock_warning">
                    <i class="material-icons">keyboard_capslock</i>
                    <div class="text">La touche Verr. Maj. est active.</div>
                </div>
            </form>
            <p class="col s12 m8 l6 offset-m2 offset-l3"><a href="#" onclick="login()" class="btn right indigo darken-4 waves-effect waves-light ">Connection</a></p>
            <p class="col s12 m8 l6 offset-m2 offset-l3"><a href="#" class="white-text underline">S'inscrire</a></p>
        </div>
    </div>
</main>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!-- Compiled and minified Materialize JavaScript -->
<script src="<?php echo base_url(); ?>static/js/materialize.min.js"></script>

<!-- Caps Lock detector -->
<script src="<?php echo base_url(); ?>static/js/capslock_detector.js"></script>

<script type="text/javascript">
    function displayPassword() {
        let pass_input = $("#password");
        pass_input.attr('type', (pass_input.attr('type') === "password" ? "text" : "password"));
    }

    function login() {
        let error_bar = $("#error_bar");
        error_bar.find(".text").html("");
        error_bar.addClass("hide");

        const username_input = $("#username").val();
        const password_input = $("#password").val();

        if (username_input === "" || password_input === "") {
            error_bar.find(".text").html("Veuillez remplir les deux champs.");
            error_bar.removeClass("hide");
        } else {

             $.ajax({
                 type: "POST",
                 url: "login/",
                 data: {
                     username: username_input,
                     password: password_input
                 },
                 statusCode: {
                     200: function () {
                         // TODO: Find a cleaner way?
                         location.reload();
                         // $("html").html(data); // much quicker but breaks the next page because document.ready is not called
                         // document.write(data) // breaks it less but still not quite functional
                     },
                     400: function (data) {
                         error_bar.find(".text").html(data.responseText);
                         error_bar.removeClass("hide");
                     },
                     401: function (data) {
                         error_bar.find(".text").html(data.responseText);
                         error_bar.removeClass("hide");
                     }
                 }
             })
        }
    }

    $("form").on('keypress', 'input', function(event) {
        if (event.which === 13) {
            event.preventDefault();
            login();
        }
    });
</script>
</body>
</html>