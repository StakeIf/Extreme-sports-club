<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'User.php');

$model = User::get();

// проверяем наличие пользователя с указанным юзернеймом
$mail = '\''. $_POST['username'] . '\'';
$pass = '\''. $_POST['password'] . '\'';

$users = $model->where(['email_users'=>$mail, 'password' => $pass])->fetchAll();

if (count($users) == 0) {
    flash('Пользователь с такими данными не зарегистрирован');
    header('Location: /views/login/');
    die;
} else {
    $_SESSION['user_id'] = $users[0]['pk_users'];
    header('Location: /');
}