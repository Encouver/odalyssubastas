<?php

/**
 * This is the model class for table "pre_subastas".
 *
 * The followings are the available columns in table 'pre_subastas':
 * @property integer $id
 * @property integer $usuario_id
 * @property integer $puja_maxima
 * @property integer $puja_telefonica
 * @property integer $asistir_subasta
 * @property integer $imagen_s_id
 * @property integer $no_hacer_nada
 * @property integer $subasta_id
 * @property double $monto
 * @property string $observaciones
 * @property string $telefonos
 *
 * The followings are the available model relations:
 * @property Usuarios $usuario
 * @property Subastas $subasta
 * @property ImagenS $imagenS
 */
class PreSubastas extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pre_subastas';
	}

	public $opcion;

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
			array('usuario_id, imagen_s_id, subasta_id', 'required'),
			array('opcion', 'numerical', 'integerOnly'=>true),
			array('usuario_id, puja_maxima, puja_telefonica, asistir_subasta, imagen_s_id, no_hacer_nada, subasta_id', 'numerical', 'integerOnly'=>true),
			array('puja_maxima', 'validarPujaMaxima'),
			array('monto', 'numerical'),
			array('observaciones, telefonos', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, usuario_id, puja_maxima, puja_telefonica, asistir_subasta, imagen_s_id, no_hacer_nada, subasta_id, monto', 'safe', 'on'=>'search'),
		);
	}

	public function validarPujaMaxima($attribute,$params){
        if($this->hasErrors()) {
            return;
        }

		if($this->$attribute == 1 && $this->monto < $this->imagenS->actual*1.1)
		{
            $this->addError('monto','El monto debe ser mayor a '.number_format($this->imagenS->actual*1.1).' '.$this->subasta->moneda.' (10% mayor del precio actual).');
			return false;
		}

		return true;

	}

    // Verifica si ya se existe la presubasta.
    public function yaSeDejo(){

        if($this->model()->findByAttributes(array('usuario_id'=>$this->usuario_id, 'imagen_s_id'=>$this->imagen_s_id, 'subasta_id'=>$this->subasta_id,)) && $this->scenario != 'update')
            return true;

        return false;
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'usuario' => array(self::BELONGS_TO, 'Usuarios', 'usuario_id'),
			'subasta' => array(self::BELONGS_TO, 'Subastas', 'subasta_id'),
			'imagenS' => array(self::BELONGS_TO, 'ImagenS', 'imagen_s_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'usuario_id' => 'Usuario',
			'puja_maxima' => 'Puja Maxima',
			'puja_telefonica' => 'Puja Telefonica',
			'asistir_subasta' => 'Asistir Subasta',
			'imagen_s_id' => 'Imagen S',
			'no_hacer_nada' => 'No Hacer Nada',
			'subasta_id' => 'Subasta',
			'monto' => 'Monto',
			'observaciones' => 'Observaciones',
			'telefonos' => 'Telefonos',
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
		$criteria->compare('usuario_id',$this->usuario_id);
		$criteria->compare('puja_maxima',$this->puja_maxima);
		$criteria->compare('puja_telefonica',$this->puja_telefonica);
		$criteria->compare('asistir_subasta',$this->asistir_subasta);
		$criteria->compare('imagen_s_id',$this->imagen_s_id);
		$criteria->compare('no_hacer_nada',$this->no_hacer_nada);
		$criteria->compare('subasta_id',$this->subasta_id);
		$criteria->compare('monto',$this->monto);
		$criteria->compare('observaciones',$this->observaciones,true);
		$criteria->compare('telefonos',$this->telefonos,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PreSubastas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
