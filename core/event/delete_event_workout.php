<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Event.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportEvent.php');


$model = Event::get();
$modelSport = SportEvent::get();

$modelSport->sql('delete from sport_event where pk_event =' . htmlspecialchars($_GET['ID']));

$model->sql('delete from event where pk_event =' . htmlspecialchars($_GET['ID']));
header('Location: /views/workout/');