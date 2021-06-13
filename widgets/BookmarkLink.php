<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\widgets;

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\widgets\WallEntryControlLink;
use humhub\modules\contentBookmarks\assets\Assets;
use humhub\modules\contentBookmarks\helpers\Url;
use humhub\modules\contentBookmarks\Module;
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
        if ($this->record->content->getContainer() === null) {
            return '';
        }
        
        Assets::register($this->getView());

        /* @var Module $module */
        $module = Yii::$app->getModule('content-bookmarks');

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
