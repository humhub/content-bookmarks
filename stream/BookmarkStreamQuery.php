<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\stream;

use humhub\modules\stream\models\ContentContainerStreamQuery;
use humhub\modules\stream\models\filters\ContentContainerStreamFilter;
use humhub\modules\contentBookmarks\stream\filters\BookmarkStreamFilter;
use Yii;

/**
 * BookmarkStreamQuery
 */
class BookmarkStreamQuery extends ContentContainerStreamQuery
{

    /**
     * @inheritdoc
     */
    public function beforeApplyFilters()
    {
        parent::beforeApplyFilters();
        $this->removeFilterHandler(ContentContainerStreamFilter::class);
        $this->addFilterHandler(new BookmarkStreamFilter(['user' => Yii::$app->user]));
    }

}
