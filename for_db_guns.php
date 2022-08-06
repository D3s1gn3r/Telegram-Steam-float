<?php 
    require $_SERVER['DOCUMENT_ROOT'] . '/rb.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/db.php';

    $name = trim($_POST['name']);
    $name = str_replace(" ", "%20", $name);
    $name = str_replace("|", "%7C", $name);
    $fromfloat = trim($_POST['fromfloat']);
    $tofloat = trim($_POST['tofloat']);
    $paintseed = trim($_POST['paintseed']);
    $phase = trim($_POST['phase']);
    $notes = trim($_POST['notes']);



    $places = R::dispense( 'floatguns' );
    $places->name = $name;
    $places->fromfloat = $fromfloat;
    $places->tofloat = $tofloat;
    $places->paintseed = $paintseed;
    $places->phase = $phase;
    $places->notes = $notes;
    R::store( $places );
?>


