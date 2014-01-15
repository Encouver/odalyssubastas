<?php
/* @var $this RegistroPujasController */
/* @var $model RegistroPujas */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'registro-pujas-pujar-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
    'focus'=>array($model,'idusuario'),
)); ?>

	<p class="note">Los campos con <span class="required">*</span> son obligatorios.</p>

	<?php //echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'idusuario'); ?>
		<?php echo $form->textField($model,'idusuario'); ?>
		<?php echo $form->error($model,'idusuario'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_imagen_s'); ?>
		<?php echo $form->textField($model,'id_imagen_s'); ?>
		<?php echo $form->error($model,'id_imagen_s'); ?>
	</div>


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
													        	$("#pujaModal").dialog("close");
													            alert("Puja exitosa!");
													            }',),
													    array('type'=>'submit')); ?>

	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->