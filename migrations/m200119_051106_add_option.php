<?php

use yii\db\Migration;

/**
 * Class m200119_051106_add_option
 */
class m200119_051106_add_option extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->update('option', [
    		'code' => 'babyblog',
		    'name' => 'Настройки sp.babyblog.ru'
	    ], [
	    	'code' => 'babyblog_auth'
	    ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200119_051106_add_option cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200119_051106_add_option cannot be reverted.\n";

        return false;
    }
    */
}
