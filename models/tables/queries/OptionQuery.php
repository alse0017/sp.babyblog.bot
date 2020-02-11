<?php

namespace app\models\tables\queries;

use app\models\tables\Option;

/**
 * This is the ActiveQuery class for [[\app\models\tables\Option]].
 *
 * @see Option
 */
class OptionQuery extends \yii\db\ActiveQuery
{
    public function code($code)
    {
        return $this->andWhere(['code' => $code]);
    }

    /**
     * {@inheritdoc}
     * @return Option[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Option|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
