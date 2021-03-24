<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\models;

use humhub\modules\content\models\Content;
use Yii;

/**
 * This is the extended model class for the Content to make it bookmarkable
 *
 * @since 1.8
 */
class BookmarkableContent extends Content
{
    /**
     * @var bool
     */
    protected $isBookmarked;

    /**
     * @return bool
     */
    public function canBeBookmarked()
    {
        return !Yii::$app->user->isGuest && !$this->isNewRecord;
    }

    public function isBookmarked()
    {
        if (isset($this->isBookmarked)) {
            return $this->isBookmarked;
        }

        if (!$this->canBeBookmarked()) {
            return false;
        }

        $this->isBookmarked = ContentBookmark::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->andWhere(['content_id' => $this->id])
            ->exists();

        return $this->isBookmarked;
    }

    /**
     * @return bool
     */
    public function bookmark()
    {
        if (!$this->canBeBookmarked()) {
            return false;
        }

        if ($this->isBookmarked()) {
            $this->isBookmarked = false;
            return (bool) ContentBookmark::deleteAll([
                'user_id' => Yii::$app->user->id,
                'content_id' => $this->id,
            ]);
        }

        $contentBookmark = new ContentBookmark();
        $contentBookmark->user_id = Yii::$app->user->id;
        $contentBookmark->content_id = $this->id;

        $this->isBookmarked = true;

        return (bool) $contentBookmark->save();
    }
}
