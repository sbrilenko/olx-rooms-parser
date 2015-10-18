<?php

/**
 * This is the model class for table "ads".
 *
 * The followings are the available columns in table 'ads':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $link
 * @property string $author
 * @property string $phone
 * @property string $price
 * @property string $read_status
 * @property integer $time_create
 */
class Ads extends CActiveRecord
{
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ads';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time_create,read_status', 'numerical', 'integerOnly'=>true),
			array('title, link, phone, price', 'length', 'max'=>512),
            array('author', 'length', 'max'=>256),
            array('id, description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
//			array('id, username, password, email, company_id, activationKey, createtime, lastvisit, lastaction, lastpasswordchange, failedloginattempts, superuser, status, day_count, points, was_flag, work_count, job_type, job_title, level, start_month', 'safe', 'on'=>'search'),
		);
	}


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'title',
			'description' => 'description',
            'link'=>'link',
            'author'=>'author',
            'phone'=>'phone',
			'price'=>'price',
            'read_status'=>'read_status',
			'time_create' => 'time_create'
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
        $criteria->compare('link',$this->link,true);
        $criteria->compare('author',$this->author,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('time_create',$this->time_create);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ads the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
