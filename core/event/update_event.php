<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Event.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportEvent.php');


$model = Event::get();


$model->where(['pk_event' => $_GET['ID']])->update([
    'data' => '\'' . $_POST['DATA'] . '\'',
    'name' => '\''. $_POST['NAME'] . '\'',
    'pk_area' => $_POST['AREA'],
    'description' => '\'' . $_POST['DISC'] . '\''
]);

header('Location: /views/addEvent/?ID=' . $_GET['ID']);