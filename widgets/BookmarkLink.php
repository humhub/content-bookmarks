<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\content_bookmarks\widgets;

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\widgets\WallEntryControlLink;
use humhub\modules\content_bookmarks\assets\Assets;
use humhub\modules\content_bookmarks\helpers\Url;
use humhub\modules\content_bookmarks\Module;
use Yii;

/**
 * BookmarkLink for Wall Entries shows a bookmark link.
 *
 * @since 1.8
 */
class BookmarkLink extends WallEntryControlLink
{

    /**
     * @var ContentActiveRecord
     */
    public $record;

    public function init()
    {
        Assets::register($this->getView());

        /* @var Module $module */
        $module = Yii::$app->getModule('content_bookmarks');

        if ($module->isBookmarkedRecord($this->record)) {
            $this->label = Yii::t('ContentBookmarksModule.base', 'Remove from bookmarks');
            $this->icon = 'fa-bookmark';
        } else {
            $this->label = Yii::t('ContentBookmarksModule.base', 'Save as bookmark');
            $this->icon = 'fa-bookmark-o';
        }
        $this->options = [
            'data-action-click' => 'bookmark',
            'data-action-url' => Url::toBookmarkContent($this->record->content),
        ];

        parent::init();
    }
}