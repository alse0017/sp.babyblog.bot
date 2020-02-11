<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\tables\Option */
/* @var $form yii\widgets\ActiveForm */
?>
<div>

	<?php $form = ActiveForm::begin(); ?>

	<?=$this->render('data-forms/'.$model->code, compact('model', 'form'))?>

	<div class="form-group">
		<?=Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
	</div>
	<?php ActiveForm::end(); ?>
</div>
