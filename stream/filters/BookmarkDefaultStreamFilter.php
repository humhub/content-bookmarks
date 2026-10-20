<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\stream\filters;

use humhub\modules\stream\models\filters\DefaultStreamFilter;

/**
 * The same as the core {@see DefaultStreamFilter}, but hidden content is NOT omitted:
 * "hidden" means "don't show in the stream feed", while bookmarks are a personal list of saved content.
 * Archived content is still omitted by default and can be shown by the "Archived" filter.
 *
 * Note: {@see self::apply()} is a copy of {@see DefaultStreamFilter::apply()} without the hidden content handling,
 * so keep it in sync with the core method when the latter is changed.
 *
 * @see DefaultStreamFilter::apply()
 * @since 1.3.2
 */
class BookmarkDefaultStreamFilter extends DefaultStreamFilter
{
    /**
     * @inheritdoc
     */
    public function apply()
    {
        if ($this->isFilterActive(self::FILTER_FILES)) {
            $this->filterFile();
        }

        if ($this->isFilterActive(self::FILTER_ARCHIVED)) {
            $this->filterArchived();
        } elseif (!$this->streamQuery->isSingleContentQuery()) {
            $this->unFilterArchived();
        }

        if ($this->isFilterActive(self::FILTER_MINE)) {
            $this->filterMine();
        }

        if ($this->isFilterActive(self::FILTER_INVOLVED)) {
            $this->filterInvolved();
        }

        if ($this->isFilterActive(self::FILTER_PRIVATE)) {
            $this->filterPrivate();
        } elseif ($this->isFilterActive(self::FILTER_PUBLIC)) {
            $this->filterPublic();
        }
    }
}
