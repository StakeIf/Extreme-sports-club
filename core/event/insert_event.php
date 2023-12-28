<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Event.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportEvent.php');

$model = Event::get();
$modelSport = SportEvent::get();

$data = $_POST['DATA'];
$name = $_POST['NAME'];
$description = $_POST['DISC'];

$model->insert([
    'data' => '\''. $data .'\'',
    'name' => '\''.$name .'\'',
    'pk_type_event' => htmlspecialchars($_GET['TYPE']),
    'pk_area' =>  $_POST['AREA'] ,
    'description' =>'\''. $description .'\''
]);

$newId = $model->select(['pk_event'])->where([
    'data' => '\''. $data .'\'',
    'name' => '\''.$name .'\'',
    'pk_type_event' => htmlspecialchars($_GET['TYPE']),
    'pk_area' =>  $_POST['AREA']
])->fetch()['pk_event'];

echo $newId;

header('Location: /views/addEvent/?ID=' . $newId);