<?php

use humhub\modules\contentBookmarks\widgets\StreamViewer;
use humhub\modules\user\models\User;

/* @var User $user */
?>

<?= StreamViewer::widget(['contentContainer' => $user]); ?>
