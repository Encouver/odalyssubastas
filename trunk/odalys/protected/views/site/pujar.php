<?php
/* @var $this RegistroPujasController */
/* @var $model RegistroPujas */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'registro-pujas-pujar-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>


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
		<?php echo $form->labelEx($model,'monto_puja'); ?>
		<?php echo $form->textField($model,'monto_puja'); ?>
		<?php echo $form->error($model,'monto_puja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maximo_dispuesto'); ?>
		<?php echo $form->textField($model,'maximo_dispuesto'); ?>
		<?php echo $form->error($model,'maximo_dispuesto'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->