<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\content_bookmarks\controllers;

use humhub\components\access\ControllerAccess;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\content_bookmarks\actions\BookmarkStreamAction;

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
        return $this->render('index', [
            'user' => $this->contentContainer,
        ]);
    }
}
