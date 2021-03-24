<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\contentBookmarks\models;

use humhub\components\ActiveRecord;

/**
 * This is the model class for table 'content_bookmark'.
 *
 * The followings are the available columns in table 'content_bookmark':
 * @property integer $user_id
 * @property integer $content_id
 */
class ContentBookmark extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'content_bookmark';
    }

    /**
     * @inhritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'content_id'], 'integer'],
            [['user_id', 'content_id'], 'required'],
        ];
    }
}