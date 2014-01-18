<?php
/* @var $this RegistroPujasController */
/* @var $model RegistroPujas */
/* @var $form CActiveForm */
?>


<?php 

		if(isset($_POST['imagen_s']))
		{
			$model->id_imagen_s = $_POST['imagen_s'];

			$criteria = new CDbCriteria;

			$criteria->condition = 'id=:id';
			$criteria->select = '*';
			$criteria->params = array('id'=>$_POST['imagen_s']);

			$imagen = ImagenS::model()->find($criteria);


			// Si la subasta esta activa
			if (Subastas::model()->findByPk($imagen['ids'])['activa'])
			{
				echo '<div id="imageng_'.$_POST['imagen_s'].' class="image"> 
							<img src="'.$imagen['imageng'].'"> 
								<p>'.$imagen['descri'].'</p> 
								<BR> 
								<h1>Precio actual: '.$imagen['actual'].'</h1>
								Precio siguiente: '.($imagen['actual']*1.1).'<BR> 
								Minimo Monto maximo: '.($imagen['actual']*1.1*1.1).'
							</img>
					  </div>';
			}else
			{
				echo 'La imágen no pertenece a la subasta activa. Se recibio: '.$_POST['imagen_s'];
			}

		}else
		{
			//echo 'Error recibiendo el identificador de la imágen.';
		}


?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'registro-pujas-pujar-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
    'focus'=>array($model,'maximo_dispuesto'),
)); ?>

	<p class="note">Los campos con <span class="required">*</span> son obligatorios.</p>

	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'maximo_dispuesto'); ?>
		<?php echo $form->textField($model,'maximo_dispuesto'); ?>

		<?php if(isset($_POST['imagen_s'])) echo $form->hiddenField($model,'id_imagen_s',array('value'=>$_POST['imagen_s'])); ?>
		<?php echo $form->error($model,'maximo_dispuesto'); ?>
	</div>



	<div class="row buttons">
		<?php //echo CHtml::submitButton('Submit'); ?>
		<?php if(isset($_POST['imagen_s']))
					echo CHtml::ajaxSubmitButton('Pujar', '', array('type'=>'POST',//'update'=>'#pujaModal', 
																	'dataType' => "html",
																	//'data' => '{imagen_ss: "0"}',
																	'error' =>'function(data){
																		alert("Error");
																		alert(data["responseText"]);
																	}',
																	'success' => 'function(data){
																			json = data;
																			$(this).html(data); 
																			//return false;
																	}',
																	'context'=>'js:this',

															        'beforeSend' => 'function(xhr,settings){
																	        	//settings.data += {imagen_ss: $(this).attr(\'id\').split("_" )[0]});
																	        	console.log(typeof settings.data);
																	        	console.log(settings.data);
																	        	//settins.data += "&"+$.param({"imagen_ss": $(this).attr(\'id\').split("_" )[0]});
																		        //Encode at end
																		        //settings.data = jQuery.param(settings.data, false);
											            						//settings.data += encodeURIComponent(\'&imagen_ss\')
									              								//+ \'=\'
									              								//+ encodeURIComponent($(this).attr(\'id\').split("_")[0]);
																	        	//alert(settings.data);
									              								console.log(settings.data);
									              								//$(\'#RegistroPujas_\').val($(this).attr(\'id\').split("_")[0]);
															        }',
															        'complete' => 'function(){
															        	//$("#pujaModal").html(json);
															        	//$("#pujaModal").dialog("close");
															            //alert("Puja exitosa!");
															            }',
															       ),
													   		  array('id'=>$_POST['imagen_s'].'_'.uniqid())); 
   		  ?>

	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->