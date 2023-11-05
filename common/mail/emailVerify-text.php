<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/verify-email', 'token' => $user->verification_token]);
?>
Hello <?= $user->name ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>
