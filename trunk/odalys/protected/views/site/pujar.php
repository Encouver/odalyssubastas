<?php
/* @var $this RegistroPujasController */
/* @var $model RegistroPujas */
/* @var $form CActiveForm */
	if(isset($_POST['imagen_s']))
		$model->id_imagen_s = $imagenid = $_POST['imagen_s'];
 	else
  		$imagenid = $model->id_imagen_s;
  	$imagenesDir = 'http://www.odalys.com/odalys/';

?>

	 <style type="text/css">
			ul li #row {
			    width:100%;
			}

	  </style>
	<div class="box" style="font-size:12px">

	<table>
		<tr>
			<td style='width: 50%;'>

				
								<?php /*
								Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;
								Yii::app()->clientscript->scriptMap['jquery-ui.js'] = false;
								Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;
								Yii::app()->clientscript->scriptMap['jquery.js'] = false;*/

								Yii::app()->clientscript->scriptMap = array('jquery-ui.min.js'=>false,
																			'jquery-ui.js'=>false,
																			'jquery.min.js'=>false,
																			'jquery.js'=>false, );

										//if(isset($_POST['imagen_s']))
										//{
											//$model->id_imagen_s = $_POST['imagen_s'];


											$imagen = ImagenS::model()->find('id=:id',array(':id'=>$imagenid));


											// Si la subasta esta activa
											if (Subastas::model()->findByPk($imagen['ids'])['silenciosa'])
											{
												echo '<div id="imageng_'.$imagenid.' class="image"> 
															<table>
															<td  style="vertical-align:top"><img src="'.$imagenesDir.$imagen['imagen'].'"/></td>
															<td style="padding-left:14px">
																<p>'.$imagen['descri'].'</p> 
																<BR/>
																Precio actual: '.number_format($imagen['actual']).'<BR>
																Puja siguiente: '.number_format($imagen['actual']*1.1).'<BR> </td></table>
																<!--Minimo Puja maxima: '.number_format($imagen['actual']*1.1*1.1).'-->
													  </div>';
											}else
												throw new Exception("Error Processing Request: imagen no pertenece a subasta silenciosa activa.", 1);
												

										//}else
										//{
											//echo 'Error recibiendo el identificador de la imágen.';
										//}	
								?>
							
					
	</td>
	 	
	 	<td style="padding-left:14px; width: 50%;">

				<div class="form-inline" style="line-height: 5px;">

				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'registro-pujas-pujar-form',
					'enableAjaxValidation'=>false,
					'enableClientValidation'=>true,
				    'focus'=>array($model,'maximo_dispuesto'),
				)); ?>


					<?php //echo $form->errorSummary($model); ?>

					<?php if(Yii::app()->session['id_usuario']){

					?>
	 
			 		<?php }?>
				<ul>
						 <?php  
							
							$idsub = $imagenid.'_'.uniqid();

							$precioActual = number_format(ImagenS::model()->find('id=:id', array(':id'=>$imagenid))['actual']*1.1);

							$baseUrl = Yii::app()->request->baseUrl;
							Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/numberformat.js', CClientScript::POS_END);
						?>
					<div class="row"><li>
						<?php echo $form->labelEx($model,'maximo_dispuesto', array('class'=>'titulos')); ?>
						<?php echo $form->textField($model,'maximo_dispuesto', 
						array('class'=>'form-control','oninput'=>'js: var precio = "'.$precioActual.'";  if($(this).val() != ""){ precio = $(this).val();} $("#'.$idsub.'").attr("value","Pujar "+number_format(precio));')); ?>

						<?php if(isset($_POST['imagen_s'])) echo $form->hiddenField($model,'id_imagen_s',array('value'=>$_POST['imagen_s'])); ?>
						<?php echo $form->error($model,'maximo_dispuesto'); ?></li>
					</div>
						 		<p class="note" style="font-size:10px">Campos con <span class="required">*</span> son requeridos.</p> 
					<div class="row">

						<?php 
						if(Yii::app()->session['id_usuario']){
							if(!isset(Yii::app()->request->cookies['up']) && !isset(Yii::app()->request->cookies['uc'])){
								?><li><?php
							  echo $form->labelEx($model,'paleta', array('class'=>'titulos')); 
							  echo $form->textField($model,'paleta',array('class'=>'form-control','value'=> '')); 
						 	  echo $form->error($model,'paleta');
						 	  ?></li><?php
						 	}
						 } ?>
					</div>

					<div class="row">

						<?php 
						if(Yii::app()->session['id_usuario']){
							if(!isset(Yii::app()->request->cookies['up']) && !isset(Yii::app()->request->cookies['uc'])){
								?><li><?php
							  echo $form->labelEx($model,'codigo', array('class'=>'titulos')); 
							  echo $form->textField($model,'codigo',array('class'=>'form-control','value'=> '')); 
						 	  echo $form->error($model,'codigo');
						 	  ?></li><?php
						 	}
						 } ?>
					</div>

			  
					<div>
						<?php 	
						echo CHtml::ajaxSubmitButton('Pujar '.$precioActual, '',
											 array('type'=>'POST',//'update'=>'#pujaModal', 
												'dataType' => "json",
												//'data' => '{imagen_ss: "0"}',
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
													json = data;//$("#pujaModal").dialog("close");
														if(data[\'id\']){
															alert(data["msg"]);
															if(data["success"]){
																$("#pujaModal").dialog("close");
															}else
																$("#pujaModal").html(data["responseText"]);
																//$("#registro-pujas-pujar-form").attr("style","with:600px;");

														}else{

															$("#pujaModal").html(data);
															//$("#pujar_container").attr("style","with:600px;");
														
														}

												}',
												'context'=>'js:this',

										        'beforeSend' => 'function(xhr,settings){
		
										        }',
										        'complete' => 'function(){
				
										            }',
										       ),
								   		  array('class'=>'btn','style'=>'width:200px;','id'=>$idsub)); 
								   		  ?>

					</div>
			</ul>

				<?php $this->endWidget(); ?>

				</div><!-- form -->
			</td>
		</tr>
	</table>



	</div>
