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
 * @property integer $fuesilenciosa
 * @property string $moneda
 * @property integer $envio_correos
 * @property integer $envio_correos_pre
 */
class Subastas extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'subastas';
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
			array('nombre, categoriaid, nombrec, horae, exp, horas, subasta, lugare, lugars, caratula, pdf, activa, publicaciones', 'required'),
			array('categoriaid, activa, solopdf, silenciosa, fuesilenciosa, envio_correos, envio_correos_pre', 'numerical', 'integerOnly'=>true),
			array('nombre, nombrec, horae, exp, horas, subasta, lugare, lugars, caratula, pdf', 'length', 'max'=>200),
			array('publicaciones', 'length', 'max'=>255),
			array('NS', 'length', 'max'=>400),
            array('moneda', 'length', 'max'=>3),
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
            'fuesilenciosa' => 'Fuesilenciosa',
            'moneda' => 'Moneda',
            'envio_correos' => 'Envio Correos',
			'envio_correos_pre' => 'Envio Correos PRe',
		);
	}

	public function subastaActiva(){

		if($this->silenciosaActiva())
			return true;
		else
			return false;
	}

	public function silenciosaActiva(){

		$criteria = new CDbCriteria;

		$criteria->condition = 'silenciosa=:silenciosa';
		$criteria->params = array(':silenciosa'=>1);

		return Subastas::model()->find($criteria);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Subastas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function enPresubasta(){

		$actualTime = new DateTime("now");

		$time = $this->fechaPresubasta();

		$intervaloPresubasta =  $time->getTimestamp() - $actualTime->getTimestamp();

		// Verificando que se encuentra en la proxima hora al finalizar la subasta.
		if( $intervaloPresubasta >=0 && $intervaloPresubasta <= Yii::app()->params['tiempoPresubasta']*3600 )
			return true;
		else
			return false;
	}

	public function fechaPresubasta(){
		$crono = Cronometro::model()->findByAttributes(array('ids'=> $this->ultimaSilenciosa()->id));

		$time = new DateTime($crono->fecha_finalizacion);

		return $time->add(new DateInterval('PT'.Yii::app()->params['tiempoPresubasta'].'H'));
	}

	// Fecha de finalización de la presubasta

	public function ultimaSilenciosa(){

		$criteria = new CDbCriteria;

		$criteria->condition = 'fuesilenciosa=:fuesilenciosa';
		$criteria->params = array(':fuesilenciosa'=>1);
		$criteria->order = 'id DESC';

		return Subastas::model()->find($criteria);
	}

	// Fecha de finalización de la subasta actual

	public function fechaSubastaActiva(){
		if(!$this->silenciosaActiva())
			return false;

		$crono = Cronometro::model()->findByAttributes(array('ids'=> $this->silenciosaActiva()->id));


		return new DateTime($crono->fecha_finalizacion);
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
        $criteria->compare('fuesilenciosa',$this->fuesilenciosa);
        $criteria->compare('moneda',$this->moneda,true);
        $criteria->compare('envio_correos',$this->envio_correos);
		$criteria->compare('envio_correos_pre',$this->envio_correos_pre);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}