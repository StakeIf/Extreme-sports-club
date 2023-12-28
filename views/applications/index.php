<?php
/**
 * $user
 */
$pageTitle = 'Заявки';

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'header.php');

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Application.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Status.php');


$model = Application::get();
$modelStatus = Status::get();


$statuses = $modelStatus->fetchAll();

$success = false;
$sql = 'select 
ap.pk_application, ap.time, ap.comment_status, 
ap.pk_status_application, sa.name as status, 
ap.pk_type_application, ta.name as type, ap.pk_event, e.name as event, te.name as type_ev,
ap.pk_users, us.email_users, ap.pk_sport_event

from application as ap
join status_application as sa on ap.pk_status_application = sa.pk_status_application
join type_application as ta on ap.pk_type_application = ta.pk_type_application
join event as e on ap.pk_event = e.pk_event
join type_event as te on e.pk_type_event = te.pk_type_event
join users as us on ap.pk_users = us.pk_users';


if ($user && $user['pk_type_users'] == 1) {
    $success = true;
    $apps = $model->sql($sql . ' order by ap.time desc');
} else {
    $apps = $model->sql($sql . ' where ap.pk_users =' . $user['pk_users'] . 'order by ap.time desc');
}
?>

    <div class="container-fluid border-bottom min-vh">
        <?php flash() ?>

        <table class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Дата</th>
                <?php if ($success) { ?>
                    <th scope="col">Пользователь</th>
                <?php } ?>
                <th scope="col">Мероприятие</th>
                <th scope="col">Тип</th>
                <th scope="col">Спорт</th>
                <th scope="col">Вид</th>
                <th scope="col">Статус</th>
                <th scope="col">Комментарий</th>
                <th scope="col"></th>
            </tr>
            </thead>

            <tbody>

            <?php foreach ($apps as $app): ?>
                <tr>
                    <?php if ($success) {
                        $sq = $app['pk_sport_event'] ?? 0; ?>
                        <form method="post" action="/core/application/update_appl.php?ID=<?= $app['pk_application'] ?>">
                            <td><?= $app['time'] ?></td>
                            <?php if ($success) { ?>
                                <td><?= $app['email_users'] ?></td>
                            <?php } ?>
                            <td><?= $app['event'] ?></td>
                            <td><?= $app['type_ev'] ?></td>

                            <td><?= ($model->sql('select name from sport as s
join sport_event as se on s.pk_sport=se.pk_sport  
where se.pk_sport_event =' . $sq))[0]['name'] ?? '-'?></td>

                            <td><?= $app['type'] ?></td>

                            <td>
                                <select name='STATUS'>
                                    <?php foreach ($statuses as $status): ?>
                                        <option <?= $status['pk_status_application'] == $app['pk_status_application'] ? 'selected' : ''?>
                                            value="<?= $status['pk_status_application']?>"><?= $status['name']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <td>
                                <input name='COMMENT' type="text" value="<?= $app['comment_status'] ?>" required></td>
                            </td>

                            <td>
                                <button  type="submit" class="button">
                                    <img src="../../img/icons/save.png" alt="Сохранить" width="32" height="32">
                                </button>
                            </td>

                            <td><a href="/core/application/delete_appl.php?ID=<?=$app['pk_application']?>">
                                    <img src="/img/icons/trash.svg" alt="Удалить" height="32" width="32">
                                </a>
                            </td>
                        </form>


                    <?php } else {
                        $sq = $app['pk_sport_event'] ?? 0; ?>
                        <td><?= $app['time'] ?></td>
                        <td><?= $app['event'] ?></td>
                        <td><?= $app['type_ev'] ?></td>
                        <td><?= ($model->sql('select name from sport as s
join sport_event as se on s.pk_sport=se.pk_sport  
where se.pk_sport_event =' . $sq))[0]['name'] ?? '-'?></td>
                        <td><?= $app['type'] ?></td>
                        <td><strong><?= $app['status'] ?></strong></td>
                        <td><?= $app['comment_status'] ?></td>

                        <td><a href="/core/application/delete_appl.php?ID=<?=$app['pk_application']?>">
                                <img src="/img/icons/trash.svg" alt="Удалить" height="32" width="32">
                            </a>
                        </td>
                    <?php } ?>

                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <script src="../../js/form.js"></script>


<?php require_once(DR . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'footer.php'); ?>