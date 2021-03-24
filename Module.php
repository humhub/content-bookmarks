<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks;

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\contentBookmarks\models\BookmarkableContent;

class Module extends \humhub\components\Module
{
    
    public $resourcesPath = 'resources';
    
    /**
     * @param ContentActiveRecord $record
     * @return bool
     */
    public function isBookmarkedRecord($record)
    {
        if (!($record->content instanceof Content) || $record->content->isNewRecord) {
            return false;
        }

        $bookmarkableContent = BookmarkableContent::findOne(['id' => $record->content->id]);

        return $bookmarkableContent && $bookmarkableContent->isBookmarked();
    }
}