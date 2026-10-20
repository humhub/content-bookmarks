<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace contentBookmarks;

use humhub\modules\content\models\Content;
use humhub\modules\contentBookmarks\models\BookmarkableContent;
use humhub\modules\contentBookmarks\stream\BookmarkStreamQuery;
use humhub\modules\contentBookmarks\widgets\BookmarkStreamFilterNavigation;
use humhub\modules\post\models\Post;
use humhub\modules\space\models\Space;
use humhub\modules\stream\widgets\WallStreamFilterNavigation;
use humhub\modules\user\models\User;
use tests\codeception\_support\HumHubDbTestCase;
use Yii;

class BookmarkStreamQueryTest extends HumHubDbTestCase
{
    private function createBookmarkedPost(string $message): Post
    {
        $post = new Post(Space::findOne(['id' => 1]), Content::VISIBILITY_PUBLIC, ['message' => $message]);
        $this->assertTrue($post->save());

        $bookmarkableContent = BookmarkableContent::findOne(['id' => $post->content->id]);
        $bookmarkableContent->bookmark();
        $this->assertTrue($bookmarkableContent->isBookmarked());

        return $post;
    }

    private function getBookmarkedContentIds(array $filters = []): array
    {
        /* @var User $user */
        $user = Yii::$app->user->getIdentity();

        $query = BookmarkStreamQuery::find();
        $query->user = $user;
        $query->container = $user;
        $query->filters = $filters;

        return array_map(fn(Content $content) => $content->id, $query->all());
    }

    public function testHiddenContentIsIncludedInBookmarks()
    {
        $this->becomeUser('User1');

        $visiblePost = $this->createBookmarkedPost('Visible post');
        $hiddenPost = $this->createBookmarkedPost('Hidden post');
        Content::updateAll(['hidden' => 1], ['id' => $hiddenPost->content->id]);

        $ids = $this->getBookmarkedContentIds();
        $this->assertContains($visiblePost->content->id, $ids);
        $this->assertContains($hiddenPost->content->id, $ids);
    }

    public function testArchivedContentIsOmittedByDefault()
    {
        $this->becomeUser('User1');

        $post = $this->createBookmarkedPost('Archived post');
        Content::updateAll(['archived' => 1], ['id' => $post->content->id]);

        $this->assertNotContains($post->content->id, $this->getBookmarkedContentIds());
        $this->assertContains($post->content->id, $this->getBookmarkedContentIds([WallStreamFilterNavigation::FILTER_ARCHIVED]));
    }

    public function testHiddenFilterIsNotAvailableInBookmarksFilterNavigation()
    {
        $this->becomeUser('User1');

        $navigation = new BookmarkStreamFilterNavigation();

        $this->assertArrayNotHasKey(WallStreamFilterNavigation::FILTER_HIDDEN, $navigation->filters);
        $this->assertArrayHasKey(WallStreamFilterNavigation::FILTER_ARCHIVED, $navigation->filters);
    }
}
