<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportUser.php');

$modelSport = SportUser::get();

$modelSport->sql('delete from sport_users where pk_sport =' . htmlspecialchars($_GET['SPORT']) . 'and pk_users =' . $_SESSION['user_id']);

header('Location: /views/personal/');