<?

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\tables\Option */
/* @var $form yii\widgets\ActiveForm */

?>
<div style="max-width: 350px;">
	<?
	echo $form->field($model, 'data[login]')->textInput()->label('Логин');
	echo $form->field($model, 'data[password]')->textInput()->label('Пароль');
	?>
</div>
<?
