<?php

namespace app\models\tables\queries;

/**
 * This is the ActiveQuery class for [[\app\models\tables\Supplier]].
 *
 * @see \app\models\tables\Supplier
 */
class SupplierQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\tables\Supplier[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\tables\Supplier|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
