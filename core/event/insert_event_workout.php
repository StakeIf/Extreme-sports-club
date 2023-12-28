<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Event.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportEvent.php');

$model = Event::get();
$modelSport = SportEvent::get();



$model->insert([
    'data' => '\''.$_POST['DATA'].'\'',
    'name' => '\''.$_POST['NAME'].'\'',
    'pk_type_event' => htmlspecialchars($_GET['TYPE']),
    'pk_area' => $_POST['AREA'],
    'description' => '\''.$_POST['DESC'].'\''
]);

$newId = $model->select(['pk_event'])->where([
    'data' => '\''.$_POST['DATA'].'\'',
    'name' => '\''.$_POST['NAME'].'\'',
    'pk_type_event' => htmlspecialchars($_GET['TYPE']),
    'pk_area' => $_POST['AREA'],
    'description' => '\''.$_POST['DESC'].'\''
    ])->fetch()['pk_event'];

$modelSport->insert([
    'pk_sport' => $_POST['SPORT'],
    'pk_event' => $newId,
    'num_participant' => $_POST['NUM']
]);

header('Location: /views/workout/');