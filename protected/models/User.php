<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $fname
 * @property string $lname
 * @property string $username
 * @property string $password
 * @property string $added_on
 *
 * The followings are the available model relations:
 * @property Affiliate[] $affiliates
 * @property Commission[] $commissions
 * @property Merchant[] $merchants
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}
	public function abc()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fname, lname, username, password', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('fname, lname, username, password', 'length', 'max'=>45),
			array('added_on', 'safe'),
			array('username', 'unique', 'message' => 'This email already exists.'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fname, lname, username, password, added_on', 'safe', 'on'=>'search'),
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
			'affiliates' => array(self::HAS_MANY, 'Affiliate', 'user_id'),
			'commissions' => array(self::HAS_MANY, 'Commission', 'user_id'),
			'merchants' => array(self::HAS_MANY, 'Merchant', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fname' => 'First Name',
			'lname' => 'Last Name',
			'username' => 'Email',
			'password' => 'Password',
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
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('lname',$this->lname,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('added_on',$this->added_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
