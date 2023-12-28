<?php $pageTitle = 'Детализация';
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'header.php');

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Event.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportEvent.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Area.php');

/**
 * @var array $request
 */
$model = Event::get();
$modelSport = SportEvent::get();
$modelArea = Area::get();

$event = $model->where(['pk_event' => (int)$request['get']['ID']])->fetch();
if (empty($event)) {
    header("Location: /error/");
}
$sports = $modelSport->sql('select * from sport_event as e join sport as s on e.pk_sport = s.pk_sport where e.pk_event = ' . $event['pk_event']);

$area = $modelArea->where(['pk_area' => $event['pk_area']])->fetch();

$images = $model->sql('select si.path from event as e join sport_event as s on e.pk_event = s.pk_event
join sport as sp on s.pk_sport = sp.pk_sport
join sport_img as si on s.pk_sport = si.pk_sport
where e.pk_event =' . $event['pk_event']);
$success = false;
if ($user && $user['pk_type_users'] == 1) {
    $success = true;
}
?>
<link href="/css/styleSlider.css" rel="stylesheet">


<div class="container-fluid min-vh">
    <?php if ($success): ?>
        <div class="w-100 text-end mt-2">
            <a href="/views/addEvent?ID=<?= $event['pk_event'] ?>" class="btn btn-success">Редактировать</a>
        </div>
    <?php endif; ?>

    <div class="w-100 text-center mb">
        <span class="text text-black w-100 fs-2"><?= $event['name'] ?></span>
    </div>

    <div class="container">
        <div class="wrapper">
            <div class="slider">
                <?php foreach ($images as $image): ?>
                    <div class="slider__item filter">
                        <img src="/img/sports<?= $image['path'] ?>" class="card-img-top slider-img" alt="">
                    </div>
                <?php endforeach; ?>
            </div>
            <script
                    src="https://code.jquery.com/jquery-1.10.2.js"
                    integrity="sha256-it5nQKHTz+34HijZJQkpNBIHsjpV8b6QzMJs9tmOBSo="
                    crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
            <script src="/js/slick.min.js"></script>
            <script src="/js/script.js"></script>
        </div>
        <div class="d-flex flex-column align-items-center">
            <div class="text text-black w-100 fs-2 text-center"><?= $event['data'] ?></div>
            <p class="text text-black fs-5 text-card"><?= $event['description'] ?></p>


            <form method="post" action="/core/application/insert_appl_event.php?EV=<?= $event['pk_event'] ?>">
                <div class="row py-2">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Тип спорта</th>
                            <th>Мест</th>
                            <?php if ($user): ?>
                                <th>Запись</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        foreach ($sports as $sport):
                            $i++; ?>
                            <tr>
                                <td><?= $sport['name'] ?></td>
                                <td><?= $sport['num_participant'] ?></td>
                                <?php if ($user): ?>
                                    <td><input name="SPORT<?= $i ?>" class="form-check-input" type="checkbox"
                                               value="<?= $sport['pk_sport_event'] ?>" id="flexCheckDefault"></td>
                                <?php endif; ?>

                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php flash() ?>

                <?php if ($user): ?>
                    <div class="row py-2">
                        <div class="col-4"></div>
                        <div class="col-4 d-flex justify-content-center mt-auto">
                            <a class="btn btn-success mx-2"
                               href="/core/application/insert_appl_viewer.php?ID=<?= $event['pk_event'] ?>">Зритель</a>
                            <button type="submit" class="btn btn-success mx-2">Участник</button>
                        </div>
                    </div>
                <?php endif; ?>

            </form>

            <div class="text-card text-center">
                <div class="text text-black w-100 fs-2 text-center">Где будет проходить мероприятие:</div>
                <?php echo $area['ymap'] ?>
                <div>
                </div>
            </div>

        </div>
    </div>
</div>


<?php require_once(DR . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'footer.php'); ?>
