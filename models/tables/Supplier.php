<?php

namespace app\models\tables;

use frostealth\yii2\behaviors\ArrayFieldBehavior;
use Yii;

/**
 * This is the model class for table "{{%supplier}}".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $babyblog_shop_id
 * @property int|null $babyblog_buyout_id
 * @property float $max_price
 * @property string $import_status
 * @property int|null $import_id
 * @property array|null $import_data
 * @property array|null $import_init_data
 */
class Supplier extends \yii\db\ActiveRecord
{
	const IMPORT_STATUS_IMPORT = 'import';
	const IMPORT_STATUS_AFTER_IMPORT = 'after_import';
	const IMPORT_STATUS_STOP = 'stop';

	const SUPPLIER_ERNEST_ID = 1;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return '{{%supplier}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['import_data', 'import_init_data'], 'safe'],
			[['babyblog_shop_id', 'babyblog_buyout_id', 'import_id'], 'integer'],
			[['max_price'], 'number'],
			[['import_status'], 'in', 'range' => [static::IMPORT_STATUS_IMPORT, static::IMPORT_STATUS_AFTER_IMPORT, static::IMPORT_STATUS_STOP]],
			[['name'], 'string', 'max' => 255],
		];
	}

	public function behaviors()
	{
		return [
			[
				'class'               => ArrayFieldBehavior::class,
				'attributes'          => ['import_data', 'import_init_data'],
				'defaultEncodedValue' => NULL,
				'defaultDecodedValue' => [],
			]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'                 => 'ID',
			'name'               => 'Name',
			'babyblog_shop_id'   => 'Babyblog Shop ID',
			'babyblog_buyout_id' => 'Babyblog Buyout ID',
			'max_price'          => 'Max Price',
			'import_status'      => 'Import Status',
			'import_id'          => 'Import ID',
			'import_data'        => 'Import Data',
			'import_init_data'   => 'Import Init Data',
		];
	}

	/**
	 * {@inheritdoc}
	 * @return \app\models\tables\queries\SupplierQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\models\tables\queries\SupplierQuery(get_called_class());
	}

	/**
	 * Деактивация товаров, которых небыло в выгрузке
	 */
	public function deactivateBabyblogProducts()
	{
		$babyblogProducts = BabyblogProduct::find()
			->with('supplier')
			->select(['id', 'product_id', 'active', 'supplier_id'])
			->where([
				'supplier_id'  => $this->id,
				'active'       => 1
			])
			->andWhere(['!=', 'import_id', $this->import_id])
			->all();

		if($babyblogProducts)
		{
			foreach($babyblogProducts as $product)
			{
				$product->importDeactivate();
			}
		}

		return count($babyblogProducts);
	}

	/**
	 * Активация товаров, которые появились в выгрузке
	 */
	public function activateBabyblogProducts()
	{
		$babyblogProducts = BabyblogProduct::find()
			->with('supplier')
			->select(['id', 'product_id', 'active', 'supplier_id'])
			->where([
				'supplier_id'  => $this->id,
				'import_id'    => $this->import_id,
				'active'       => 0
			])
			->all();

		if($babyblogProducts)
		{
			foreach($babyblogProducts as $product)
			{
				$product->importActivate();
			}
		}

		return count($babyblogProducts);
	}
}