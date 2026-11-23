<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\stream;

use humhub\modules\contentBookmarks\models\ContentBookmark;
use humhub\modules\contentBookmarks\models\filters\BookmarksContentStreamFilter;
use humhub\modules\contentBookmarks\stream\filters\BookmarkDefaultStreamFilter;
use humhub\modules\contentBookmarks\stream\filters\BookmarkStreamFilter;
use humhub\modules\stream\models\ContentContainerStreamQuery;
use humhub\modules\stream\models\filters\ContentContainerStreamFilter;
use humhub\modules\stream\models\filters\DefaultStreamFilter;
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
        // The bookmarks are already joined by BookmarkStreamFilter
        $this->removeFilterHandler(BookmarksContentStreamFilter::class);
        // Hidden content must be included into the bookmarks, so use the default filter without omitting hidden content
        $this->removeFilterHandler(DefaultStreamFilter::class);
        $this->addFilterHandler(BookmarkDefaultStreamFilter::class);
        // Prepend the filter so that the pinned, draft and scheduled queries cloned by other filters are restricted as well
        $this->addFilterHandler(new BookmarkStreamFilter(['user' => $this->user]), true, true);
    }

    /**
     * @inheritdoc
     */
    protected function postProcessAll(array $result)
    {
        $result = parent::postProcessAll($result);

        if (count($result) > 0 && !Yii::$app->user->isGuest) {
            // Keep the top ordered records(Pin, Draft, Scheduled, Unread News) only if they are bookmarked by current user
            $bookmarkedContentIds = ContentBookmark::find()
                ->select('content_id')
                ->where(['user_id' => Yii::$app->user->id])
                ->column();

            if (count($bookmarkedContentIds) > 0) {
                return array_filter($result, fn($content) => in_array($content->id, $bookmarkedContentIds));
            }

            return [];
        }

        return $result;
    }
}
