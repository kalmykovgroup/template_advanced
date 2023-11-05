<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
      Выше указанная ошибка произошла во время обработки веб-сервером вашего запроса.
    </p>
    <p>
        Мы уже знаем об этом, и передали информацию нашим специалистам.
    </p>

</div>
