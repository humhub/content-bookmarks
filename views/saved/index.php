<?php

use humhub\modules\content_bookmarks\widgets\StreamViewer;
use humhub\modules\user\models\User;

/* @var User $user */
?>

<?= StreamViewer::widget(['contentContainer' => $user]); ?>
