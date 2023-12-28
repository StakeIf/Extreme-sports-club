<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Event.php');


$model = Event::get();

$model->sql('delete from event where pk_event =' . htmlspecialchars($_GET['ID']));
header('Location: /views/event/');