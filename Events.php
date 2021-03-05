<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\content_bookmarks;

use humhub\modules\content\widgets\WallEntryControls;
use humhub\modules\content_bookmarks\helpers\Url;
use humhub\modules\content_bookmarks\widgets\BookmarkLink;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\user\widgets\ProfileMenu;
use Yii;

class Events
{
    public static function onWallEntryControlsInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        /* @var WallEntryControls $wallEntryControls */
        $wallEntryControls = $event->sender;
        $wallEntryControls->addWidget(BookmarkLink::class, ['record' => $wallEntryControls->object], ['sortOrder' => 450]);
    }

    public static function onProfileMenuInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        if (empty($event->sender->user) || $event->sender->user->id != Yii::$app->user->id) {
            return;
        }

        /* @var ProfileMenu $profileMenu */
        $profileMenu = $event->sender;

        $profileMenu->addEntry(new MenuLink([
            'label' => Yii::t('ContentBookmarksModule.base', 'Saved Content'),
            'url' => Url::toSavedContent($event->sender->user),
            'icon' => 'bookmark',
            'sortOrder' => 250,
            'isActive' => MenuLink::isActiveState('content_bookmarks', 'saved'),
        ]));
    }
}
