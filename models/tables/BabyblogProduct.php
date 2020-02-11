<?php

namespace app\models\tables;

use app\components\babyblog\Babyblog;
use app\exceptions\BabyblogProductImportException;
use frostealth\yii2\behaviors\ArrayFieldBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%babyblog_product}}".
 *
 * @property int $id
 * @property int|null $import_id
 * @property string|null $import_error
 * @property int $supplier_id
 * @property string $supplier_product_id
 * @property bool $active
 * @property int|null $product_id
 * @property string|null $name
 * @property string|null $article
 * @property float|null $price
 * @property int|null $category_id
 * @property string|null $description
 * @property array|null $props
 * @property array|null $pictures
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Supplier $supplier
 */
class BabyblogProduct extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return '{{%babyblog_product}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['props', 'pictures'], 'safe'],
			['active', 'default', 'value' => 1],
			[['import_id', 'supplier_id', 'product_id', 'category_id'], 'integer'],
			[['description'], 'string'],
			[['active'], 'boolean'],
			[['supplier_id', 'supplier_product_id'], 'required'],
			[['price'], 'number'],
			[['import_error'], 'string', 'max' => 1000],
			[['supplier_product_id', 'name', 'article'], 'string', 'max' => 255],
		];
	}

	public function behaviors()
	{
		return [
			TimestampBehavior::class,
			[
				'class'               => ArrayFieldBehavior::class,
				'attributes'          => ['props', 'pictures'],
				'defaultEncodedValue' => NULL,
				'defaultDecodedValue' => [],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'                  => 'ID',
			'import_id'           => 'Import ID',
			'import_status'       => 'Import Status',
			'import_error'        => 'Import Error',
			'supplier_id'         => 'Supplier ID',
			'supplier_product_id' => 'Supplier Product ID',
			'product_id'          => 'Product ID',
			'name'                => 'Name',
			'article'             => 'Article',
			'price'               => 'Price',
			'category_id'         => 'Category ID',
			'description'         => 'Description',
			'props'               => 'Props',
			'pictures'            => 'Pictures',
			'created_at'          => 'Created At',
			'updated_at'          => 'Updated At',
		];
	}

	/**
	 * {@inheritdoc}
	 * @return \app\models\tables\queries\BabyblogProductQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\models\tables\queries\BabyblogProductQuery(get_called_class());
	}

	/**
	 * @return ActiveQuery
	 */
	public function getSupplier()
	{
		return $this->hasOne(Supplier::class, ['id' => 'supplier_id']);
	}

	/**
	 * @throws BabyblogProductImportException
	 */
	public function import()
	{
		if($this->isNewRecord || $this->dirtyAttributes)
		{
			throw new BabyblogProductImportException('Require save product before import');
		}

		$supplier = $this->supplier;
		$this->import_id = $supplier->import_id;
		$this->import_error = NULL;
		$this->save(false);

		try
		{
			$babyblog = Babyblog::getInstance();

			$this->importPhotos();

			$data = [
				'shop_id'      => $supplier->babyblog_shop_id,
				'buyout_id'    => $supplier->babyblog_buyout_id,
				'product_id'   => $this->product_id ? $this->product_id:NULL,
				'name'         => $this->name,
				'article'      => $this->article,
				'price'        => $this->price,
				'category_id'  => $this->category_id,
				'description'  => $this->description,
				'field'        => $this->props,
				'microtime'    => microtime(true),
				'photos'       => [],
				'photo_width'  => [],
				'photo_height' => [],
				'main_photo'   => NULL,
			];

			if(!empty($this->pictures) && is_array($this->pictures))
			{
				foreach($this->pictures as $picture)
				{
					if(empty($picture['src']))
					{
						continue;
					}

					$data['photos'][] = $picture['src'];
					$data['photo_width'][] = $picture['width'];
					$data['photo_height'][] = $picture['height'];

					if(!$data['main_photo'])
					{
						$data['main_photo'] = $picture['src'];
					}
				}
			}

			$client = $babyblog->getCurlClient('https://sp.babyblog.ru/org/wizard/ajax_set_product');
			$client->referer = "https://sp.babyblog.ru/org/shop/wizard/{$supplier->babyblog_shop_id}/{$supplier->babyblog_buyout_id}/product".($this->product_id ? '/'.$this->product_id:'');
			$client->data = http_build_query($data);

			$result = json_decode($client->exec(), true);

			if(!$this->product_id)
			{
				if(!is_int($result))
				{
					throw new BabyblogProductImportException("add error: \n".print_r($result, 1));
				}
				else $this->product_id = $result;
			}
			else
			{
				if(empty($result['product_id']))
				{
					throw new BabyblogProductImportException("update error: \n".print_r($result ? $result:'Product not found', 1));
				}
			}

			$this->save(false);
		}
		catch(BabyblogProductImportException $e)
		{
			if($this->id)
			{
				Yii::$app->db->createCommand()->update(static::tableName(), [
					'import_error' => $e->getMessage()
				], ['id' => $this->id])->execute();
			}
			throw $e;
		}
	}

	public function importActivate()
	{
		if($this->isNewRecord || $this->dirtyAttributes)
		{
			throw new BabyblogProductImportException('Require save product before activate');
		}

		$babyblog = Babyblog::getInstance();
		$supplier = $this->supplier;

		$client = $babyblog->getCurlClient('https://sp.babyblog.ru/org/wizard/ajax_set_products');
		$client->referer = "https://sp.babyblog.ru/org/shop/wizard/{$supplier->babyblog_shop_id}/{$supplier->babyblog_buyout_id}/products";
		$client->data = [
			'shop_id' => $supplier->babyblog_shop_id,
			'action'  => 1,
			'ids'     => $this->product_id,
		];

		$client->exec();
		if($client->getResultHttpCode() == 200)
		{
			$this->import_error = NULL;
		}
		else
		{
			$this->import_error = 'ACTIVATE ERROR';
		}

		$this->active = 1;
		$this->save(false);
	}

	public function importDeactivate()
	{
		if($this->isNewRecord || $this->dirtyAttributes)
		{
			throw new BabyblogProductImportException('Require save product before deactivate');
		}

		$babyblog = Babyblog::getInstance();
		$supplier = $this->supplier;

		$client = $babyblog->getCurlClient('https://sp.babyblog.ru/org/wizard/ajax_set_products');
		$client->referer = "https://sp.babyblog.ru/org/shop/wizard/{$supplier->babyblog_shop_id}/{$supplier->babyblog_buyout_id}/products";
		$client->data = [
			'shop_id' => $supplier->babyblog_shop_id,
			'action'  => 2,
			'ids'     => $this->product_id,
		];

		$client->exec();
		if($client->getResultHttpCode() == 200)
		{
			$this->import_error = NULL;
		}
		else
		{
			$this->import_error = 'DEACTIVATE ERROR';
		}

		$this->active = 0;
		$this->save(false);
	}

	/**
	 * @throws BabyblogProductImportException
	 */
	protected function importPhotos()
	{
		$babyblog = Babyblog::getInstance();
		$supplier = $this->supplier;

		if(!empty($this->pictures) && is_array($this->pictures))
		{
			// Для того чтобы перезаписать поле $this->pictures, ему надо евно присвоить новое значение, а не просто модифицировать
			$pictures = $this->pictures;

			$uploadPicture = function (&$picture) use ($babyblog, $supplier) {
				if(empty($picture['origSrc']) || !empty($picture['src']))
				{
					return;
				}

				$client = $babyblog->getCurlClient('https://sp.babyblog.ru/org/wizard/urlload');
				$client->referer = "https://sp.babyblog.ru/org/shop/wizard/{$supplier->babyblog_shop_id}/{$supplier->babyblog_buyout_id}/product".($this->product_id ? "/{$this->product_id}":"");
				$client->data = http_build_query([
					'imageurl' => $picture['origSrc'],
				]);

				$result = json_decode($client->exec(), true);
				if(!empty($result['photo_orig']))
				{
					$picture['src'] = $result['photo_orig'];
					$picture['width'] = $result['width'];
					$picture['height'] = $result['height'];
				}
				else throw new BabyblogProductImportException("Error upload photo: ".$picture['origSrc']);
			};

			foreach($pictures as &$picture)
			{
				$uploadPicture($picture);
			}

			$this->pictures = $pictures;
		}
	}
}
