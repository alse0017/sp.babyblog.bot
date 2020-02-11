<?php

use yii\db\Migration;

/**
 * Class m200211_151154_add_supplier
 */
class m200211_151154_add_supplier extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->insert('supplier', [
		    'name'               => 'Эрнест-опт',
		    'babyblog_shop_id'   => NULL,
		    'babyblog_buyout_id' => NULL,
		    'max_price'          => 0,
		    'import_status'      => 'stop',
		    'import_id'          => NULL,
		    'import_data'        => '{"category_links":[]}',
		    'import_init_data'   => '{"category_links":[{"url":"http://ernest-opt.ru/catalog/35","name":"Женские визитницы","babyblog_id":"49"},{"url":"http://ernest-opt.ru/catalog/113","name":"Женские зонты","babyblog_id":"52"},{"url":"http://ernest-opt.ru/catalog/36","name":"Женские ключницы","babyblog_id":"49"},{"url":"http://ernest-opt.ru/catalog/37","name":"Женские косметички","babyblog_id":"49"},{"url":"http://ernest-opt.ru/catalog/30","name":"Женские кошельки","babyblog_id":"257"},{"url":"http://ernest-opt.ru/catalog/32","name":"Женские кошельки","babyblog_id":"257"},{"url":"http://ernest-opt.ru/catalog/28","name":"Женские кошельки","babyblog_id":"257"},{"url":"http://ernest-opt.ru/catalog/29","name":"Женские кошельки","babyblog_id":"257"},{"url":"http://ernest-opt.ru/catalog/31","name":"Женские кошельки","babyblog_id":"257"},{"url":"http://ernest-opt.ru/catalog/34","name":"Женские обложки на документы","babyblog_id":"49"},{"url":"http://ernest-opt.ru/catalog/33","name":"Женские обложки на документы","babyblog_id":"49"},{"url":"http://ernest-opt.ru/catalog/116","name":"Женские обложки на документы","babyblog_id":"49"},{"url":"http://ernest-opt.ru/catalog/119","name":"Женские рюкзаки","babyblog_id":"212"},{"url":"http://ernest-opt.ru/catalog/2","name":"Женские сумки - кожаные","babyblog_id":"212"},{"url":"http://ernest-opt.ru/catalog/3","name":"Женские сумки - кожзаменитель","babyblog_id":"212"},{"url":"http://ernest-opt.ru/catalog/9","name":"Мужские барсетки - кожанные","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/11","name":"Мужские барсетки - кожанные","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/16","name":"Мужские барсетки - кожзаменитель","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/18","name":"Мужские барсетки - кожзаменитель","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/47","name":"Мужские визитницы","babyblog_id":"89"},{"url":"http://ernest-opt.ru/catalog/112","name":"Мужские зонты","babyblog_id":"92"},{"url":"http://ernest-opt.ru/catalog/48","name":"Мужские ключницы","babyblog_id":"89"},{"url":"http://ernest-opt.ru/catalog/41","name":"Мужские кошельки","babyblog_id":"260"},{"url":"http://ernest-opt.ru/catalog/42","name":"Мужские кошельки","babyblog_id":"260"},{"url":"http://ernest-opt.ru/catalog/43","name":"Мужские кошельки","babyblog_id":"260"},{"url":"http://ernest-opt.ru/catalog/39","name":"Мужские кошельки","babyblog_id":"260"},{"url":"http://ernest-opt.ru/catalog/46","name":"Мужские обложки на документы","babyblog_id":"89"},{"url":"http://ernest-opt.ru/catalog/45","name":"Мужские обложки на документы","babyblog_id":"89"},{"url":"http://ernest-opt.ru/catalog/44","name":"Мужские обложки на документы","babyblog_id":"89"},{"url":"http://ernest-opt.ru/catalog/10","name":"Мужские папки - кожанные","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/17","name":"Мужские папки - кожзаменитель","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/6","name":"Мужские портфели - кожаные","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/7","name":"Мужские портфели - кожаные","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/13","name":"Мужские портфели - кожзаменитель","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/14","name":"Мужские портфели - кожзаменитель","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/120","name":"Мужские рюкзаки","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/8","name":"Мужские сумки - кожаные","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/15","name":"Мужские сумки - кожзаменитель","babyblog_id":"317"},{"url":"http://ernest-opt.ru/catalog/23","name":"ж|м сумки дорожные - кожаные","babyblog_id":"212|317"},{"url":"http://ernest-opt.ru/catalog/24","name":"ж|м сумки дорожные - кожзаменитель","babyblog_id":"212|317"}]}',
	    ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200211_151154_add_supplier cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200211_151154_add_supplier cannot be reverted.\n";

        return false;
    }
    */
}
