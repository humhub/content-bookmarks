<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\helpers;

use humhub\modules\content\models\Content;
use humhub\modules\user\models\User;
use yii\helpers\Url as BaseUrl;

class Url extends BaseUrl
{
    const ROUTE_BOOKMARK_CONTENT = '/content-bookmarks/bookmark/update';
    const ROUTE_SAVED_CONTENT = '/content-bookmarks/saved';

    public static function toContent($route, Content $content)
    {
        return $content->getContainer()->createUrl($route, ['id' => $content->id]);
    }

    public static function toBookmarkContent(Content $content)
    {
        return static::toContent(static::ROUTE_BOOKMARK_CONTENT, $content);
    }

    public static function toSavedContent(User $user)
    {
        return $user->createUrl(static::ROUTE_SAVED_CONTENT);
    }

}