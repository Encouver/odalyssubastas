<?php
/* @var $this RegistroPujasController */
/* @var $model RegistroPujas */
/* @var $form CActiveForm */
?>


<?php 
	
	$modelImagenS = new ImagenS();
	//$_POST['data']->imagen_s

	if(isset($_POST['imagen_s']))
	echo '<div id="'.$_POST['imagen_s'].'" </div>';

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