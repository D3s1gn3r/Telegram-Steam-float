<?php

    require $_SERVER['DOCUMENT_ROOT'] . '/rb.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/db.php';

    $id = trim($_POST['id']);

    R::exec('DELETE FROM `floatguns` WHERE `id` = ?', array(
    $id
    ));

?>


