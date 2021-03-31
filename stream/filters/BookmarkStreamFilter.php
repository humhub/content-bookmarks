<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\stream\filters;

use humhub\modules\content\models\Content;
use humhub\modules\space\models\Space;
use humhub\modules\stream\models\filters\StreamQueryFilter;
use humhub\modules\user\models\User;
use Yii;

/**
 * This stream query filter manages the scope of a bookmarked stream.
 *
 * @since 1.8
 */
class BookmarkStreamFilter extends StreamQueryFilter
{
    /**
     * @var User
     */
    public $user;

    /**
     * @inheritdoc
     */
    public function apply()
    {
        if (!$this->user) {
            return;
        }

        $this->query->innerJoin('content_bookmark', 'content_bookmark.content_id = content.id');
        $this->query->andWhere(['content_bookmark.user_id' => $this->user->id]);

        // Limit to public posts when no member
        if (Yii::$app->user->isGuest) {
            $this->query->andWhere('content.visibility = :visibility', [':visibility' => Content::VISIBILITY_PUBLIC]);
        } else if (!Yii::$app->user->getIdentity()->canViewAllContent()) {
            // Limit only if current User/Admin cannot view all content
            $this->query->andWhere('content.visibility = :visibility' .
                ' OR content.created_by = :userId' .
                ' OR (contentcontainer.class = :spaceClass AND contentcontainer.pk IN (SELECT space_id FROM space_membership WHERE user_id = :userId))' .
                ' OR (contentcontainer.class = :userClass AND contentcontainer.pk IN (SELECT user_id FROM user_friendship WHERE friend_user_id = :userId))', [
                ':visibility' => Content::VISIBILITY_PUBLIC,
                ':userId' => $this->user->id,
                ':spaceClass' => Space::class,
                ':userClass' => User::class,
            ]);
        }
    }
}
