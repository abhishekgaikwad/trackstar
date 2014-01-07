<?php

class CampaignController extends Controller
{
        public $layout='home';
	public function actionIndex()
	{
		$this->render('index');
	}
        
        public function actionAddCampaign() {
                $model = new Campaign ;
                
                $merchant = Merchant::model()->findAll();
                $affiliate = Affiliate::model()->findAll();
                //$present=Campaign::model()->with('merchant','affiliate')->findAll();
                
                $criteria = new CDbCriteria ;
                //$criteria->condition = "affiliate_id = $value AND campaign_date >= '$dateStart' AND campaign_date <= '$dateEnd'  ";
               
                $criteria->with = array('merchant','affiliate',);
                 $criteria->order = 't.added_on DESC';
                $criteria->limit = 10;
                $present=Campaign::model()->with('merchant','affiliate')->findAll($criteria);
                
                //var_dump($present);
                if(isset($_POST["Campaign"]))
                {        
                        $model->merchant_id = $_POST['Campaign']['merchant_id'] ;
                        $model->affiliate_id = $_POST['Campaign']['affiliate_id'] ;
                        $model->campaign_date = date("Y-m-d", strtotime($_POST['Campaign']['campaign_date']));
                        //$model->commission = $_POST['Campaign']['commission'] ;
                        $model->estimated_value = $_POST['Campaign']['estimated_value'] ;
                        $model->added_by = Yii::app()->session['user_id'] ;
                        $model->added_on = new CDbExpression('NOW()') ;
                        
                        if($model->save()) {
                                $present=Campaign::model()->with('merchant','affiliate')->findAll($criteria);
                                $this->render('addCampaign',array(
                                'model'=>new Campaign,
                                'merchant'=>$merchant,
                                'affiliate'=>$affiliate,
                                'message' => 'Campaign Saved',
                                'present'=>$present,
                        ));
                        } else {
                                
                                $this->render('addCampaign',array(
                                'model'=> $model,
                                'merchant'=>$merchant,
                                'affiliate'=>$affiliate,
                                'present'=>$present,

                                ));
                                
                        }
                }
                
                if(!isset($_POST["Campaign"]))
                {
                        $this->render('addCampaign', array(
                        'model'=>$model,
                        'merchant'=>$merchant,
                        'affiliate'=>$affiliate,
                        'present'=>$present,
                        ));
                }
                
                
                
        }
        
        public function actionUpdateCampaign() {
            
            $criteria = new CDbCriteria ;
            $criteria->condition = 'final_value IS NULL';
            $criteria->with = array('merchant','affiliate','addedBy');
             
            $openCampaign = new CActiveDataProvider('Campaign', array(
				 'criteria'=>  $criteria,
				 'pagination' => false,));
                          // var_dump($openCampaign->getData());
            $merchant = Merchant::model()->findAll();
            $affiliate = Affiliate::model()->findAll();
            $this->render('updateCampaign', array(
                'openCampaign'=>$openCampaign->getData() ,
                'merchant'=>$merchant,
		'affiliate'=>$affiliate,
            ));
        }
        
        
        public function actionAjaxGetUpdateCampaign() {
            if(Yii::app()->request->getRequestType() == 'POST') {
                $criteria = new CDbCriteria ;
                $criteria->addCondition('final_value IS NULL');
                $criteria->with = array('merchant','affiliate','addedBy','finalizedBy');
                
                if( Yii::app()->request->getPost("month")=='true') {
                    $period = Yii::app()->request->getPost("monthValue") ;
                    $date = date("Y-m-d", strtotime($period));
                    $criteria->addCondition("campaign_date = '$date'");
                }
                
                if( Yii::app()->request->getPost("merchant")=="true") {
                    $merchant_id = Yii::app()->request->getPost("merchantValue");
                    $criteria->addCondition("merchant_id = $merchant_id");
                }
                
                if( Yii::app()->request->getPost("affiliate")=="true") {
                    $affiliate_id = Yii::app()->request->getPost("affiliateValue");
                    $criteria->addCondition("affiliate_id = $affiliate_id");
                }
                    
                
                $closedCampaign = new CActiveDataProvider('Campaign', array(
                    'criteria'=>  $criteria,
                    'pagination' => false,));
                
                $this->renderPartial('ajaxGetUpdateCampaign',array(
                    'openCampaign' => $closedCampaign->getData(),
                ));
            }
        }
        
        
        public function actionAjaxUpdate() {
            if(Yii::app()->request->getRequestType() == 'POST') {
                if(Yii::app()->request->getPost('estimate_value')) {
                    $campaign_id = Yii::app()->request->getPost('campaign_id') ;
                    $model = Campaign::model()->with('merchant','affiliate','addedBy','finalizedBy')->findByPk($campaign_id) ;
                    $model->estimated_value = Yii::app()->request->getPost('estimate_value') ;
                    if($model->update()){
                        echo $model->estimated_value ;
                    }
                    else {
                        echo "error" ;
                    }
                }
                else if(Yii::app()->request->getPost('final_value')) {
                    $campaign_id = Yii::app()->request->getPost('campaign_id') ;
                    $model = Campaign::model()->with('merchant','affiliate','addedBy','finalizedBy')->findByPk($campaign_id) ;
                    $model->finalized_by = Yii::app()->session['user_id'] ;
                    $model->finalized_on = new CDbExpression('NOW()') ;
                    $model->final_value = Yii::app()->request->getPost('final_value') ;
                    if($model->update()) {
                        $model = Campaign::model()->with('merchant','affiliate','addedBy','finalizedBy')->findByPk($campaign_id) ;
                        $this->renderPartial('ajaxUpdateCampaign',array(
                            'model' => $model,
                            'error' => 0 ,
                        ));
                    } else {
                        $this->renderPartial('ajaxUpdateCampaign',array(
                            'error' => 1 ,
                        ));
                    }
                    
                }
            }
        }
        
        public function actionClosedCampaign() {
            $criteria = new CDbCriteria ;
            $criteria->condition = 'final_value IS NOT NULL';
            $criteria->with = array('merchant','affiliate','addedBy','finalizedBy');
            $closedCampaign = new CActiveDataProvider('Campaign', array(
				 'criteria'=>  $criteria,
				 'pagination' => false,));
            $merchant = Merchant::model()->findAll();
            $affiliate = Affiliate::model()->findAll();
            $this->render('ClosedCampaign', array(
                'closedCampaign'=>$closedCampaign->getData() ,
                'merchant'=>$merchant,
		'affiliate'=>$affiliate,
            ));
        }
        
        public function actionAjaxGetAffiliate() {
            if(Yii::app()->request->getRequestType() == 'POST') {
                $merchant = Yii::app()->request->getPost("merchant") ;
                $period = Yii::app()->request->getPost("period") ;
                $date = date("Y-m-d", strtotime($period));
                $criteria = new CDbCriteria;
                $criteria->condition = "merchant_id = $merchant AND campaign_date = '$date' ";
                $data = new CActiveDataProvider('Campaign', array(
				 'criteria'=>  $criteria,
				 'pagination' => false,));
                $affiliate = Affiliate::model()->findAll();
                $this->renderPartial('ajaxGetAffiliate',array(
                    'data' => $data->getData(),
                    'affiliate' => $affiliate ,
                ));
            }
        }
        public function actionAjaxGetClosedCampaign() {
            if(Yii::app()->request->getRequestType() == 'POST') {
                $criteria = new CDbCriteria ;
                $criteria->addCondition('final_value IS NOT NULL');
                $criteria->with = array('merchant','affiliate','addedBy','finalizedBy');
                
                if( Yii::app()->request->getPost("month")=='true') {
                    $period = Yii::app()->request->getPost("monthValue") ;
                    $date = date("Y-m-d", strtotime($period));
                    $criteria->addCondition("campaign_date = '$date'");
                }
                
                if( Yii::app()->request->getPost("merchant")=="true") {
                    $merchant_id = Yii::app()->request->getPost("merchantValue");
                    $criteria->addCondition("merchant_id = $merchant_id");
                }
                
                if( Yii::app()->request->getPost("affiliate")=="true") {
                    $affiliate_id = Yii::app()->request->getPost("affiliateValue");
                    $criteria->addCondition("affiliate_id = $affiliate_id");
                }
                    
                
                $closedCampaign = new CActiveDataProvider('Campaign', array(
                    'criteria'=>  $criteria,
                    'pagination' => false,));
                
                $this->renderPartial('ajaxGetClosedCampaign',array(
                    'closedCampaign' => $closedCampaign->getData(),
                ));
            }
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