<?php

/**
 * This is the model class for table "subastas".
 *
 * The followings are the available columns in table 'subastas':
 * @property integer $id
 * @property string $nombre
 * @property integer $categoriaid
 * @property string $nombrec
 * @property string $horae
 * @property string $exp
 * @property string $horas
 * @property string $subasta
 * @property string $lugare
 * @property string $lugars
 * @property string $caratula
 * @property string $pdf
 * @property integer $activa
 * @property string $publicaciones
 * @property string $NS
 * @property integer $solopdf
 * @property integer $silenciosa
 */
class Subastas extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Subastas the static model class
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
		return 'subastas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, categoriaid, nombrec, horae, exp, horas, subasta, lugare, lugars, caratula, pdf, activa, publicaciones', 'required'),
			array('categoriaid, activa, solopdf, silenciosa', 'numerical', 'integerOnly'=>true),
			array('nombre, nombrec, horae, exp, horas, subasta, lugare, lugars, caratula, pdf', 'length', 'max'=>200),
			array('publicaciones', 'length', 'max'=>255),
			array('NS', 'length', 'max'=>400),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre, categoriaid, nombrec, horae, exp, horas, subasta, lugare, lugars, caratula, pdf, activa, publicaciones, NS, solopdf, silenciosa', 'safe', 'on'=>'search'),
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
			'nombre' => 'Nombre',
			'categoriaid' => 'Categoriaid',
			'nombrec' => 'Nombrec',
			'horae' => 'Horae',
			'exp' => 'Exp',
			'horas' => 'Horas',
			'subasta' => 'Subasta',
			'lugare' => 'Lugare',
			'lugars' => 'Lugars',
			'caratula' => 'Caratula',
			'pdf' => 'Pdf',
			'activa' => 'Activa',
			'publicaciones' => 'Publicaciones',
			'NS' => 'Ns',
			'solopdf' => 'Solopdf',
			'silenciosa' => 'Silenciosa',
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
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('categoriaid',$this->categoriaid);
		$criteria->compare('nombrec',$this->nombrec,true);
		$criteria->compare('horae',$this->horae,true);
		$criteria->compare('exp',$this->exp,true);
		$criteria->compare('horas',$this->horas,true);
		$criteria->compare('subasta',$this->subasta,true);
		$criteria->compare('lugare',$this->lugare,true);
		$criteria->compare('lugars',$this->lugars,true);
		$criteria->compare('caratula',$this->caratula,true);
		$criteria->compare('pdf',$this->pdf,true);
		$criteria->compare('activa',$this->activa);
		$criteria->compare('publicaciones',$this->publicaciones,true);
		$criteria->compare('NS',$this->NS,true);
		$criteria->compare('solopdf',$this->solopdf);
		$criteria->compare('silenciosa',$this->silenciosa);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}