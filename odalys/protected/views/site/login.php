<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Inicio de sesión';
$this->breadcrumbs=array(
	'Inicio de sesión',
);
?>


<p>Inicia sesión en odalys.com</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Campos con <span class="required">*</span> son requeridos.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); if(false){?>

		<p class="hint">
			Hint: You may login with <kbd>demo</kbd>/<kbd>demo</kbd> or <kbd>admin</kbd>/<kbd>admin</kbd>.
		</p>
		<?php }?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Iniciar sesión'); ?>
	</div>

	<div></div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<br/>
	<br/><br/>
	<br/>
<p>
	¿Olvidaste tu clave? Recupérala <?php echo CHtml::link('aquí','http://odalys.com/odalys/recupera.php'); ?>
	<br/>
	<br/>
	No te has registrado en odalys.com? Házlo <?php echo CHtml::link('aquí','http://odalys.com/odalys/registro.php'); ?>
</p>