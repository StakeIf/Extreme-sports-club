<?php $pageTitle = 'Карточка';
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'header.php');

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Event.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'SportEvent.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Sport.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Area.php');

$model = Event::get();
$modelSport = Sport::get();
$modelArea = Area::get();

$allSports = $modelSport->fetchAll();
$areas = $modelArea->fetchAll();

if (isset($_GET['ID'])) {
    $event = $model->sql('select event.*, area.name as area from event
join area on event.pk_area = area.pk_area where event.pk_event=' . htmlspecialchars($_GET['ID']))[0];
    if (empty($event)) {
        header("Location: /error/");
    }
    $eventSports = SportEvent::get()->sql('select e.*, s.name from sport_event as e
join sport as s on e.pk_sport = s.pk_sport where pk_event=' . $event['pk_event']);

    $choiceSports = [];
    foreach ($allSports as $sport) {
        $repeat = 0;
        foreach ($eventSports as $eventSport) {
            if ($sport['pk_sport'] == $eventSport['pk_sport']) {
                $repeat = 1;
            }
        }
        if (!$repeat) {
            $choiceSports[] = $sport;
        }
    }
} else {
    $event['pk_area'] = -1;
}

?>
<div class="container-fluid min-vh">
    <div class="w-100 text-center mt-5 mb-2">
        <span class="text text-black w-100 fs-2">Создание карточки мероприятия</span>
    </div>

    <div class="container">
        <?php if (isset($_GET['ID'])){ ?>
        <form method="post" action="/core/event/update_event.php?ID=<?= $_GET['ID'] ?>">

            <?php } else { ?>
            <form method="post" action="/core/event/insert_event.php?TYPE=1">

                <?php } ?>

                <div class="row">
                    <div class="col-4 col-lg-4 col-md-4 col-sm-4  d-flex flex-column">
                        <div class="col-1 text-center fs-4 w-100 align-self-start">
                            <span class="text text-black">Название</span>
                        </div>
                        <div class="col col-input">
                            <input maxlength="50" name="NAME" class="form-control input-rounded" type="text"
                                   placeholder="Введите название" value="<?= $event['name'] ?? '' ?>"
                                   aria-label="Name" required>
                        </div>
                        <div class="col-1 text-center fs-4 w-100 mt-1 align-self-center">
                            <span class="text text-black">Дата</span>
                        </div>
                        <div class="col col-input">
                            <input class="form-control input-rounded" name='DATA' type="date"
                                   list="dateList" value="<?= $event['data'] ?? '2023-01-01' ?>" required>
                        </div>
                        <div class="col-1 text-center fs-4 w-100 mt-1 align-self-end">
                            <span class="text text-black">Площадка</span>
                        </div>
                        <div class="col col-input">
                            <select class="form-control input-rounded" name='AREA' aria-label="Platform">
                                <?php foreach ($areas as $area): ?>
                                    <option <?= $area['pk_area'] == $event['pk_area'] ? 'selected' : '' ?>
                                            value="<?= $area['pk_area'] ?> "> <?= $area['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-7 d-flex flex-column align-self-start">
                        <div class="col-1 text-center fs-4 w-100 align-self-start">
                            <span class="text text-black">Описание</span>
                        </div>
                        <textarea name="DISC"  class="form-control border textarea-size"
                                  placeholder="Описание"
                                  id="exampleFormControlTextarea1" rows="3"
                                  required><?= $event['description'] ?? '' ?></textarea>
                    </div>

                    <?php if (isset($_GET['ID'])) { ?>
                        <div class="row py-2">
                            <div class="col-4"></div>
                            <div class="col-4 d-flex justify-content-center mt-auto">
                                <button type="submit" class="btn btn-success">Сохранить изменения</button>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row py-2">
                            <div class="col-4"></div>
                            <div class="col-4 d-flex justify-content-center mt-auto">
                                <button type="submit" class="btn btn-success">Сохранить и перейти к добавлению спорта
                                </button>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </form>

            <?php if (isset($_GET['ID'])): ?>
                <?php flash() ?>
                <form method="post" action="/core/event/insert_event_sport.php?ID=<?=$_GET['ID']?>">
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
                        <div class="col-3 d-flex flex-column">
                            <div class="col-1 text-center fs-4 w-100 mt-1">
                                <span class="text text-black">Кол-во участников</span>
                            </div>
                            <div class="col col-input">
                                <input name="NUM" class="form-control input-rounded" type="number" min="1"
                                       value="1"
                                       aria-label="Experience">
                            </div>
                        </div>
                        <div class="col-1 d-flex justify-content-between align-self-end panel">
                            <button type="submit" class="button">
                                <img src="/img/icons/plus.svg" alt="Добавить" width="32" height="32">
                            </button>
                        </div>
                    </div>
                </form>

                <?php foreach ($eventSports as $eventSport): ?>
                    <form method="post"
                          action="/core/event/update_event_sport.php?SPORT=<?= $eventSport['pk_sport_event'] ?>&ID=<?= $event['pk_event'] ?>">
                        <div class="row py-2">
                            <div class="col-4 d-flex flex-column">

                                <div class="col col-input">
                                    <input class="form-control input-rounded" type="text" min="0"
                                           value="<?= $eventSport['name'] ?>"
                                           aria-label="Experience" disabled>
                                </div>
                            </div>

                            <div class="col-3 d-flex flex-column">

                                <div class="col col-input">
                                    <input name="NUM" class="form-control input-rounded" type="number" min="0"
                                           value="<?= $eventSport['num_participant'] ?>"
                                           aria-label="Experience">
                                </div>
                            </div>
                            <div class="col-1 d-flex justify-content-between align-self-end panel">
                                <button type="submit" class="button">
                                    <img src="/img/icons/save.png" alt="Сохранить" width="32" height="32">
                                </button>
                                <a href="/core/event/delete_event_sport.php?SPORT=<?= $eventSport['pk_sport_event'] ?>&ID=<?= $event['pk_event'] ?>">
                                    <img src="../../img/icons/cross.svg" alt="Удалить" width="32" height="32">
                                </a>
                            </div>
                        </div>
                    </form>

                <?php endforeach; ?>

            <?php endif; ?>

    </div>
</div>

<?php require_once(DR . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'footer.php'); ?>
