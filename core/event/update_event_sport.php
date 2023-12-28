<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportEvent.php');


$modelSport = SportEvent::get();


$modelSport->where([
    'pk_sport_event' => $_GET['SPORT']
])
    ->update([
        'num_participant' => $_POST['NUM'],
    ]);

header('Location: /views/addEvent/?ID=' . $_GET['ID']);