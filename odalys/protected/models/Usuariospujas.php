<?php

/**
 * This is the model class for table "usuariospujas".
 *
 * The followings are the available columns in table 'usuariospujas':
 * @property integer $id
 * @property integer $idusuario
 * @property integer $paleta
 * @property string $codigo
 * @property string $cedula
 * @property integer $idsubasta
 */
class Usuariospujas extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Usuariospujas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'usuariospujas';
	}

	public function behaviors()
	{
	    return array(
	        'ActiveRecordLogableBehavior'=>
	            'application.behaviors.ActiveRecordLogableBehavior',
	    );
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idusuario, paleta, codigo, cedula, idsubasta', 'required'),
			array('idusuario, paleta, idsubasta', 'numerical', 'integerOnly'=>true),
			array('codigo, cedula', 'length', 'max'=>255),
			array('paleta', 'match', 'pattern' => '/^[0-9]+$/', 'allowEmpty' => true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idusuario, paleta, codigo, cedula, idsubasta', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idusuario' => 'Idusuario',
			'paleta' => 'Paleta',
			'codigo' => 'Codigo',
			'cedula' => 'Cedula',
			'idsubasta' => 'Idsubasta',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('idusuario',$this->idusuario);
		$criteria->compare('paleta',$this->paleta);
		$criteria->compare('codigo',$this->codigo,true);
		$criteria->compare('cedula',$this->cedula,true);
		$criteria->compare('idsubasta',$this->idsubasta);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}