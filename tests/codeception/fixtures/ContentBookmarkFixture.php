<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\tests\codeception\fixtures;

use humhub\modules\contentBookmarks\models\ContentBookmark;
use yii\test\ActiveFixture;

class ContentBookmarkFixture extends ActiveFixture
{
    public $modelClass = ContentBookmark::class;
    public $dataFile = '@content-bookmarks/tests/codeception/fixtures/data/content_bookmark.php';
}
