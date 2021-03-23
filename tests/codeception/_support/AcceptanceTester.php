<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace content_bookmarks;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \AcceptanceTester
{
    use _generated\AcceptanceTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @param string $waitBookmarkButtonText
     */
    public function clickWallEntryBookmark($waitBookmarkButtonText) {
        $this->jsClick('[data-stream-entry=1] [data-toggle=dropdown]');
        $this->waitForText($waitBookmarkButtonText);
        $this->jsClick('[data-stream-entry=1] [data-action-click=bookmark]');
    }
}