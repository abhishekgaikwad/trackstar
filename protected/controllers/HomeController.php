<?php

class HomeController extends Controller
{
	public $layout='home';
	
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	
	
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','AddUser','AddTransaction','AddMerchant','AddAffiliate','logout'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionAddUser()
	{
		$model = new User;
		//echo Yii::app()->session['user_id']  ;
			
		if(isset($_POST["User"]))
		{	
			
			$model->fname = $_POST['User']['fname'] ;
			$model->lname = $_POST['User']['lname'] ;
			$model->username = $_POST['User']['username'] ;
			$model->password = $_POST['User']['password'] ;
			$model->added_on = new CDbExpression('NOW()') ;
			if($model->save()) {
				$this->render('addUser',array(
				'model'=>new User,
				'message' => 'User Saved',
			));
			} else {
				
				$this->render('addUser',array(
				'model'=> $model,
				));
				
			}
		}
		
		if(!isset($_POST["User"]))
		{
			$this->render('addUser',array(
				'model'=>$model,
			));
		}
		
		
	}
	
	public function actionAddTransaction() {
		$model = new Commission ;
		
		$merchant = Merchant::model()->findAll();
		$affiliate = Affiliate::model()->findAll();
		$present=Commission::model()->with('merchant','affiliate')->findAll();
		
		//var_dump($present);
		
		if(isset($_POST["Commission"]))
		{	
			
			$model->merchant_id = $_POST['Commission']['merchant_id'] ;
			$model->affiliate_id = $_POST['Commission']['affiliate_id'] ;
			$model->date_of_report = date("Y-m-d", strtotime($_POST['Commission']['date_of_report']));
			$model->commission = $_POST['Commission']['commission'] ;
			$model->no_of_clicks = $_POST['Commission']['no_of_clicks'] ;
			$model->no_of_sales = $_POST['Commission']['no_of_sales'] ;
			$model->user_id = Yii::app()->session['user_id'] ;
			$model->added_on = new CDbExpression('NOW()') ;
			if($model->save()) {
				$present=Commission::model()->with('merchant','affiliate')->findAll();
				$this->render('addTransaction',array(
				'model'=>new Commission,
				'merchant'=>$merchant,
				'affiliate'=>$affiliate,
				'message' => 'Transaction Saved',
				'present'=>$present,
			));
			} else {
				
				$this->render('addTransaction',array(
				'model'=> $model,
				'merchant'=>$merchant,
				'affiliate'=>$affiliate,
				'present'=>$present,
				));
				
			}
		}
		
		if(!isset($_POST["Commission"]))
		{
			$this->render('addTransaction', array(
			'model'=>$model,
			'merchant'=>$merchant,
			'affiliate'=>$affiliate,
			'present'=>$present,
			));
		}
		
		
		
	}
	
	public function actionAddMerchant()
	{
		$model = new Merchant();
				
		
		//$user = Yii::app()->db->createCommand()->select('a.id, affiliate_name, a.added_on, username')->from('affiliate a')->join('user u', 'a.user_id=u.id')->queryRow();

		$presentMerchant=Merchant::model()->with('user')->findAll();
		if(isset($_POST["Merchant"]))
		{	
			
			$model->merchant_name = $_POST['Merchant']['merchant_name'] ;
			$model->user_id = Yii::app()->session['user_id'] ;
			$model->added_on = new CDbExpression('NOW()') ;
			if($model->save()) {
				$presentMerchant=Merchant::model()->with('user')->findAll();
				$this->render('addMerchant',array(
				'model'=>new Merchant,
				'message' => 'Merchant Saved',
				'presentMerchant'=>$presentMerchant,
			));
			} else {
				
				$this->render('addMerchant',array(
				'model'=> $model,
				'presentMerchant'=>$presentMerchant,
				));
				
			}
		}
		
		if(!isset($_POST["Merchant"]))
		{
			$this->render('addMerchant',array(
			'model'=>$model,
			'presentMerchant'=>$presentMerchant,
			));
		}
	}
	
	public function actionAddAffiliate()
	{
		$model = new Affiliate();
		
		
		//$user = Yii::app()->db->createCommand()->select('a.id, affiliate_name, a.added_on, username')->from('affiliate a')->join('user u', 'a.user_id=u.id')->queryRow();

		$presentAffiliates=Affiliate::model()->with('user')->findAll();
		if(isset($_POST["Affiliate"]))
		{	
			
			$model->affiliate_name = $_POST['Affiliate']['affiliate_name'] ;
			$model->user_id = Yii::app()->session['user_id'] ;
			$model->added_on = new CDbExpression('NOW()') ;
			if($model->save()) {
				$presentAffiliates=Affiliate::model()->with('user')->findAll();
				$this->render('addAffiliate',array(
				'model'=>new Affiliate,
				'message' => 'Affiliate Saved',
				'presentAffiliates'=>$presentAffiliates,
			));
			} else {
				
				$this->render('addAffiliate',array(
				'model'=> $model,
				'presentAffiliates'=>$presentAffiliates,
				));
				
			}
		}
		
		if(!isset($_POST["Affiliate"]))
		{
			$this->render('addAffiliate',array(
			'model'=>$model,
			'presentAffiliates'=>$presentAffiliates,
			));
		}
	}
	
	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}