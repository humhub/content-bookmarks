<?php

use yii\db\Migration;

/**
 * Class m210303_111425_initial
 */
class m210303_111425_initial extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('content_bookmark', [
            'user_id' => $this->integer(),
            'content_id' => $this->integer(),
        ]);
        $this->addPrimaryKey('pk_content_bookmarks', 'content_bookmark', 'user_id,content_id');
        $this->addForeignKey('fk_content_bookmarks_user_id', 'content_bookmark', 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk_content_bookmarks_news_id', 'content_bookmark', 'content_id', 'content', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('content_bookmarks');
    }
}
