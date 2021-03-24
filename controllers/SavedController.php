<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\controllers;

use humhub\components\access\ControllerAccess;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\contentBookmarks\actions\BookmarkStreamAction;
use humhub\modules\user\models\User;
use Yii;

class SavedController extends ContentContainerController
{
    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [
            [ControllerAccess::RULE_LOGGED_IN_ONLY => ['index']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'stream' => [
                'class' => BookmarkStreamAction::class,
                'contentContainer' => $this->contentContainer
            ],
        ];
    }

    public function actionIndex()
    {
        if (!($this->contentContainer instanceof User) || $this->contentContainer->id != Yii::$app->user->id) {
            $this->forbidden();
        }

        return $this->render('index', [
            'user' => $this->contentContainer,
        ]);
    }
}
