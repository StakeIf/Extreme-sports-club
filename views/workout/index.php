<?php
/**
 * $user
 */
$pageTitle = 'Тренировки';

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Event.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Sport.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Area.php');


$model = Event::get();
$modelSport = Sport::get();
$modelArea = Area::get();

$success = false;

$workoutList = $model->sql('select e.pk_event, e.data, e.name, e.description, 
a.pk_area, a.name as area, a.address, s.pk_sport_event, 
s.num_participant, s.pk_sport, sp.name as sport from event as e 
join area as a on e.pk_area = a.pk_area
join sport_event as s on e.pk_event = s.pk_event
join sport as sp on s.pk_sport = sp.pk_sport
where e.pk_type_event = 2 order by e.data desc');
$sports = $modelSport->fetchAll();
$areas = $modelArea->fetchAll();


if ($user && $user['pk_type_users'] == 1) {
    $success = true;
}
?>

<?php if ($success == true): ?>
    <link rel="stylesheet" href="../../css/form.css">
    <div class="w-100 text-end mt-2">
    <button id="openModal" type="button" class="btn btn-success">Добавить</button>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content container">
            <br>

            <strong>Введите данные для новой тренировки</strong>
            <form method="post" action="../../core/event/insert_event_workout.php?TYPE=2">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Дата</th>
                        <th scope="col">Название</th>
                        <th scope="col">Спорт</th>
                        <th scope="col">Место</th>
                        <th scope="col">Кол-во участников</th>
                        <th scope="col">Описание</th>
                        <th scope="col"></th>
                        <th scope="col"></th>

                    </tr>
                    </thead>

                    <tbody>
                    <td><input name='DATA' type="date" list="dateList" required></td>
                    <td><input name='NAME' type="text" required></td>
                    <td>
                        <select name='SPORT'>
                            <?php foreach ($sports as $sport): ?>
                                <option value="<?= $sport['pk_sport'] ?>"><?= $sport['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name='AREA'>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?= $area['pk_area'] ?> "> <?= $area['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input name='NUM' type="number" min="1" required>
                    </td>
                    <td><input name='DESC' type="text" required></td>
                    </tbody>
                </table>

                <div class="d-grid gap-2 col-3 mx-auto">
                    <button class="btn btn-primary" type="submit">Сохранить и добавить</button>
                </div>
            </form>


            <div style="margin-top: 10px" class="d-grid gap-2 col-3 mx-auto">
                <button class="close btn btn-primary" type="button">Отмена</button>
            </div>

            <br>
        </div>
    </div>

<?php endif; ?>

    <div class="container-fluid border-bottom min-vh">
        <?php flash() ?>

    <table class="table table-hover">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Дата</th>
            <th scope="col">Название</th>
            <th scope="col">Спорт</th>
            <th scope="col">Место</th>
            <th scope="col">Кол-во мест</th>
            <th scope="col">Описание</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>

        <tbody>

        <?php foreach ($workoutList as $workout): ?>
            <tr>
                <?php if ($user) {
                    if ($user['pk_type_users'] == 1) { ?>
                        <form method="post" action="../../core/event/update_event_workout.php?ID=<?= $workout['pk_event'] ?>">
                            <td><input name='DATA' type="date" list="dateList" value="<?= $workout['data'] ?>" required>
                            </td>
                            <td><input name='NAME' type="text" value="<?= $workout['name'] ?>" required></td>
                            <td>
                                <select name='SPORT'>
                                    <?php foreach ($sports as $sport): ?>
                                        <option <?= $sport['pk_sport'] == $workout['pk_sport'] ? 'selected' : '' ?>
                                                value="<?= $sport['pk_sport'] ?>"><?= $sport['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select name='AREA'>
                                    <?php foreach ($areas as $area): ?>
                                        <option <?= $area['pk_area'] == $workout['pk_area'] ? 'selected' : '' ?>
                                                value="<?= $area['pk_area'] ?> "> <?= $area['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input name='NUM' type="number" min="1" value="<?= $workout['num_participant'] ?>"
                                       required>
                            </td>
                            <td><input name='DESC' type="text" value="<?= $workout['description'] ?>" required></td>
                            <td>
                                <button type="submit" class="button">
                                    <img width="32" height="32" src="../../img/icons/save.png" alt="Сохранить">
                                </button>
                            </td>

                            <td>
                                <a href="../../core/event/delete_event_workout.php?ID=<?= $workout['pk_event'] ?>">
                                    <img style="width: 30px" src="/img/icons/trash.svg" alt="Удалить">
                                </a>
                            </td>
                        </form>


                    <?php } else { ?>
                        <td><?= $workout['data'] ?></td>
                        <td><?= $workout['name'] ?></td>
                        <td><?= $workout['sport'] ?></td>
                        <td><?= $workout['address'] ?></td>
                        <td><?= $model->sql('select free_participants(' . $workout['pk_event'] . ',' . $workout['pk_sport'] . ')')[0]['free_participants']; ?></td>
                        <td><?= $workout['description'] ?></td>
                        <td><a href="../../core/application/insert_appl_workout.php?EV=<?=$workout['pk_event']?>&SP=<?=$workout['pk_sport_event']?>" class="btn btn-primary">Пойду</a></td>
                    <?php }
                } else { ?>
                    <td><?= $workout['data'] ?></td>
                    <td><?= $workout['name'] ?></td>
                    <td><?= $workout['sport'] ?></td>
                    <td><?= $workout['address'] ?></td>
                    <td><?= $model->sql('select free_participants(' . $workout['pk_event'] . ',' . $workout['pk_sport'] . ')')[0]['free_participants']; ?></td>
                    <td><?= $workout['description'] ?></td>
                <?php } ?>

            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
    </div>

    <script src="../../js/form.js"></script>


<?php require_once(DR . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'footer.php'); ?>