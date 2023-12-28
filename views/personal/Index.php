<?php
$pageTitle = 'Профиль';

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'header.php');

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Sport.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Rank.php');

$user = null;

if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $model = User::get();
    $modelSport = Sport::get();
    $modelRank = Rank::get();

    $user = $model->where(['pk_users' => $_SESSION['user_id']])->fetch();
    $allSports = $modelSport->fetchAll();
    $allRank = $modelRank->fetchAll();

    $UserSports = $model->sql('select su.*, r.name as rank, s.*  from sport_users as su
join rank as r on su.pk_rank = r.pk_rank
join sport as s on su.pk_sport = s.pk_sport
join users as u on su.pk_users = u.pk_users
where u.pk_users =' . $_SESSION['user_id'] . ' order by su.year desc');

    $choiceSports = [];
    foreach ($allSports as $sport) {
        $repeat = 0;
        foreach ($UserSports as $userSport) {
            if ($sport['pk_sport'] == $userSport['pk_sport']) {
                $repeat = 1;
            }
        }
        if (!$repeat){
            $choiceSports[] = $sport;
        }
    }
}
?>
<?php if ($user) { ?>
    <div class="container-fluid min-vh">
        <div class="w-100 text-end mt-2">
            <span class="text text-black w-100 fs-5 text-decoration-underline highlight-list">
                <a href="../applications" >Список заявок</a>
            </span>
        </div>
        <div class="w-100 text-center">
            <span class="text text-black w-100 fs-2">Личный кабинет</span>
        </div>
        <div class="container">
            <form method="post" action="/core/user/update_user.php">
                <div class="row">
                    <div class="col-4 col-lg-4 col-md-4 col-sm-4  d-flex flex-column">
                        <div class="col-1 text-center fs-4 w-100 align-self-start">
                            <span class="text text-black">Фамилия</span>
                        </div>
                        <div class="col col-input">
                            <input maxlength="30" name="SURNAME" class="form-control input-rounded" type="text" value="<?= $user['surname'] ?>"
                                   aria-label="Familia" required>
                        </div>
                        <div class="col-1 text-center fs-4 w-100 mt-1 align-self-center">
                            <span class="text text-black">Имя</span>
                        </div>
                        <div class="col col-input">
                            <input maxlength="30" name="NAME" class="form-control input-rounded" type="text" value="<?= $user['name'] ?>"
                                   aria-label="Name" required>
                        </div>
                        <div class="col-1 text-center fs-4 w-100 mt-1 align-self-end">
                            <span class="text text-black">Отчество</span>
                        </div>
                        <div class="col col-input">
                            <input maxlength="30" name="PATRONYMIC" class="form-control input-rounded" type="text" value="<?= $user['patronymic'] ?>"
                                   aria-label="Surname" required>
                        </div>
                    </div>
                    <div class="col-4">

                    </div>
                    <div class="col-3 d-flex align-items-center">
                        <img src="../../img/user.png" class="img-fluid border" alt="Аватарка">
                    </div>
                </div>

                <div class="row py-2">
                    <div class="col-4"></div>
                    <div class="col-4 d-flex justify-content-center mt-auto">
                        <button type="submit" class="btn btn-success">Сохранить общую информацию</button>
                    </div>
                </div>
            </form>


            <?php flash() ?>
            <form method="post" action="../../core/user/insert_user_sport.php">
                <div class="row py-2">
                    <div class="col-4 d-flex flex-column">
                        <div class="col-1 text-center fs-4 w-100 mt-1">
                            <span class="text text-black">Тип спорта</span>
                        </div>
                        <div class="col col-input">
                            <select class="form-control input-rounded align-self-end" name='SPORT' required>
                                <option value="-1" selected>Выберите спорт</option>
                                <?php foreach ($choiceSports as $sport): ?>
                                    <option value="<?= $sport['pk_sport'] ?>"><?= $sport['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4 d-flex flex-column">
                        <div class="col-1 text-center fs-4 w-100 mt-1">
                            <span class="text text-black">Разряд</span>
                        </div>
                        <div class="col col-input">
                            <select class="form-control input-rounded align-self-end" name='RANK' required>
                                <option value="-1" selected>Выберите разряд</option>
                                <?php foreach ($allRank as $rank): ?>
                                    <option value="<?= $rank['pk_rank'] ?>"><?= $rank['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-3 d-flex flex-column">
                        <div class="col-1 text-center fs-4 w-100 mt-1">
                            <span class="text text-black">Год начала занятий</span>
                        </div>
                        <div class="col col-input">
                            <input name="YEAR" class="form-control input-rounded" type="number" min="1900" max="2023"
                                   value="2023"
                                   aria-label="Experience">
                        </div>
                    </div>
                    <div class="col-1 d-flex justify-content-between align-self-end panel">
                        <button type="submit" class="button">
                            <img src="../../img/icons/plus.svg" alt="Добавить" width="32" height="32">
                        </button>
                    </div>
                </div>
            </form>

            <?php foreach ($UserSports as $UserSport): ?>
                <form method="post" action="../../core/user/update_user_sport.php?SPORT=<?=$UserSport['pk_sport']?>">
                    <div class="row py-2">
                        <div class="col-4 d-flex flex-column">

                            <div class="col col-input">
                                <input class="form-control input-rounded" type="text" min="0"
                                       value="<?= $UserSport['name'] ?>"
                                       aria-label="Experience" disabled>
                            </div>
                        </div>
                        <div class="col-4 d-flex flex-column">
                            <div class="col col-input">
                                <select class="form-control input-rounded align-self-end" name='RANK'>
                                    <?php foreach ($allRank as $rank): ?>
                                        <option <?= $rank['pk_rank'] == $UserSport['pk_rank'] ? 'selected' : '' ?>
                                                value="<?= $rank['pk_rank'] ?>"><?= $rank['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3 d-flex flex-column">

                            <div class="col col-input">
                                <input name="YEAR" class="form-control input-rounded" type="number" min="0"
                                       value="<?= $UserSport['year'] ?>"
                                       aria-label="Experience">
                            </div>
                        </div>
                        <div class="col-1 d-flex justify-content-between align-self-end panel">
                            <button type="submit" class="button">
                                <img src="../../img/icons/save.png" alt="Сохранить" width="32" height="32">
                            </button>
                            <a href="../../core/user/delete_user_sport.php?SPORT=<?= $UserSport['pk_sport'] ?>">
                                <img src="../../img/icons/cross.svg" alt="Удалить" width="32" height="32">
                            </a>
                        </div>
                    </div>
                </form>

            <?php endforeach; ?>


            <div class="row py-2">
                <div class="col-4"></div>
                <div class="col-4 d-flex justify-content-center mt-auto">
                    <a href="../../core/do_logout.php" class="btn btn-danger">Выйти</a>
                </div>
            </div>
        </div>
    </div>

<?php } else { ?>

<?php } ?>

<?php require_once(DR . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'footer.php'); ?>