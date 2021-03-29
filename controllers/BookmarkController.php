<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\controllers;

use humhub\components\access\ControllerAccess;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\contentBookmarks\models\BookmarkableContent;
use Yii;
use yii\web\HttpException;

class BookmarkController extends ContentContainerController
{
    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [
            [ControllerAccess::RULE_LOGGED_IN_ONLY => ['update']],
        ];
    }

    /**
     * Bookmark/Unbookmark a Content record
     *
     * @param int $id
     */
    public function actionUpdate($id)
    {
        $content = BookmarkableContent::findOne(['id' => $id]);

        if (!$content) {
            throw new HttpException(404, Yii::t('ContentBookmarksModule.base', 'Invalid content id given!'));
        }

        if ($content->bookmark()) {
            if ($content->isBookmarked()) {
                $this->getView()->success(Yii::t('ContentBookmarksModule.base', 'Content successfully bookmarked. You can access it directly from your profile.'));
            } else {
                $this->getView()->info(Yii::t('ContentBookmarksModule.base', 'Content successfully removed from bookmarks.'));
            }
        } else {
            $this->getView()->error(Yii::t('ContentBookmarksModule.base', 'You cannot bookmark this content!'));
        }
    }
}
