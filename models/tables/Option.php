<?php

namespace app\models\tables;

use app\models\tables\queries\OptionQuery;
use frostealth\yii2\behaviors\ArrayFieldBehavior;
use Yii;

/**
 * This is the model class for table "{{%option}}".
 *
 * @property string $code Код
 * @property string $name Название
 * @property array|null $data Данные
 * @property int|null $sort Сортировка
 */
class Option extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return '{{%option}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['code', 'name'], 'required'],
			[['data'], 'safe'],
			[['code'], 'string', 'max' => 50],
			[['name'], 'string', 'max' => 255],
			[['code'], 'unique'],
		];
	}

	public function behaviors()
	{
		return [
			[
				'class'               => ArrayFieldBehavior::class,
				'attributes'          => ['data'],
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
			'code' => 'Код',
			'name' => 'Название',
			'data' => 'Данные',
			'sort' => 'Сортировка',
		];
	}

	/**
	 * {@inheritdoc}
	 * @return \app\models\tables\queries\OptionQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new OptionQuery(get_called_class());
	}

	/**
	 * @param string $code
	 * @return static|null
	 */
	public static function get($code)
	{
		return static::find()->code($code)->one();
	}

	/**
	 * @return array
	 */
	public static function getBabyblog()
	{
		return static::get('babyblog')->data;
	}
}
