<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\stream;

use humhub\modules\contentBookmarks\models\ContentBookmark;
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
                return array_filter($result, function ($content) use ($bookmarkedContentIds) {
                    return in_array($content->id, $bookmarkedContentIds);
                });
            }

            return [];
        }

        return $result;
    }
}
