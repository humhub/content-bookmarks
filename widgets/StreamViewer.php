<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\widgets;

use humhub\modules\stream\widgets\StreamViewer as BaseStreamViewer;
use Yii;

/**
 * StreamViewer shows a saved/bookmarked contents stream per current User
 */
class StreamViewer extends BaseStreamViewer
{

    /**
     * @var string the path to Stream Action to use
     */
    public $streamAction = '/content-bookmarks/saved/stream';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->messageStreamEmpty = '<b>' . Yii::t('UserModule.profile', 'You didn\'t save any content yet!') . '</b>';
    }

}
