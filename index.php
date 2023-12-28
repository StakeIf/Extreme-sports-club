<?php require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'header.php'); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'User.php');

$user = null;

if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $model = User::get();
    $user = $model->where(['pk_users' => $_SESSION['user_id']])->fetch();
}
?>
    <div class="container-fluid min-vh custom-background position-relative">
        <div class="overlay position-absolute top-0 start-0 w-100 h-100"></div>
        <div class="w-100 h-100 mt-5 mb-2 inner d-flex flex-column align-items-center">
    <?php if ($user) { ?>
            <div class="text w-100 fs-2 text-start">Welcome, <?= htmlspecialchars($user['name'] == '' ? 'новичок' : $user['name']) ?>!</div>
    <?php } else { ?>
            <div class="text w-100 fs-2 text-start">Welcome, гость!</div>
    <?php } ?>
            <span class="text w-100 fs-2 text-center">Клуб экстремальных видов спорта</span>
            <span class="text fs-5 text-center text-main">Добро пожаловать в клуб экстремальных видов спорта, место,
                где адреналин и страсть к приключениям сливаются в одно целое! Наш клуб призван объединять людей, разделяющих любовь к невероятным испытаниям и драйву.
                Независимо от того, являетесь ли вы профессиональным экстремалом или только начинаете свой путь в мире экстремальных видов спорта,
                здесь найдется место для каждого.</span>
        </div>
    </div>

<?php require_once(DR . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'footer.php'); ?>