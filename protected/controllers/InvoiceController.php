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
                $criteria->addCondition('is_billed = 0 OR is_billed = 2');
                $criteria->with = array('merchant','affiliate','invoiceComponents');
                $campaign = new CActiveDataProvider('Campaign', array(
                    'criteria'=>  $criteria,
                    'pagination' => false,));
                $this->renderPartial('ajaxGetCampaign',array(
                    'campaign' => $campaign->getData(),
                ));                
            }
        }
        
        public function actionAjaxSave() {
            //Yii::app()->request->getRequestType() == 'POST'
           // var_dump(json_decode(file_get_contents("php://input"), true));
            if(Yii::app()->request->getRequestType() == 'POST') {
                $data = json_decode(file_get_contents("php://input"), true);
                $invoice = new Invoice ;
                $invoice->invoice_number = $data['invoice_number'];
                $invoice->affiliate_id = $data['affiliate_id'];
                $invoice->date_of_invoice = date("Y-m-d", strtotime($data['date_of_invoice']));
                $invoice->added_by = Yii::app()->session['user_id'] ;
                $invoice->added_on = new CDbExpression('NOW()') ;
                $tempValue = 0 ;
                $tempTax = 0 ;
                
                if($invoice->save()) { //save basic invoice
                    $invoiceID = $invoice->id ;
                   //echo $invoiceID;
                    //var_dump($data['campaign']);
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
                                $invoiceComponents = InvoiceComponent::model()->findAllByAttributes(array('campaign_id'=>$id)); // find invoice componets from the datbase with associated with current campaign id
                                $temp = 0 ; 
                                foreach ($invoiceComponents as $individualComponents) { //sum of all previous invoice components
                                    $temp += $individualComponents->value ;
                                }
                                $temp += $item['value'] ; // add entered value from client to the sum
                                if($campaign->final_value == $temp) { //if total equal campaign value mark as billed and update
                                    $campaign->is_billed = 1 ;
                                    $campaign->update();
                                } 
                                elseif ($campaign->final_value < $temp) {
                                    echo 'Campaign is billed' ;
                                }
                                else {
                                    $campaign->is_billed = 2 ;
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
                            $invoiceComponent->date_of_component = date("Y-m-d", strtotime($item['date_of_component'])) ;
                        }
                        if($invoiceComponent->save()){
                            //echo
                        }
                        else {
                            print_r($invoiceComponent->getErrors()) ;
                            echo "Component Save Failed\n" ;
                        }
                    }
//                    $invoice->service_value = $data["service_value"];
//                    $invoice->service_tax = $data["service_tax"] ;
//                    $invoice->total_value = $data["total_value"] ;
                    $invoice->service_value = $tempValue ;
                    $invoice->service_tax = $tempTax ;
                    $invoice->total_value = $tempValue + $tempTax ;
                    if($invoice->update()) {
                        echo 'saved' ;
                    } else {
                        echo 'Failed To Update Invoice Values\n' ;
                    }
                } else {
                    echo 'Failed to Create Inovoice\n';
                }
             }                
        }
        
        public function actionView() {
                $affiliate = Affiliate::model()->findAll();
            
                $this->render('view',array(
                    'affiliate' => $affiliate,
                ));  
        }
        
        public function actionAjaxGetInvoiceList() {
            if(Yii::app()->request->getRequestType() == 'POST') {
                $criteria = new CDbCriteria ;
                $criteria->with = array('affiliate');
                
                if( Yii::app()->request->getPost("start")=='true') {
                    $period = Yii::app()->request->getPost("startValue") ;
                    $date = date("Y-m-d", strtotime($period));
                    $criteria->addCondition("date_of_invoice >= '$date'");
                }
                
                if( Yii::app()->request->getPost("end")=="true") {
                    $period = Yii::app()->request->getPost("endValue") ;
                    $date = date("Y-m-d", strtotime($period));
                    $criteria->addCondition("date_of_invoice <= '$date'");
                }
                
                if( Yii::app()->request->getPost("affiliate")=="true") {
                    $affiliate_id = Yii::app()->request->getPost("affiliateValue");
                    $criteria->addCondition("affiliate_id = $affiliate_id");
                }
                    
                
                $invoiceList = new CActiveDataProvider('Invoice', array(
                    'criteria'=>  $criteria,
                    'pagination' => false,));
                
                //var_dump($invoiceList->getData());
                $this->renderPartial('ajaxGetInvoiceList',array(
                    'invoiceList' => $invoiceList->getData(),
                ));
            }
        }
        
        public function actionAjaxGetInvoice() {
            if(Yii::app()->request->getRequestType() == 'POST') {
                $id = Yii::app()->request->getPost("invoice_id") ;
//                $criteria = new CDbCriteria ;                
//                $criteria->with = array('affiliate');
//                $criteria->addCondition("id = $id");
//                $invoice = new CActiveDataProvider('Invoice', array(
//                    'criteria'=>  $criteria,
//                    'pagination' => false,));
                $criteria2 = new CDbCriteria ;                
                $criteria2->with = array(
                    'campaign'=>array(
                        'with' => 'merchant',
                    ));
                $criteria2->addCondition("invoice_id = $id");
                $component = new CActiveDataProvider('InvoiceComponent', array(
                  'criteria'=>  $criteria2,
                  'pagination' => false,));
                $invoice = Invoice::model()->findByPk($id) ;
                $this->renderPartial('ajaxGetInvoice',array(
                    'components' => $component->getData(),
                    'invoice' => $invoice,
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