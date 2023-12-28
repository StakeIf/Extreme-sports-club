<?php
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Application.php');

$model = Application::get();
$model = Application::get();
$a = date('Y-m-d H:i:s');
$b = strtotime($a);
$b += 3600 * 6;
$now = date('Y-m-d H:i:s', $b);

$model->where(['pk_application' => $_GET['ID']])->update([
    'time_status' =>'\''. $now . '\'',
    'comment_status' => '\''. $_POST['COMMENT'] . '\'',
    'changer' => $_SESSION['user_id'],
    'pk_status_application' => '\'' . $_POST['STATUS'] . '\''
]);

header('Location: /views/applications/');