<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Application.php');

$model = Application::get();

$model->sql('delete from application where pk_application =' . htmlspecialchars($_GET['ID']));
header('Location: /views/applications/');