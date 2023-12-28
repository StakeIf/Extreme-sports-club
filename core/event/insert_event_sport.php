<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportEvent.php');

$modelSport = SportEvent::get();


print_r($_POST);
print_r($_GET);

//die();

if ($_POST['SPORT'] == '-1'){
    flash('Выберите спорт');
    header('Location: /views/addEvent/?ID='.$_GET['ID']);
    die;
}

$modelSport->insert([
    'pk_event' => $_GET['ID'],
    'pk_sport' => $_POST['SPORT'],
    'num_participant' => $_POST['NUM']
]);

header('Location: /views/addEvent/?ID='.$_GET['ID']);