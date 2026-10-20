<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\widgets;

use humhub\modules\stream\widgets\WallStreamFilterNavigation;

/**
 * Filter navigation of the bookmarks stream.
 * The "Hidden" filter is not available here because bookmarks always include hidden content.
 *
 * @since 1.3.2
 */
class BookmarkStreamFilterNavigation extends WallStreamFilterNavigation
{
    /**
     * @inheritdoc
     */
    public function addFilter($filter, $blockId = null)
    {
        if (($filter['id'] ?? null) === static::FILTER_HIDDEN) {
            return;
        }

        parent::addFilter($filter, $blockId);
    }
}
