<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\content\widgets\WallEntryControls;
use humhub\modules\contentBookmarks\Events;
use humhub\modules\stream\models\WallStreamQuery;
use humhub\modules\stream\widgets\WallStreamFilterNavigation;
use humhub\modules\user\widgets\ProfileMenu;

/** @noinspection MissedFieldInspection */
return [
    'id' => 'content-bookmarks',
    'class' => 'humhub\modules\contentBookmarks\Module',
    'namespace' => 'humhub\modules\contentBookmarks',
    'events' => [
        ['class' => WallEntryControls::class, 'event' => WallEntryControls::EVENT_INIT, 'callback' => [Events::class, 'onWallEntryControlsInit']],
        ['class' => ProfileMenu::class, 'event' => ProfileMenu::EVENT_INIT, 'callback' => [Events::class, 'onProfileMenuInit']],
        ['class' => WallStreamFilterNavigation::class, 'event' =>  WallStreamFilterNavigation::EVENT_BEFORE_RUN, 'callback' => [Events::class, 'onStreamFilterBeforeRun']],
        ['class' => WallStreamQuery::class, 'event' =>  WallStreamQuery::EVENT_BEFORE_FILTER, 'callback' => [Events::class, 'onStreamFilterBeforeFilter']],
    ]
];