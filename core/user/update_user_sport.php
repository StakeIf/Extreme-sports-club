<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportUser.php');


$modelSport = SportUser::get();

print_r($_POST);

$modelSport->where([
    'pk_users' => $_SESSION['user_id'],
    'pk_sport' => $_GET['SPORT']
    ])
    ->update([
        'year' => $_POST['YEAR'],
        'pk_rank' => $_POST['RANK'],
    ]);

header('Location: /views/personal/');