<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'User.php');

$model = User::get();

// Проверим, не занято ли имя пользователя
$mail = '\''. $_POST['username'] . '\'';
$users = $model->where(['email_users'=>$mail])->fetchAll();

if (count($users) > 0) {
    flash('Пользователь с такой почтой уже зарегестрирован.');
    header('Location: /views/registration/'); // Возврат на форму регистрации
    die; // Остановка выполнения скрипта
}

$model->registration($_POST['username'], $_POST['password']);

header('Location: /views/login/');