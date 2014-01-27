<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

if(!$modal){
	$this->pageTitle=Yii::app()->name . ' - Inicio de sesión';
	$this->breadcrumbs=array(
		'Inicio de sesión',
	);
}
?>


<p>Inicia sesión en odalys.com</p>

<div class="form" style="width:350px;">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>


	<div class="row" style="width:250px;">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
	<br/>
	<div class="row" style="width:250px;">
		<?php echo $form->labelEx($model,'password', array('style' => 'font-size:14x;')); ?>
		<?php echo $form->passwordField($model,'password', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'password'); if(false){?>


		<?php }?>
	</div>
		<br/>
	<p class="note" style="font-size:11px;">Campos con <span class="required">*</span> son requeridos.</p>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe', array('style' => 'font-size:12x;font-weight:normal;')); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
	<br/>
	<div class="row buttons">

		<?php if($modal) echo $form->hiddenField($model,'modal',array('value'=>true)); ?>


		<?php 	if(!$modal)
					echo CHtml::submitButton('Iniciar sesión'); 
				else
					echo CHtml::ajaxSubmitButton('Iniciar sesión', '',
											 array('type'=>'POST',//'update'=>'#pujaModal', 
												'dataType' => "json",
												//'data' => '{modal: true}',
												'error' =>'function(data){
													//alert("Error");
													//console.log(data);
													if(data["status"] == 200){
														$("#pujaModal").html(data["responseText"]);
														//$("#pujaModal").attr("style","with:600px;");
													}
													else{
														alert(data["responseText"]);
													}
												}',
												'success' => 'function(data){
														json = data;
														$("#pujaModal").dialog("close");
														location.reload();

												}',
												'context'=>'js:this',

										        'beforeSend' => 'function(xhr,settings){
		
										        }',
										        'complete' => 'function(){
				
										            }',
										       ),
								   		  array('class'=>'btn','style'=>'width:200px;','id'=>uniqid()));  ?>
	</div>

	<div></div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<br/>
	
<p>
	¿Olvidaste tu clave? Recupérala <?php echo CHtml::link('aquí','http://odalys.com/odalys/recupera.php'); ?>
	<br/>
	<br/>
	No te has registrado en odalys.com? Házlo <?php echo CHtml::link('aquí','http://odalys.com/odalys/registro.php'); ?>
</p>