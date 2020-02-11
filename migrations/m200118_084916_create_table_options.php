<?php

use yii\db\Migration;

/**
 * Class m200118_084916_create_table_options
 */
class m200118_084916_create_table_options extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->execute("
CREATE TABLE `option` (
	`code` VARCHAR(50) NOT NULL COMMENT 'Код',
	`name` VARCHAR(255) NOT NULL COMMENT 'Название',
	`data` LONGTEXT NULL COMMENT 'Данные',
	`sort` INT(11) NULL DEFAULT NULL COMMENT 'Сортировка',
	PRIMARY KEY (`code`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
    	");

	    $data = [
		    [
			    'code' => 'babyblog_auth',
			    'name' => 'Данные для авторизации на sp.babyblog.ru',
			    'data' => '{"login":"","password":""}',
			    'sort' => '',
		    ],
	    ];
	    foreach($data as $item)
	    {
		    $this->db->schema->insert('option', $item);
	    }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200118_084916_create_table_options cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200118_084916_create_table_options cannot be reverted.\n";

        return false;
    }
    */
}
