<?php

use yii\db\Migration;

/**
 * Class m200211_150723_add_catalog_tables
 */
class m200211_150723_add_catalog_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->execute("
CREATE TABLE `babyblog_product` (
	`id` INT(18) NOT NULL AUTO_INCREMENT,
	`import_id` INT(18) NULL DEFAULT NULL,
	`import_error` VARCHAR(1000) NULL DEFAULT NULL,
	`supplier_id` INT(18) NOT NULL,
	`supplier_product_id` VARCHAR(255) NOT NULL,
	`product_id` INT(18) NULL DEFAULT NULL,
	`active` TINYINT(1) NOT NULL DEFAULT '0',
	`name` VARCHAR(255) NULL DEFAULT NULL,
	`article` VARCHAR(255) NULL DEFAULT NULL,
	`price` DECIMAL(9,2) NULL DEFAULT '0.00',
	`category_id` INT(11) NULL DEFAULT '0',
	`description` TEXT NULL,
	`props` TEXT NULL,
	`pictures` TEXT NULL,
	`created_at` INT(11) NULL DEFAULT NULL,
	`updated_at` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `import_id` (`import_id`),
	INDEX `product_id` (`product_id`),
	INDEX `supplier_id` (`supplier_id`),
	INDEX `supplier_id_supplier_product_id` (`supplier_id`, `supplier_product_id`),
	INDEX `active` (`active`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `supplier` (
	`id` INT(18) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NULL DEFAULT NULL,
	`babyblog_shop_id` INT(18) NULL DEFAULT NULL,
	`babyblog_buyout_id` INT(18) NULL DEFAULT NULL,
	`max_price` DECIMAL(9,2) NOT NULL DEFAULT '0.00',
	`import_status` ENUM('import','after_import','stop') NOT NULL DEFAULT 'stop',
	`import_id` INT(18) NULL DEFAULT NULL,
	`import_data` TEXT NULL,
	`import_init_data` TEXT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `supplier_product` (
	`supplier_id` INT(18) NOT NULL,
	`product_id` VARCHAR(50) NOT NULL,
	`url` VARCHAR(500) NOT NULL COLLATE 'cp1251_general_ci',
	`babyblog_category_id` INT(18) NULL DEFAULT '0',
	`name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'cp1251_general_ci',
	`article` VARCHAR(50) NULL DEFAULT NULL COLLATE 'cp1251_general_ci',
	`color` VARCHAR(50) NULL DEFAULT NULL COLLATE 'cp1251_general_ci',
	`description` TEXT NULL COLLATE 'cp1251_general_ci',
	`price` DECIMAL(10,0) NULL DEFAULT NULL,
	`pictures` TEXT NULL COLLATE 'cp1251_general_ci',
	`created_at` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`supplier_id`, `product_id`),
	INDEX `supplier_id` (`supplier_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
    	");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200211_150723_add_catalog_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200211_150723_add_catalog_tables cannot be reverted.\n";

        return false;
    }
    */
}
