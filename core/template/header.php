<?php
// Подключаем константы
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'define.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'request.php');


/**
 * @var string $pageTitle
 */
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'User.php');

$user = null;
if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $model = User::get();
    $user = $model->where(['pk_users' => $_SESSION['user_id']])->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title><?= $pageTitle ?? 'Главная' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link type="image/x-icon" href="../../img/icons/fav.png" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="../../css/style.css?<?php echo time();?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<header role="banner" id="header">
    <nav class="navbar bg-dark navbar-expand-lg w-100">
        <div class="container-fluid">
            <a href="/" class="navbar-brand">
                <span class="text">EXTREME SPORTS CLUB</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent"
                    aria-controls="navContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav mr-auto mb-3 mb-lg-0 w-100">
                    <li class="nav-item w-100">
                        <a href="/"
                           class="nav-link text w-100 text-center fs-4 <?= $pageTitle ??  'disabled'?>">Главная</a>
                    </li>
                    <li class="nav-item w-100">
                        <a href="/views/event"
                           class="nav-link text w-100 text-center fs-4 <?= $pageTitle == 'Мероприятия' ? 'disabled' : '' ?>">Мероприятия</a>
                    </li>
                    <li class="nav-item w-100">
                        <a href="/views/workout"
                           class="nav-link text w-100 text-center fs-4 <?= $pageTitle == 'Тренировки' ? 'disabled' : '' ?>">Тренировки</a>
                    </li>
                    <li class="nav-item w-100">
                        <?php if ($user) { ?>
                            <a href="/views/personal/"
                               class="nav-link text w-100 text-center fs-4 <?= $pageTitle == 'Профиль' ? 'disabled' : '' ?>">Профиль</a>
                        <?php } else { ?>
                            <a href="/views/login/"
                               class="nav-link text w-100 text-center fs-4 <?= $pageTitle == 'Авторизация' ? 'disabled' : '' ?>">Войти</a>
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main role="main">