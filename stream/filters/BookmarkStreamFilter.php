<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\stream\filters;

use humhub\modules\content\models\Content;
use humhub\modules\space\models\Membership;
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
        if (!($this->user instanceof User)) {
            $this->query->andWhere('0 = 1');
            return;
        }

        $this->query->innerJoin('content_bookmark', 'content_bookmark.content_id = content.id');
        $this->query->andWhere(['content_bookmark.user_id' => $this->user->id]);

        if ($this->user->canManageAllContent()) {
            return;
        }

        // Restrict to readable content, see ActiveQueryContent::readable()
        $this->query->leftJoin(
            'space bookmarkSpace',
            'bookmarkSpace.id = contentcontainer.pk AND contentcontainer.class = :bookmarkSpaceClass',
        );
        $this->query->leftJoin(
            'space_membership bookmarkMembership',
            'bookmarkMembership.space_id = bookmarkSpace.id AND bookmarkMembership.user_id = :bookmarkUserId AND bookmarkMembership.status = :bookmarkMemberStatus',
        );

        $spaceMemberCondition = 'bookmarkMembership.user_id IS NOT NULL';
        if (Yii::$app->getModule('space')->globalAdminCanAccessPrivateContent && $this->user->isSystemAdmin()) {
            // Same as Space::canAccessPrivateContent()
            $spaceMemberCondition = 'bookmarkSpace.id IS NOT NULL';
        }

        $userContainerCondition = ['AND',
            'contentcontainer.class = :bookmarkUserClass',
            ['OR',
                'content.visibility = :bookmarkVisibilityPublic',
                'content.contentcontainer_id = :bookmarkUserContainerId',
            ],
        ];

        if (Yii::$app->getModule('friendship')->isFriendshipEnabled()) {
            // Private profile content is readable for confirmed friends only
            $userContainerCondition[2][] = ['AND',
                'content.visibility = :bookmarkVisibilityPrivate',
                'EXISTS (SELECT 1 FROM user_friendship bf1'
                . ' INNER JOIN user_friendship bf2 ON bf2.user_id = bf1.friend_user_id AND bf2.friend_user_id = bf1.user_id'
                . ' WHERE bf1.user_id = contentcontainer.pk AND bf1.friend_user_id = :bookmarkUserId)',
            ];
        }

        $this->query->andWhere(['OR',
            'content.created_by = :bookmarkUserId',
            'content.contentcontainer_id IS NULL',
            ['AND',
                'bookmarkSpace.id IS NOT NULL',
                ['OR',
                    $spaceMemberCondition,
                    ['AND', 'content.visibility = :bookmarkVisibilityPublic', 'bookmarkSpace.visibility <> :bookmarkSpaceVisibilityNone'],
                ],
            ],
            $userContainerCondition,
        ]);

        $this->query->addParams([
            ':bookmarkUserId' => $this->user->id,
            ':bookmarkUserContainerId' => $this->user->contentcontainer_id,
            ':bookmarkSpaceClass' => Space::class,
            ':bookmarkUserClass' => User::class,
            ':bookmarkMemberStatus' => Membership::STATUS_MEMBER,
            ':bookmarkVisibilityPublic' => Content::VISIBILITY_PUBLIC,
            ':bookmarkVisibilityPrivate' => Content::VISIBILITY_PRIVATE,
            ':bookmarkSpaceVisibilityNone' => Space::VISIBILITY_NONE,
        ]);
    }
}
