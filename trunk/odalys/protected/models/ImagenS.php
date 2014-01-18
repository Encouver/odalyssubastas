<?php

/**
 * This is the model class for table "imagen_s".
 *
 * The followings are the available columns in table 'imagen_s':
 * @property integer $id
 * @property integer $ids
 * @property string $imagen
 * @property string $imageng
 * @property string $descri
 * @property string $solonombre
 * @property string $monto
 * @property string $base
 * @property string $actual
 * @property string $id_usuario
 */
class ImagenS extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ImagenS the static model class
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
		return 'imagen_s';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, ids, imagen, imageng, descri, monto, base, actual, id_usuario', 'required'),
			array('id, ids', 'numerical', 'integerOnly'=>true),
			array('imagen, imageng, descri, solonombre, monto, base, actual, id_usuario', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ids, imagen, imageng, descri, solonombre, monto, base, actual, id_usuario', 'safe', 'on'=>'search'),
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
			'ids' => 'Ids',
			'imagen' => 'Imagen',
			'imageng' => 'Imageng',
			'descri' => 'Descri',
			'solonombre' => 'Solonombre',
			'monto' => 'Monto',
			'base' => 'Base',
			'actual' => 'Actual',
			'id_usuario' => 'Id Usuario',
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
		$criteria->compare('ids',$this->ids);
		$criteria->compare('imagen',$this->imagen,true);
		$criteria->compare('imageng',$this->imageng,true);
		$criteria->compare('descri',$this->descri,true);
		$criteria->compare('solonombre',$this->solonombre,true);
		$criteria->compare('monto',$this->monto,true);
		$criteria->compare('base',$this->base,true);
		$criteria->compare('actual',$this->actual,true);
		$criteria->compare('id_usuario',$this->id_usuario,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}