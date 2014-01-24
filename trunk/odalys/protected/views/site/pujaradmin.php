	<?php

	$scriptBuscar = '

	function buscar(objeto)
	{

		$.ajax({
				type:\'POST\',
				url:  "'.Yii::app()->getHomeUrl().'?r=site/pujaradmin",
				dataType: "html",
				data: {
					correo : objeto.value,
					datosDeControl: 1

				},
					beforeSend: function () {
					//$(\'#otro\').text("cargando");
	            },
					success : function(data){
						json = data
				},
					error : function(XMLHttpRequest, textStatus, errorThrown) {
				},
					complete : function(data) { 
						$(\'#resultado\').html(data);			 
			    }
		});

	}
	';

	Yii::app()->clientScript->registerScript('pujaradmin',$scriptBuscar,CClientScript::POS_READY);

	?>
		<table>
<tr>
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

				$criteria = new CDbCriteria;

				$criteria->condition = 'id=:id';
				$criteria->select = '*';
				$criteria->params = array('id'=>$_POST['imagen_s']);

				$imagen = ImagenS::model()->find($criteria);


				// Si la subasta esta activa
				if (Subastas::model()->findByPk($imagen['ids'])['silenciosa'])
				{
					echo '<div id="imageng_'.$_POST['imagen_s'].' class="image"> 
								<td><img src="'.$imagen['imagen'].'"> 
								</img></td><td>
									<p>'.$imagen['descri'].'</p> 
									<BR/> 
									Precio actual: '.number_format($imagen['actual']).'<BR>
									Puja siguiente: '.number_format($imagen['actual']*1.1).'<BR> </td>
									<!--Minimo Puja maxima: '.number_format($imagen['actual']*1.1*1.1).'-->
						  </div>';
				}else
				{
					echo 'La imágen no pertenece a la subasta silenciosa. Se recibio: '.$_POST['imagen_s'];
				}

			}else
			{
				//echo 'Error recibiendo el identificador de la imágen.';
			}


	?>
</tr>
</table>
<div class = "form">



    <?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'registro-pujas-pujar-form',
		'enableAjaxValidation'=>false,
		'enableClientValidation'=>true,
	    'focus'=>array($model,'maximo_dispuesto'),
	)); ?>

	<div class="row">
		<!--<select onchange="//buscar(this);" name="buscar" id="correo">
	    	<!--<option value="">Seleccione usuario</option>--

	    	<?php
/*	    		if($usuarios)
	    		{
	    			foreach ($usuarios as $t) {
	    		
	    				echo '<option value="'.$t.'">'.$t.'</option>';
			
	    			}
	    		}
	    		
*/
	    	?>
	    </select>-->
	    <?php
 			//echo CHtml::dropDownList('Cars', 'car_id', $usuarios);
	    	echo CHtml::activeDropDownList($model, 'correo', $usuarios);
	    ?>
	</div>

		<?php echo $form->errorSummary($model); ?>

		<?php if(Yii::app()->session['id_usuario']){

		?>
 		<p class="note">Campos con <span class="required">*</span> son requeridos.</p>  
 		<?php } ?>
<ul>
  
		<div class="row"><li>
			<?php echo $form->labelEx($model,'maximo_dispuesto'); ?>
			<?php echo $form->textField($model,'maximo_dispuesto',array('value'=> '0')); ?>

			<?php if(isset($_POST['imagen_s'])) echo $form->hiddenField($model,'id_imagen_s',array('value'=>$_POST['imagen_s'])); ?>
			<?php echo $form->error($model,'maximo_dispuesto'); ?></li>
		</div>
  
</ul>
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
																			}else{
																				alert(data["responseText"]);
																			}
																		}',
																		'success' => 'function(data){
																			json = data;//$("#pujaModal").dialog("close");
																			console.log(data);
																			//alert(data);
																				if(data[\'id\']){
																					alert(data["msg"]);
																					if(data["success"]){
																						$("#pujaModal").dialog("close");
																					}else{
																					//$("#registro-pujas-pujar-form").empty();
																					$("#registro-pujas-pujar-form").html(data);
																				}
																			}
																				//return false;
																		}',
																		'context'=>'js:this',

																        'beforeSend' => 'function(xhr,settings){
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

  </div>
	
	<div id="resultado">
		
	</div>

	<?php //$this->renderPartial();

		//$this->renderPartial('login');
		//echo $contenido;  
	
	?>