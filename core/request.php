<?php
$request['get'] = [];
$request['post'] = [];

foreach ($_GET as $key => $value) {
    $request['get'][$key] = htmlspecialchars($value);
}

foreach ($_POST as $key => $value) {
    $request['post'][$key] = htmlspecialchars($value);
}
