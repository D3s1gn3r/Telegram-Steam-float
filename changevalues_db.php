<?php 
    require $_SERVER['DOCUMENT_ROOT'] . '/rb.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/db.php';

    $id = trim($_POST['id']);
    $value = trim($_POST['value']);


    $book = R::load('floatguns', $id);
    $book->notes = $value; 
    R::store($book);

?>


