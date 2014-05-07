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

	if(isset($_POST['imagen_s']))
		$model->id_imagen_s = $imagenid = $_POST['imagen_s'];
 	else
  		$imagenid = $model->id_imagen_s;	
	$imagenesDir = 'http://www.odalys.com/odalys/';

	Yii::app()->clientscript->scriptMap = array('jquery-ui.min.js'=>false,
												'jquery-ui.js'=>false,
												'jquery.min.js'=>false,
												'jquery.js'=>false, );


	$imagen = ImagenS::model()->find('id=:id',array(':id'=>$imagenid));


	// Si la subasta esta activa
	$subas = Subastas::model()->findByPk($imagen['ids']);
	if ($subas['silenciosa'])
	{
		echo '<div id="imageng_'.$imagenid.' class="image"> 
					<table>
					<td  style="vertical-align:top"><img src="'.$imagenesDir.$imagen['imagen'].'"/></td>
					<td style="padding-left:14px">
						<p>'.$imagen['descri'].'</p> 
						<BR/>
						<precio id="'.$imagenid.'">
							Precio actual: <div><moneda>'.$subas->moneda.'</moneda> <actual_'.$imagenid.'>'.number_format($imagen['actual']).'</actual_'.$imagenid.'><BR></div>
							Puja siguiente: <div><moneda>'.$subas->moneda.'</moneda> <siguiente_'.$imagenid.'>';

		//Verificando si es primera puja
		$imgConPujas = RegistroPujas::model()->find('id_imagen_s=:imagen',
		array(
		  ':imagen'=>$model->id_imagen_s,
		));

		if($imgConPujas)
			echo number_format($imagen['actual']*1.1);
		else
			echo number_format($imagen['base']);

		echo '</siguiente_'.$imagenid.'></div>
						</precio><BR> </td></table>
			  </div>';
	}else
		throw new Exception("Error Processing Request: imagen no pertenece a subasta silenciosa activa.".$model->id_imagen_s, 1);



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

		<?php if(Yii::app()->session['admin']){

		?>
 		<p class="note">Campos con <span class="required">*</span> son requeridos.</p>  
 		<?php } ?>
<ul>
  
		<div class="row">
			<li>
				<?php echo $form->labelEx($model,'maximo_dispuesto'); ?>
				<?php echo $form->textField($model,'maximo_dispuesto',array('value'=> '0')); ?>
				<?php echo $form->error($model,'maximo_dispuesto'); ?>
			</li>
			<?php echo $form->hiddenField($model,'id_imagen_s',array('value'=>$imagenid)); ?>

			
		</div>

		<div class="row">
			<?php 
				//$usuario_pujaindefinida = Usuarios::model()->find('id=:id',array(':id'=>$imagen->id_usuario));
				if($imagen->puja_indefinida == 0)
					echo '<p>La puja indefinida para esta imagen esta actualmente inactiva.</p>';
				else
					echo '<p>La puja indefinida para esta imagen se encuentra activa </p>';
					//echo '<p>La puja indefinida para esta imagen se encuentra activa para el usuario: '.$usuario_pujaindefinida->email.' </p>';
						
				
				//echo $form->checkBox($imagen,'puja_indefinida',array('checked'=>''));
				/*echo CHtml::activelabelEx($imagen,'puja_indefinida');
				echo CHtml::activeCheckBox($imagen,'puja_indefinida',array('checked'=>''));
				echo CHtml::error($imagen,'puja_indefinida');*/
			?>
		</div>

		<div class="row">
			<?php 

				echo CHtml::activeHiddenField($imagen,'puja_indefinida',array('value'=>0,'id'=>'pujailimitada'));
			?>
		</div>
  
</ul>
		<div class="row buttons">
			<?php //echo CHtml::submitButton('Submit'); ?>
			<?php 

						echo CHtml::ajaxSubmitButton('Puja Ilimitada', '', array('type'=>'POST',
																		'dataType' => "json",
																		'error' =>'function(data){
																			if(data["status"] == 200){
																				$("#registro-pujas-pujar-form").html(data["responseText"]);
																			}else{
																				alert(data["responseText"]);
																			}
																		}',
																		'success' => 'function(data){
																			json = data;//$("#pujaModal").dialog("close");
																				if(data[\'id\']){
																					alert(data["msg"]);
																					if(data["success"]){
																						$("#pujaModal").dialog("close");
																					}else{
																						$("#registro-pujas-pujar-form").html(data);
																				}
																			}
																				
																		}',
																		'context'=>'js:this',
																        'beforeSend' => 'function(xhr,settings){
																        	$("input#pujailimitada").attr("value",1);
																        	alert($("input#pujailimitada").attr("value"));
																        }',
																        'complete' => 'function(){
																         }',
																       ),
														   		  array('id'=>$imagenid.'_'.uniqid())); 
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
																			//console.log(data);
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