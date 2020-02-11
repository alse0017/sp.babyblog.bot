<?php

namespace app\controllers;

use app\models\tables\Option;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\web\NotFoundHttpException;

class OptionsController extends SiteController
{
	protected function verbs()
	{
		return [
			'delete' => ['POST'],
		];
	}

	public function actionIndex()
	{
		$dataProvider = new ActiveDataProvider([
			'query' => Option::find(),
			'sort' => [
				'class' => Sort::class,
				'defaultOrder' => [
					'sort' => SORT_ASC
				]
			]
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionUpdate($code)
	{
		$model = $this->findModel($code);

		if($model->load(Yii::$app->request->post()) && $model->save())
		{
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * @param integer $code
	 * @return Option the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($code)
	{
		if(($model = Option::get($code)) !== NULL)
		{
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
