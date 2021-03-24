<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\models\filters;

use humhub\modules\stream\models\filters\StreamQueryFilter;
use Yii;

class BookmarksContentStreamFilter extends StreamQueryFilter
{

    const FILTER_BOOKMARKED = 'entry_bookmarked';

    /**
     * Array of stream filters to apply to the query.
     * There are the following filter available:
     *
     *  - 'entry_bookmarked': Filters with only bookmarked wall entries
     *
     * @var array
     */
    public $filters = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filters'], 'safe']
        ];
    }

    public function apply()
    {
        if ($this->isFilterActive(self::FILTER_BOOKMARKED)) {
            $this->filterBookmarked();
        }
    }

    public function isFilterActive($filter)
    {
        return in_array($filter, $this->filters);
    }

    protected function filterBookmarked()
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $this->query->innerJoin('content_bookmark', 'content.id = content_bookmark.content_id');
        $this->query->andWhere('content_bookmark.user_id = :userId', [':userId' => Yii::$app->user->id]);
    }
}
