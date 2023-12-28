<?php
$pageTitle = 'Регистрация';

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'header.php');

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'QueryDb.php');
if (check_auth()) {
    header('Location: /');
    die;
}
?>
<?php flash() ?>

<main class="form-signin">
    <form method="post" action="../../core/do_register.php">
        <h1 class="h3 py-2 text-center fw-normal">Регистрация</h1>

        <div class="form-floating py-2">
            <input type="email" class="form-control input-rounded" id="username" name="username" placeholder="name@example.com" required>
            <label for="floatingInput">Адрес эл. почты</label>
        </div>
        <div class="form-floating py-2">
            <input type="password" class="form-control input-rounded" id="password" name="password"  placeholder="Password" required>
            <label for="floatingPassword">Пароль</label>
        </div>
        <div class="row py-2">
            <div class="col-12 d-flex justify-content-center mt-auto w-100">
                <div class="align-self-center">
                    <button type="submit" class=" btn btn-lg btn-primary text-white">Зарегестрироваться</button>
                </div>
            </div>
        </div>

    </form>
</main>

<?php require_once(DR . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'footer.php'); ?>