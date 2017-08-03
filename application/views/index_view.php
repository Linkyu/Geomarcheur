<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        body {
            padding: 0;
            margin: 0;
            background-color: lightgrey;
            display: flex;
            justify-content: center;
        }

        #page {
            min-width: 70%;
            max-width: 70%;
        }

        .grid {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .list .block {
            min-width: 100%;
        }

        .block {
            min-width: 30%;
            max-width: 30%;
            padding: 1em;
            box-sizing: border-box;
            background-color: whitesmoke;
            border: 1px solid darkgrey;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<div id="page">
    <h1>Blablabla ..... </h1>

    <div>
        <p>Ceci est la vue de mon opération Index DU FUTUUUUUR.</p>
    </div>

    <div class="<?php echo $display ?>"><?php
        foreach ($resultat as $ligne)
        {

            echo '<div class="block">';

            echo $ligne['id'];
            echo '<h1>'.$ligne['titre'].'</h1>';
            echo '<p>'.$ligne['dateActualite'].'</p>';
            echo '<div><img src="'.$ligne['image'].'" /></div>';
            echo '<p>'.$ligne['texte'].'</p>';

            echo '</div>';
        }
        ?>
    </div>

</div>

</body>
</html>