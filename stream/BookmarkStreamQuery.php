<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\content_bookmarks\stream;

use humhub\modules\stream\models\ContentContainerStreamQuery;

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

        // TODO: Filter only saved/bookmarked contents
    }
}
