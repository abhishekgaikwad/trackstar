<?php

class InvoiceController extends Controller
{
        public $layout='home';
	public function actionIndex()
	{
		$this->render('index');
	}
        
        public function actionAdd() {
            $affiliate = Affiliate::model()->findAll();
            $this->render('add', array(
                'affiliate' => $affiliate,
            ));
            
        }
        
        public function actionAjaxGetCampaign() {
            //Yii::app()->request->getRequestType() == 'POST'
            if(Yii::app()->request->getRequestType() == 'POST') {
                $affiliate_id = Yii::app()->request->getPost("affiliateValue") ;
                $criteria = new CDbCriteria ;                
                $criteria->addCondition("affiliate_id = $affiliate_id");
                $criteria->addCondition("final_value IS NOT NULL");
                $criteria->addCondition('is_billed = 0');
                $criteria->with = array('merchant','affiliate','invoiceComponents');
                $campaign = new CActiveDataProvider('Campaign', array(
                    'criteria'=>  $criteria,
                    'pagination' => false,));
                $this->renderPartial('ajaxGetCampaign',array(
                    'campaign' => $campaign->getData(),
                ));                
            }
        }
        
        public function actionSave() {
            if(Yii::app()->request->getRequestType() == 'POST') {
                $data = json_decode(file_get_contents("php://input"));
                $model = new Invoice ;
                $model->invoice_number = $data['invoice_number'];
                $model->affiliate_id = $data['affiliate_id'];
                $model->date_of_invoice = $data['date_of_invoice'];
                $model->added_by = Yii::app()->session['user_id'] ;
                $model->added_on = new CDbExpression('NOW()') ;
                $tempValue = 0 ;
                $tempTax = 0 ;
                if($model->save()){ //save basic invoice
                    $invoiceID = $model->id ;
                    foreach ($data['campaign'] as $item) { //iterate over every component sent from client
                        $invoiceComponent = new InvoiceComponent ;
                        $invoiceComponent->invoice_id = $invoiceID ;
                        if(isset($item['campaign_id'])){ //check whether it is not a manual entry
                            $id = $item['campaign_id'] ;
                            $invoiceComponent->campaign_id = $item['campaign_id'] ;
                            $campaign = Campaign::model()->findByPk($item['campaign_id']);//get the current campaign from database
                            if($campaign->final_value == $item['value']) { //if actual value and entered value marked it as billed
                                $campaign->is_billed = 1 ;
                                $campaign->update();
                            } else {
                                $invoiceComponents = InvoiceComponent::model()->findAllByAttributes("campaign_id = $id"); // find invoice componets from the datbase with associated with current campaign id
                                $temp = 0 ; 
                                foreach ($invoiceComponents as $individualComponents) { //sum of all previous invoice components
                                    $temp += $individualComponents->value ;
                                }
                                $temp += $item['value'] ; // add entered value from client to the sum
                                if($campaign->final_value <= $temp) { //if total equal or greater than campaign value mark as billed and update
                                    $campaign->is_billed = 1 ;
                                    $campaign->update();
                                }
                            }
                        }                        
                        if(isset($item['description'])){
                            $invoiceComponent->description = $item['description'] ;
                        }
                        $invoiceComponent->value = $item['value'] ;
                        $invoiceComponent->tax = $item['tax'] ;
                        $tempValue += $item['value'] ;
                        $tempTax += $item['value'] * (0.01 * $item['tax']) ;
                        if(isset($item['date_of_component'])){
                            $invoiceComponent->date_of_component = $item['date_of_component'] ;
                        }
                        $invoiceComponent->save();
                    }
                    $model->service_value = $tempValue ;
                    $model->service_tax = $tempTax ;
                    $model->total_value = $tempValue + $tempTax ;
                    if($model->update()) {
                        echo 'saved' ;
                    } else {
                        echo 'failed' ;
                    }
                } else {
                    echo 'failed main';
                }
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