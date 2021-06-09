<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace contentBookmarks;

use humhub\modules\contentBookmarks\models\BookmarkableContent;
use humhub\modules\contentBookmarks\Module;
use humhub\modules\post\models\Post;
use tests\codeception\_support\HumHubDbTestCase;
use Yii;

class ContentBookmarksTest extends HumHubDbTestCase
{
    /**
     * @var Module
     */
    protected $module;

    /**
     * @var Post
     */
    protected $post;

    /**
     * @var BookmarkableContent
     */
    protected $content;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->module = Yii::$app->getModule('content-bookmarks');
        $this->post = Post::findOne(['id' => 1]);
        $this->content = BookmarkableContent::findOne(['id' => $this->post->content->id]);
    }

    public function testBookmarkPostByRegisteredUser()
    {
        $this->becomeUser('User1');

        $this->assertFalse($this->module->isBookmarkedRecord($this->post));
        $this->assertFalse($this->content->isBookmarked());

        $this->assertTrue($this->content->canBeBookmarked());

        $newBookmarkableContent = new BookmarkableContent();
        $this->assertFalse($newBookmarkableContent->canBeBookmarked());

        // Save to bookmark
        $this->content->bookmark();
        $this->assertTrue($this->module->isBookmarkedRecord($this->post));
        $this->assertTrue($this->content->isBookmarked());

        // Remove from bookmarks
        $this->content->bookmark();
        $this->assertFalse($this->module->isBookmarkedRecord($this->post));
        $this->assertFalse($this->content->isBookmarked());
    }

    public function testBookmarkPostByGuest()
    {
        $this->assertFalse($this->content->canBeBookmarked());

        // Try to save to bookmark by Guest
        $this->content->bookmark();
        $this->assertFalse($this->module->isBookmarkedRecord($this->post));
        $this->assertFalse($this->content->isBookmarked());
    }
}