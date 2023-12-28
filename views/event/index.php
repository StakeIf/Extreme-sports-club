<?php
$pageTitle = 'Мероприятия';

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Event.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Sport.php');


$model = Event::get();
$modelSport = Sport::get();

$allSport = $modelSport->fetchAll();

$success = false;

$eventList = $model->where(['pk_type_event' => ['1', '3']])->sort(['data' => 'asc'])->fetchAll();
if ($user && $user['pk_type_users'] == 1) {
    $success = true;
}

?>

    <div class="container-fluid border-bottom min-vh">

        <?php if ($success == true): ?>
            <div class="w-100 text-end mt-2">
                <a href="../addEvent" class="btn btn-success">Добавить</a>
            </div>
        <?php endif; ?>

        <div class="w-100 text-center mb">
            <span class="text text-black w-100 fs-2">Наши ближайшие мероприятия</span>
        </div>
        <div class="container">

            <div class="row">
                <?php foreach ($eventList as $event): ?>
                    <?php $images = $model->sql('select si.path from event as e join sport_event as s on e.pk_event = s.pk_event
join sport as sp on s.pk_sport = sp.pk_sport
join sport_img as si on s.pk_sport = si.pk_sport
where e.pk_event =' . $event['pk_event']);

                    if (count($images) == 0) {
                        $images[0]['path'] = '/alt.png';
                    }
                    ?>
                    <div class="col-12 col-lg-4 col-md-6 col-sm-12  d-flex align-items-center mb-4">
                        <div class="col h-100 d-flex">
                            <div class="cardd">
                                <img src="/img/sports<?= $images[array_rand($images)]['path'] ?>" class="card-img-top"
                                     alt="...Фото мероприятия отсутствует, так бывает...">
                                <div class="card-body card-body-border d-flex flex-column align-items-center">
                                    <h5 class="card-title text text-black text-center"><?= $event['name'] ?></h5>
                                    <p class="card-text text text-black text-center"><?= mb_substr($event['description'], 0, 50) . '...' ?></p>
                                    <p class="card-text align-self-end text text-black"><?= $event['data'] ?></p>

                                    <div class="row w-100">
                                        <div class="col-4">

                                        </div>
                                        <div class="col-4">
                                            <a href="/views/detail?ID=<?= $event['pk_event'] ?>"
                                               class="btn btn-primary mt-auto text-center">Подробнее</a>
                                        </div>
                                        <div class="col-3">

                                        </div>
                                        <div class="col-1 d-flex align-items-center justify-content-center">
                                            <?php if ($success == true): ?>
                                                <a href="/core/event/delete_event.php?ID=<?= $event['pk_event'] ?>"><img
                                                            src="/img/icons/trash.svg" class="text-end" alt="Удалить"
                                                            width="32" height="32"></a>
                                            <?php endif; ?>                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>
    </div>

<?php require_once(DR . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'footer.php'); ?>