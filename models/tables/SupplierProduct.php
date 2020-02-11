<?php

namespace app\models\tables;

use frostealth\yii2\behaviors\ArrayFieldBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%supplier_product}}".
 *
 * @property int $supplier_id
 * @property int $product_id
 * @property string $url
 * @property int|null $babyblog_category_id
 * @property string|null $name
 * @property string|null $article
 * @property string|null $color
 * @property string|null $description
 * @property float|null $price
 * @property array|null $pictures
 * @property int|null $created_at
 */
class SupplierProduct extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return '{{%supplier_product}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			['pictures', 'safe'],
			[['supplier_id', 'product_id', 'url'], 'required'],
			[['supplier_id', 'product_id', 'babyblog_category_id'], 'integer'],
			[['description'], 'string'],
			[['price'], 'number'],
			[['url'], 'string', 'max' => 500],
			[['name'], 'string', 'max' => 255],
			[['article', 'color'], 'string', 'max' => 50],
			[['product_id', 'supplier_id'], 'unique', 'targetAttribute' => ['product_id', 'supplier_id']],
		];
	}

	public function behaviors()
	{
		return [
			[
				'class'              => TimestampBehavior::class,
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => NULL,
			],
			[
				'class'               => ArrayFieldBehavior::class,
				'attributes'          => ['pictures'],
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
			'supplier_id'          => 'Supplier ID',
			'product_id'           => 'Product ID',
			'url'                  => 'Url',
			'babyblog_category_id' => 'Babyblog Category ID',
			'name'                 => 'Name',
			'article'              => 'Article',
			'color'                => 'Color',
			'description'          => 'Description',
			'price'                => 'Price',
			'pictures'             => 'Pictures',
			'created_at'           => 'Created At',
		];
	}

	/**
	 * {@inheritdoc}
	 * @return \app\models\tables\queries\SupplierProductQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\models\tables\queries\SupplierProductQuery(get_called_class());
	}

	/**
	 * {@inheritdoc}
	 * @param int $supplier_id
	 * @param string $product_id
	 * @return static|null
	 */
	public static function findByPrimary($supplier_id, $product_id)
	{
		return static::find()->primary($supplier_id, $product_id)->one();
	}

	public function createBabyblogProduct()
	{
		$product = BabyblogProduct::findOne([
			'supplier_id'         => $this->supplier_id,
			'supplier_product_id' => $this->product_id,
		]);

		if(!$product)
		{
			$product = new BabyblogProduct();
			$product->supplier_id = $this->supplier_id;
			$product->supplier_product_id = $this->product_id;
		}

		$product->name = $this->name;
		$product->article = $this->article;
		$product->price = $this->price;
		$product->category_id = $this->babyblog_category_id;
		$product->description = $this->description;

		if(!$product->product_id)
		{
			$product->active = 1;
		}

		$color = $this->getBabyblogColor();
		if($color)
		{
			$product->props = [
				1 => [
					$color,
				],
			];
		}

		$productPictures = (array)$product->pictures;
		if($this->pictures && is_array($this->pictures))
		{
			foreach($this->pictures as $src)
			{
				$existPicture = !empty(array_filter($productPictures, function ($picture) use ($src) {
					return $picture['origSrc'] == $src;
				}));

				if($existPicture)
				{
					continue;
				}

				$productPictures[] = [
					'origSrc' => $src,
					'src'     => NULL,
					'height'  => NULL,
					'width'   => NULL,
				];
			}
		}

		$product->pictures = $productPictures;

		return $product;
	}

	protected function getBabyblogColor()
	{
		return [
			'бежевый'          => 'efdfb9',
			'белый'            => 'ffffff',
			'бордовый'         => 'ab2a2a',
			'бронза'           => 'good-gold-color.png',
			'винный'           => '872929',
			'желтый'           => 'efb128',
			'зелёный'          => '00923f',
			'золото'           => 'good-gold-color.png',
			'коричневый'       => '6e4d2c',
			'красный'          => 'da251c',
			'кремовый'         => 'efdfb9',
			'мультиколор'      => 'good-multi-color.png',
			'оранжевый'        => 'e36d27',
			'розовый'          => 'df787b',
			'светло бежевый'   => 'efdfb9',
			'серебро'          => 'good-silver-color.png',
			'серый'            => '999999',
			'синий'            => '393681',
			'тёмно бежевый'    => 'efdfb9',
			'тёмно коричневый' => '6e4d2c',
			'фиолетовый'       => '974478',
			'хаки'             => '745e2f',
			'черный'           => '1a1a1a',
		][strtolower($this->color)];
	}
}
