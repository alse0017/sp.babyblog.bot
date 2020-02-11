<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\tables\Option */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="home-slider-update">
	<h1><?=Html::encode($this->title)?></h1>
	<?=$this->render('_form', [
		'model' => $model,
	])?>
</div>
