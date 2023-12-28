<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Application.php');

print_r($_POST);
$model = Application::get();


$a = date('Y-m-d H:i:s');
$b = strtotime($a);
$b += 3600 * 6;
$now = date('Y-m-d H:i:s', $b);

if (count($_POST) == 0) {
    flash('Выберите спорт.');
    header('Location: /views/detail/?ID=' . $_GET['EV']);
    die();
}

foreach ($_POST as $sport){
    $app = $model->where([
        'pk_event' => htmlspecialchars($_GET['EV']),
        'pk_users' => $_SESSION['user_id'],
        'pk_sport_event' => $sport
    ])->fetchAll();

    if (count($app) == 0) {
        $success = $model->insert([
            'time' => '\'' . $now . '\'',
            'pk_status_application' => 1,
            'pk_type_application' => 1,
            'pk_event' => htmlspecialchars($_GET['EV']),
            'pk_users' => $_SESSION['user_id'],
            'pk_sport_event' => $sport
        ]);

        flash('Заявка создана.', 'success');
    } else {
        flash('Вы уже отправляли заявку.');
    }
}


header('Location: /views/detail/?ID=' . $_GET['EV']);
die;