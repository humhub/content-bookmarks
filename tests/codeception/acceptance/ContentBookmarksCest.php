<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace content_bookmarks\acceptance;

use content_bookmarks\AcceptanceTester;

class ContentBookmarksCest
{

    public function testBookmarkPostByRegisteredUser(AcceptanceTester $I)
    {
        $I->amUser1();

        $I->amGoingTo('save a Post to bookmark');
        $I->amOnSpace1();
        $I->waitForText('User 3 Space 1 Post Public');
        $I->clickWallEntryBookmark('Save to bookmark');

        $I->amOnProfile();
        $I->click('Saved Content');

        $I->amGoingTo('remove a Post to bookmarks');
        $I->waitForText('User 3 Space 1 Post Public');
        $I->clickWallEntryBookmark('Remove from bookmarks');

        $I->waitForText('You didn\'t save any content yet!');
    }

}
