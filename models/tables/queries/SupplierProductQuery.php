<?php

namespace app\models\tables\queries;

/**
 * This is the ActiveQuery class for [[\app\models\tables\SupplierProduct]].
 *
 * @see \app\models\tables\SupplierProduct
 */
class SupplierProductQuery extends \yii\db\ActiveQuery
{
	public function primary($supplier_id, $product_id)
	{
		return $this->andWhere([
			'supplier_id' => $supplier_id,
			'product_id'  => $product_id,
		]);
	}

    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\tables\SupplierProduct[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\tables\SupplierProduct|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
