<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'User.php');


$model = User::get();

print_r($_POST);

$model->where([
    'pk_users' => $_SESSION['user_id']
])
    ->update([
        'name' => '\'' . $_POST['NAME'] . '\'',
        'surname' => '\'' . $_POST['SURNAME'] . '\'',
        'patronymic' => '\'' . $_POST['PATRONYMIC'] . '\''
    ]);

header('Location: /views/personal/');