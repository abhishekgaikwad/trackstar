<?php

/**
 * This is the model class for table "invoice_component".
 *
 * The followings are the available columns in table 'invoice_component':
 * @property integer $id
 * @property integer $invoice_id
 * @property integer $campaign_id
 * @property string $description
 * @property integer $value
 * @property integer $tax
 * @property string $date_of_component
 *
 * The followings are the available model relations:
 * @property Campaign $campaign
 * @property Invoice $invoice
 */
class InvoiceComponent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoice_component';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invoice_id, value, tax', 'required'),
			array('id, invoice_id, campaign_id, value, tax', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>45),
			array('date_of_component', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, invoice_id, campaign_id, description, value, tax, date_of_component', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'campaign' => array(self::BELONGS_TO, 'Campaign', 'campaign_id'),
			'invoice' => array(self::BELONGS_TO, 'Invoice', 'invoice_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'invoice_id' => 'Invoice',
			'campaign_id' => 'Campaign',
			'description' => 'Description',
			'value' => 'Value',
			'tax' => 'Tax',
			'date_of_component' => 'Date Of Component',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('invoice_id',$this->invoice_id);
		$criteria->compare('campaign_id',$this->campaign_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('value',$this->value);
		$criteria->compare('tax',$this->tax);
		$criteria->compare('date_of_component',$this->date_of_component,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InvoiceComponent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
