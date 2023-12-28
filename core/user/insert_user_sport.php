<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportUser.php');

$modelSport = SportUser::get();


if ($_POST['SPORT'] == '-1' || $_POST['RANK'] == '-1'){
    flash('Выберите спорт и разряд');
    header('Location: /views/personal/');
    die;
}

$modelSport->insert([
    'pk_sport' => $_POST['SPORT'],
    'pk_rank' => $_POST['RANK'],
    'pk_users' => $_SESSION['user_id'],
    'year' => $_POST['YEAR']
]);

header('Location: /views/personal/');