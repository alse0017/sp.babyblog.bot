<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-slider-index">

	<h1><?=Html::encode($this->title)?></h1>

	<?=GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			[
				'attribute' => 'name',
				'format'    => 'html',
				'value'     => function ($model) {
					return Html::a($model->name, ['update', 'code' => $model->code]);
				},
			],
			'code',
			'sort'
		],
	]);?>
</div>
