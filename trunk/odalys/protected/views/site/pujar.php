<?php
/* @var $this RegistroPujasController */
/* @var $model RegistroPujas */
/* @var $form CActiveForm */
?>


<?php /*
Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;
Yii::app()->clientscript->scriptMap['jquery-ui.js'] = false;
Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;
Yii::app()->clientscript->scriptMap['jquery.js'] = false;*/

Yii::app()->clientscript->scriptMap = array('jquery-ui.min.js'=>false,
											'jquery-ui.js'=>false,
											'jquery.min.js'=>false,
											'jquery.js'=>false, );

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
								<BR/> 
								<h1>Precio actual: '.number_format($imagen['actual']).'</h1>
								Puja siguiente: '.number_format($imagen['actual']*1.1).'<BR> 
								<!--Minimo Puja maxima: '.number_format($imagen['actual']*1.1*1.1).'-->
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


	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'maximo_dispuesto'); ?>
		<?php echo $form->textField($model,'maximo_dispuesto',array('value'=> '0')); ?>

		<?php if(isset($_POST['imagen_s'])) echo $form->hiddenField($model,'id_imagen_s',array('value'=>$_POST['imagen_s'])); ?>
		<?php echo $form->error($model,'maximo_dispuesto'); ?>
	</div>

	<div class="row">

		<?php 
		if(Yii::app()->session['id_usuario']){
			if(!isset(Yii::app()->request->cookies['up']) && !isset(Yii::app()->request->cookies['uc'])){
			  echo $form->labelEx($model,'paleta'); 
			  echo $form->textField($model,'paleta',array('value'=> '')); 
		 	  echo $form->error($model,'paleta');
		 	}
		 } ?>
	</div>

	<div class="row">

		<?php 
		if(Yii::app()->session['id_usuario']){
			if(!isset(Yii::app()->request->cookies['up']) && !isset(Yii::app()->request->cookies['uc'])){
			  echo $form->labelEx($model,'codigo'); 
			  echo $form->textField($model,'codigo',array('value'=> '')); 
		 	  echo $form->error($model,'codigo');
		 	}
		 } ?>
	</div>

	<div class="row buttons">
		<?php //echo CHtml::submitButton('Submit'); ?>
		<?php if(isset($_POST['imagen_s']))
					$imagenid = $_POST['imagen_s'];
			  else
			  		$imagenid = $model->id_imagen_s;
					echo CHtml::ajaxSubmitButton('Pujar', '', array('type'=>'POST',//'update'=>'#pujaModal', 
																	'dataType' => "json",
																	//'data' => '{imagen_ss: "0"}',
																	'error' =>'function(data){
																		//alert("Error");
																		console.log(data);
																		if(data["status"] == 200){
																		//	$("#registro-pujas-pujar-form").empty();
																			$("#registro-pujas-pujar-form").html(data["responseText"]);
																		}
																		else{
																			alert(data["responseText"]);
																		}
																	}',
																	'success' => 'function(data){
																		json = data;//$("#pujaModal").dialog("close");
																		//$("#Cancel").click();
																		//alert(data);
																			if(data[\'id\']){
																				alert(data["msg"]);
																				if(data["success"]){
																					$("#pujaModal").dialog("close");
																				}
																			}else{
																				//$("#registro-pujas-pujar-form").empty();
																				$("#registro-pujas-pujar-form").html(data);
																			}
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
													   		  array('id'=>$imagenid.'_'.uniqid())); 
   		  ?>

	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->