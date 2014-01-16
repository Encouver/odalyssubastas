<?php
/* @var $this RegistroPujasController */
/* @var $model RegistroPujas */
/* @var $form CActiveForm */
?>


<?php 
	
	//$_POST['data']->imagen_s

		if(isset($_POST['imagen_s']))
		{
			$criteria = new CDbCriteria;

			$criteria->condition = 'id=:id';
			$criteria->select = '*';
			$criteria->params = array('id'=>$_POST['imagen_s']);

			$imagen = ImagenS::model()->find($criteria);


			// Si la subasta esta activa
			if (Subastas::model()->findByPk($imagen['ids'])['activa'])
			{
				echo '<div id="'.$_POST['imagen_s'].'"> <img src="'.$imagen['imageng'].'"> <p>'.$imagen['descri'].'</p> <BR> <h1>Precio actual: '.$imagen['actual'].'</h1></img></div>';
			}else
			{
				echo 'La imágen no pertenece a la subasta activa. Se recibio: '.$_POST['imagen_s'];
			}

		}else
		{
			echo 'Error recibiendo el identificador de la imágen.';
		}


?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'registro-pujas-pujar-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
    'focus'=>array($model,'maximo_dispuesto'),
)); ?>

	<p class="note">Los campos con <span class="required">*</span> son obligatorios.</p>

	<?php //echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'maximo_dispuesto'); ?>
		<?php echo $form->textField($model,'maximo_dispuesto'); ?>
		<?php echo $form->error($model,'maximo_dispuesto'); ?>
	</div>


	<div class="row buttons">
		<?php //echo CHtml::submitButton('Submit'); ?>
		<?php echo CHtml::ajaxSubmitButton('Pujar', '', array('type'=>'POST',//'update'=>'#pujaModal', 
															'dataType' => "html",
															'data' => '{
															}',
															'success' => 'function(data){
																	json = data;
															}',
													        'beforeSend' => 'function(){
													            //alert("beforeSend");
													        }',
													        'complete' => 'function(){
													        	//$("#pujaModal").html(json);
													        	//$("#pujaModal").dialog("close");
													            alert("Puja exitosa!");
													            }',),
													    array('type'=>'submit')); ?>

	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->