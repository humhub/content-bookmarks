<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\content_bookmarks;

use humhub\modules\content\widgets\WallEntryControls;
use humhub\modules\content_bookmarks\helpers\Url;
use humhub\modules\content_bookmarks\models\filters\BookmarksContentStreamFilter;
use humhub\modules\content_bookmarks\widgets\BookmarkLink;
use humhub\modules\stream\models\WallStreamQuery;
use humhub\modules\stream\widgets\WallStreamFilterNavigation;
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
            'label' => Yii::t('ContentBookmarksModule.base', 'Bookmarks'),
            'url' => Url::toSavedContent($event->sender->user),
            'icon' => 'bookmark',
            'sortOrder' => 250,
            'isActive' => MenuLink::isActiveState('content_bookmarks', 'saved'),
        ]));
    }

    public static function onStreamFilterBeforeRun($event)
    {
        if (Yii::$app->controller->module->id == 'content_bookmarks' &&
            Yii::$app->controller->id == 'saved' &&
            Yii::$app->controller->action->id == 'index') {
            // Don't display the filter on the page with already filtered contents
            return;
        }

        /* @var $wallFilterNavigation WallStreamFilterNavigation */
        $wallFilterNavigation = $event->sender;

        $wallFilterNavigation->addFilter([
            'id' => BookmarksContentStreamFilter::FILTER_BOOKMARKED,
            'title' =>  Yii::t('ContentBookmarksModule.base', 'Bookmarked'),
            'sortOrder' => 250
        ], WallStreamFilterNavigation::FILTER_BLOCK_BASIC);
    }

    public static function onStreamFilterBeforeFilter($event)
    {
        /* @var $streamQuery WallStreamQuery */
        $streamQuery = $event->sender;

        $streamQuery->addFilterHandler(BookmarksContentStreamFilter::class);
    }
}
