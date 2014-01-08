<?php

/**
 * This is the model class for table "invoice".
 *
 * The followings are the available columns in table 'invoice':
 * @property integer $id
 * @property string $invoice_number
 * @property integer $affiliate_id
 * @property string $date_of_invoice
 * @property integer $service_value
 * @property integer $service_tax
 * @property integer $total_value
 * @property integer $added_by
 * @property string $added_on
 *
 * The followings are the available model relations:
 * @property Affiliate $affiliate
 * @property User $addedBy
 * @property InvoiceComponent[] $invoiceComponents
 */
class Invoice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invoice_number, affiliate_id, date_of_invoice, added_by, added_on', 'required'),
			array('id, affiliate_id, service_value, service_tax, total_value, added_by', 'numerical', 'integerOnly'=>true),
			array('invoice_number', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, invoice_number, affiliate_id, date_of_invoice, service_value, service_tax, total_value, added_by, added_on', 'safe', 'on'=>'search'),
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
			'affiliate' => array(self::BELONGS_TO, 'Affiliate', 'affiliate_id'),
			'addedBy' => array(self::BELONGS_TO, 'User', 'added_by'),
			'invoiceComponents' => array(self::HAS_MANY, 'InvoiceComponent', 'invoice_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'invoice_number' => 'Invoice Number',
			'affiliate_id' => 'Affiliate',
			'date_of_invoice' => 'Date Of Invoice',
			'service_value' => 'Service Value',
			'service_tax' => 'Service Tax',
			'total_value' => 'Total Value',
			'added_by' => 'Added By',
			'added_on' => 'Added On',
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
		$criteria->compare('invoice_number',$this->invoice_number,true);
		$criteria->compare('affiliate_id',$this->affiliate_id);
		$criteria->compare('date_of_invoice',$this->date_of_invoice,true);
		$criteria->compare('service_value',$this->service_value);
		$criteria->compare('service_tax',$this->service_tax);
		$criteria->compare('total_value',$this->total_value);
		$criteria->compare('added_by',$this->added_by);
		$criteria->compare('added_on',$this->added_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Invoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
