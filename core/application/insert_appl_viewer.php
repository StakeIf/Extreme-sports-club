<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Application.php');

$model = Application::get();
$a = date('Y-m-d H:i:s');
$b = strtotime($a);
$b += 3600 * 6;
$now = date('Y-m-d H:i:s', $b);

$app = $model->where([
    'pk_event' => htmlspecialchars($_GET['ID']),
    'pk_users' => $_SESSION['user_id'],
    'pk_type_application' => 4
])->fetchAll();

if (count($app) == 0) {
    $success = $model->insert([
        'time' => '\'' . $now . '\'',
        'pk_status_application' => 1,
        'pk_type_application' => 4,
        'pk_event' => htmlspecialchars($_GET['ID']),
        'pk_users' => $_SESSION['user_id']
    ]);

    flash('Заявка создана.', 'success');
} else {
    flash('Вы уже отправляли заявку.');
}

header('Location: /views/detail/?ID=' . $_GET['ID']);
die;