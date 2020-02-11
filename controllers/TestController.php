<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\ViewAction;

class TestController extends Controller
{
	public $layout = false;

	public function beforeAction($action)
	{
		if(!parent::beforeAction($action))
		{
			return false;
		}
		if(!YII_ENV_DEV)
		{
			return false;
		}
		return true;
	}

	public function actions()
	{
		return [
			'index' => ViewAction::class
		];
	}
}
