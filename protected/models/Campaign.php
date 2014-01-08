<?php

/**
 * This is the model class for table "campaign".
 *
 * The followings are the available columns in table 'campaign':
 * @property integer $id
 * @property integer $merchant_id
 * @property integer $affiliate_id
 * @property string $campaign_date
 * @property integer $estimated_value
 * @property integer $final_value
 * @property integer $added_by
 * @property integer $finalized_by
 * @property string $added_on
 * @property string $finalized_on
 * @property integer $is_billed
 *
 * The followings are the available model relations:
 * @property Affiliate $affiliate
 * @property Merchant $merchant
 * @property User $addedBy
 * @property User $finalizedBy
 * @property InvoiceComponent[] $invoiceComponents
 */
class Campaign extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'campaign';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('merchant_id, affiliate_id, campaign_date, added_by, added_on, is_billed', 'required'),
            array('merchant_id, affiliate_id, estimated_value, final_value, added_by, finalized_by, is_billed', 'numerical', 'integerOnly'=>true),
            array('finalized_on', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, merchant_id, affiliate_id, campaign_date, estimated_value, final_value, added_by, finalized_by, added_on, finalized_on, is_billed', 'safe', 'on'=>'search'),
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
            'merchant' => array(self::BELONGS_TO, 'Merchant', 'merchant_id'),
            'addedBy' => array(self::BELONGS_TO, 'User', 'added_by'),
            'finalizedBy' => array(self::BELONGS_TO, 'User', 'finalized_by'),
            'invoiceComponents' => array(self::HAS_MANY, 'InvoiceComponent', 'campaign_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'merchant_id' => 'Merchant',
            'affiliate_id' => 'Affiliate',
            'campaign_date' => 'Campaign Date',
            'estimated_value' => 'Estimated Value',
            'final_value' => 'Final Value',
            'added_by' => 'Added By',
            'finalized_by' => 'Finalized By',
            'added_on' => 'Added On',
            'finalized_on' => 'Finalized On',
            'is_billed' => 'Is Billed',
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
        $criteria->compare('merchant_id',$this->merchant_id);
        $criteria->compare('affiliate_id',$this->affiliate_id);
        $criteria->compare('campaign_date',$this->campaign_date,true);
        $criteria->compare('estimated_value',$this->estimated_value);
        $criteria->compare('final_value',$this->final_value);
        $criteria->compare('added_by',$this->added_by);
        $criteria->compare('finalized_by',$this->finalized_by);
        $criteria->compare('added_on',$this->added_on,true);
        $criteria->compare('finalized_on',$this->finalized_on,true);
        $criteria->compare('is_billed',$this->is_billed);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Campaign the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}