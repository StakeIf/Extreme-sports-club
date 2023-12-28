<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportEvent.php');

$modelSport = SportEvent::get();

$modelSport->sql('delete from sport_event where pk_sport_event =' . htmlspecialchars($_GET['SPORT']));

header('Location: /views/addEvent/?ID='. $_GET['ID']);