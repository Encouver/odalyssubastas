<?php

/**
 * This is the model class for table "registro_pujas".
 *
 * The followings are the available columns in table 'registro_pujas':
 * @property integer $id
 * @property integer $ids
 * @property integer $idusuario
 * @property integer $id_imagen_s
 * @property integer $monto_puja
 * @property integer $maximo_dispuesto
 * @property string $fecha
 */
class RegistroPujas extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RegistroPujas the static model class
	 */
	
	public $paleta;
	public $codigo;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'registro_pujas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('paleta, codigo', 'required'),
			array('ids, idusuario, id_imagen_s, monto_puja', 'numerical', 'integerOnly'=>true),
			array('maximo_dispuesto, paleta', 'match', 'pattern' => '/^[0-9]+$/', 'allowEmpty' => true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ids, idusuario, id_imagen_s, monto_puja, maximo_dispuesto, fecha', 'safe', 'on'=>'search'),
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
			'idusuario' => 'Idusuario',
			'id_imagen_s' => 'Id Imagen S',
			'monto_puja' => 'Monto Puja',
			'maximo_dispuesto' => 'Puja Máxima',
			'fecha' => 'Fecha',
			'codigo' => 'Código',
			'paleta' => 'Paleta',

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
		$criteria->compare('idusuario',$this->idusuario);
		$criteria->compare('id_imagen_s',$this->id_imagen_s);
		$criteria->compare('monto_puja',$this->monto_puja);
		$criteria->compare('maximo_dispuesto',$this->maximo_dispuesto);
		$criteria->compare('fecha',$this->fecha,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}