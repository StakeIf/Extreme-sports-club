<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Event.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportEvent.php');


$model = Event::get();
$modelSport = SportEvent::get();

$model->where(['pk_event' => $_GET['ID']])->update([
    'data' => '\'' . $_POST['DATA'] . '\'',
    'name' => '\''. $_POST['NAME'] . '\'',
    'pk_area' => $_POST['AREA'],
    'description' => '\'' . $_POST['DESC'] . '\''
]);
$modelSport->sql('delete from sport_event where pk_event =' . htmlspecialchars($_GET['ID']));
$modelSport->sql('insert into sport_event (pk_sport, pk_event, num_participant) values (' . $_POST['SPORT'] . ',' . htmlspecialchars($_GET['ID']) .',' .  $_POST['NUM'] . ')');

header('Location: /views/workout/');