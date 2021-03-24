<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\actions;

use humhub\modules\contentBookmarks\stream\BookmarkStreamQuery;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\user\models\User;

/**
 * BookmarkStreamAction
 */
class BookmarkStreamAction extends ContentContainerStream
{
    /**
     * @inheritdoc
     */
    public $streamQueryClass = BookmarkStreamQuery::class;

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        if (!($this->contentContainer instanceof User)) {
            return false;
        }

        return parent::beforeRun();
    }
}
