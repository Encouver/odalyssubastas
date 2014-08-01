<?php

class SiteController extends Controller
{

	//public $layout='//layouts/column2';
	/**
	 * Declares class-based actions.
	 */
	public $imagenesDir = 'http://www.odalys.com/odalys/';
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionFiltro()
	{
		$layout='//layouts/column2';


		$tabla = '<table>';




		$tabla .= '</table>';



	}
	public function actionReporteresultados()
	{

		$arreglo = array();

		$criteria = new CDbCriteria;

		$criteria->condition = 'fuesilenciosa=:fuesilenciosa';
		$criteria->params = array(':fuesilenciosa'=>1);
		$criteria->order = 'id DESC';

		$silenciosa = Subastas::model()->find($criteria);		
			
		$titulo = 'Informe de la '.$silenciosa['nombre'].' '.$silenciosa['nombrec'];

		$ganadores = ImagenS::model()->findAll('ids=:ids', array(':ids' => $silenciosa['id']));

		$contenido ="<html>
		<head>
			<title>Probando</title>
			 <style type='text/css'>
		<!--
			table.page_header {width: 100%; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }
			h1 {color: #000033}
			h2 {color: #000055}
			h3 {color: #000077}
			
			div.standard
			{
				padding-left: 5mm;
			}
		-->
		</style>
		</head>
		<body>
			<div style='margin: 0px auto'>
			<img src='http://odalys.com/odalys/images/log.png'/>
		    <h4 style='text-align:center; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>$titulo<hr></h4>
		    <h5 style='text-align:left;'>Adjudicados</h5>
		    <table class='page_header'>
			<tr>
		    	<td  style='width: 15%;'>Nombre y Apellido</td>
		        <td  style='width: 5%;'>Paleta</td>
		        <td align='center' style='width: 15%;'>Lote</td>
		        <td  style='width: 20%;'>Imagen</td>";
		        $contenido .= "<td  style='width: 15%;'>";
		        $contenido .=  $silenciosa['moneda'];
		         $contenido .= "</td></tr> ";
		    foreach ($ganadores as $key => $value)
				{
					if($value->id_usuario)
					{
						$paleta = Usuariospujas::model()->find('idsubasta=:ids AND idusuario=:idusuario', array(':ids'=>$silenciosa['id'], ':idusuario' => $value->id_usuario));

						$usuario = Usuarios::model()->find('id=:id', array('id'=>$value->id_usuario));

						$contenido .=
						'
							<tr>
						<td>'. $usuario['nombre'] .' '. $usuario['apellido'].'</td>
							<td>' .$paleta['paleta'].'</td>
							<td>'.$value->descri.'</td>
							<td><img src="http://www.odalys.com/odalys/'.$value->imagen.'"/></td>
							<td>'.$silenciosa['moneda'].' '.number_format($value->actual).'</td>
							</tr>
						';				
					}
				}

   			 $contenido .="
			</table>
		    </div>
			</body>
			</html>";

			$html2pdf = Yii::app()->ePdf->HTML2PDF();
	        $html2pdf->WriteHTML($contenido);
	        $html2pdf->Output("repore.pdf", 'D');

		}

	public function actionReportepujas()
	{

		//$arreglo = array();

		$criteria = new CDbCriteria;

		$criteria->condition = 'fuesilenciosa=:fuesilenciosa';
		$criteria->params = array(':fuesilenciosa'=>1);
		$criteria->order = 'id DESC';

		$silenciosa = Subastas::model()->find($criteria);		
			
		$titulo = 'Informe de la '.$silenciosa['nombre'].' '.$silenciosa['nombrec'];

		$ganadores = ImagenS::model()->findAll('ids=:ids', array(':ids' => $silenciosa['id']));


		$contenido ="<html>
		<head>
			<title>Probando</title>
			 <style type='text/css'>
		<!--
			table
			{
			width: 100%;

			}
			table.page_header {border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }
			h1 {color: #000033}
			h2 {color: #000055}
			h3 {color: #000077}
			
			div.standard
			{
				padding-left: 5mm;
			}
		-->
		</style>
		</head>
		<body>
			<div style='margin: 0px auto'>
			<img src='http://odalys.com/odalys/images/log.png'/>
		    <h4 style='text-align:center; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>$titulo<hr></h4>
		    <h5 style='text-align:left;'>Pujadores</h5>
		    ";

		    
		    foreach ($ganadores as $key => $value)
				{
					if($value->id_usuario)
					{
							$auxiliar = 0;
							$contenido .= 
						    "<table class='page_header'>
							<tr>
						    	<td  style='width: 50%;'><img src=\"http://www.odalys.com/odalys/$value->imagen\"/></td>
						        <td  style='width: 50%;'>$value->descri</td>
						    </tr></table> ";

						$puja = RegistroPujas::model()->findAll('id_imagen_s=:id_imagen_s ORDER BY fecha ASC', array(':id_imagen_s'=>$value->id));

						$money = $silenciosa['moneda'];
						$contenido .= 
						    "<table>
							<tr>
						    	<td  style='width: 35%;'>Nombre y Apellido</td>
						        <td  style='width: 30%;'>Paleta</td>
						        <td  style='width: 35%;'>$money</td>
						    </tr> ";


						    foreach ($puja as $clave => $valor) 
						    {
						    
						    	$paleta = Usuariospujas::model()->find('idsubasta=:ids AND idusuario=:idusuario', array(':ids'=>$silenciosa['id'], ':idusuario' => $valor->idusuario));

								$usuario = Usuarios::model()->find('id=:id', array('id'=>$valor->idusuario));

								$contenido .=
										'
											<tr>
										<td>'. $usuario['nombre'] .' '. $usuario['apellido'].'</td>
											<td>' .$paleta['paleta'].'</td>
											<td> $money. '.number_format($valor->monto_puja).'</td>
											</tr>
								';



						    }

						    $contenido .= "</table>";
						
						
					}
				}

   			 $contenido .="
		    </div>
			</body>
			</html>";


			$html2pdf = Yii::app()->ePdf->HTML2PDF();
	        $html2pdf->WriteHTML($contenido);
	        $html2pdf->Output("repore.pdf", 'D');

	        //$this->render('reporte', array('content'=>$contenido));

		}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		//$this->imagenesDir;

		$criteria = new CDbCriteria;

		$criteria->condition = 'silenciosa=:silenciosa';
		$criteria->params = array(':silenciosa'=>1);

		$subas = Subastas::model()->find($criteria);


		if(!$subas)
		{

			$this->actionResultados();
			//$this->redirect('odalys/site/resultados'/*, array('imprimir'=>$imprimir)*/);
			//Aqui va la redirección a la vista resultado
			//echo 'No hay subasta activas';
			return;
		}


		$imprimir = $this->listaImagen($subas);


		$this->layout='//layouts/column1';

		//$imprimir ="Hola";
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php1'
		$this->render('index', array('imprimir'=>$imprimir,'subasta'=>$subas,'imagenesDir'=>$this->imagenesDir));

	}

	public function actionTerminar(){


		$suba = Subastas::model()->find('silenciosa=1');
		if($suba){

			$crono = Cronometro::model()->find('ids=:ids', array(':ids'=>$suba->id));
			
			if($crono)
			if( strtotime($crono->fecha_finalizacion) < time() ){
				//$this->actionIndex();
				$suba->silenciosa = 0;
			}
		
		
		

		if($suba->save())
			$this->redirect(Yii::app()->homeUrl);
			//$this->redirect(array('site/index'));
		else
			throw new Exception("Error Processing Request: No se pudo finalizar la subasta.", 1);
		}else
			$this->redirect(Yii::app()->homeUrl);
			//$this->redirect(array('site/index'));
			
	}
	public function listaImagen($subas){
		
		$query = ImagenS::model()->findAll('ids=:ids', array(':ids'=>$subas['id']));

		$numero = 0;
		$fancyElements = '';
		$imprimir = '<div id="wrapper_imagens" width="100%" class="tablaresultado">';
		foreach ($query as $key => $value) {
			$numero++;
			// El usuario de la imágen en value tiene paleta y código asignados esta subasta
			$resultado= Usuariospujas::model()->find('idusuario=:idusuario && idsubasta=:idsubasta', array(':idusuario'=>$value->id_usuario, ':idsubasta'=> $subas->id));
			
			
			//cambiar a *********ADMIN******
			$imagenElement = CHtml::image(Yii::app()->getBaseUrl(true).'/images/loader.gif','Cargando',array('data-original'=>$this->imagenesDir.$value->imagen, 'class'=>'lazy', 'onError'=>'this.onerror=null;this.src=\''.Yii::app()->getBaseUrl(true).'/images/loader.gif\';', 'width'=>'auto','height'=>'auto'));
			if(Yii::app()->session['admin'])
				$link = CHtml::ajaxLink( $imagenElement,
								        $this->createUrl('site/pujaradmin'),
								        array(
								            //'onclick'=>'$("#pujaModal").dialog("open"); return false;',
								            //'update'=>'#pujaModal'
								            'type'=>'POST',
								            'data' => array('imagen_s'=> '0' ),
								            'context'=>'js:this',
								            'beforeSend'=>'function(xhr,settings){
								            						settings.data = encodeURIComponent(\'imagen_s\')
						              								+ \'=\'
						              								+ encodeURIComponent($(this).attr(\'id\').split("_")[1]);
								            }',
								            'success'=>'function(r){$("#pujaModal").html(r).dialog("open"); return false;}'
								        ),
								        array('id'=>'admin_'.$value->id,'style'=>'vertical-align: bottom;')
									);
			else//if(Yii::app()->session['id_usuario'])
				$link = CHtml::link($imagenElement, '', array('class'=> 'des_'.$value->id,'rel'=>'gallery','style'=>'vertical-align: bottom;'));
				//height="480"
			// Fancybox
			$this->mostrandoImagen($value);


				$imprimir .='<div id="elementosImagens" style="height: 200px; text-align: center;" align="center" class="tile '.$value->solonombre.'" data-nombres="'.$value->nombres.'" data-apellidos="'.$value->apellidos.'" data-numero="'.$numero.'">
								<span style="display: inline-block; height:100px; vertical-align: bottom; "> </span> 
								'.$link.'<div style="padding-bottom: 8px;"></div>';
						
				// Usuario activo que tiene paleta y codigo asignado en esta subasta o es administrador
				$usuario_activo = Yii::app()->session['admin'] ||
								 	(Yii::app()->session['id_usuario'] &&
								 	 Usuariospujas::model()->find(' idsubasta=:idsubasta AND idusuario=:idusuario', array(':idsubasta'=>$subas->id,':idusuario'=>Yii::app()->session['id_usuario'])));
				
				$colorActual = '';
				if($value->id_usuario == Yii::app()->session['id_usuario'])
					$colorActual = 'red';
				$imprimir .= '<loteautor>'.$value->solonombre.'</loteautor><br><span id="imagen_'.$value->id.'">';
				if($resultado)
				{//La imágen tiene un pujador
					
					if(Yii::app()->session['admin'])	//Vista del admin
						$imprimir .= 'Paleta: <paleta_'.$value->id.'>'.$resultado['paleta'].'</paleta_'.$value->id.'><br/>
									  <span style="color: '.$colorActual.';">Actual: <moneda>'.$subas->moneda.'</moneda> <cantidad_'.$value->id.'>'.number_format($value->actual).'</cantidad_'.$value->id.'></span>';
					else //vista del usuario normal
						if ($usuario_activo)	//Solo los usuarios activos pueden ver el precio actual
							$imprimir .= '<span style="color: '.$colorActual.';">
										  Actual: <moneda>'.$subas->moneda.'</moneda> <cantidad_'.$value->id.'>'.number_format($value->actual).'</cantidad_'.$value->id.'></span>';

					// number_format($value->actual,0,'.','') // entero sin coma
					// '.$value->imagen.'						//imagen pequeña

				}else
				{//La imágen no tiene puja
					if ($usuario_activo)	//Solo los usuarios activos pueden ver el precio actual
						$imprimir.= '<span style="color: '.$colorActual.';">
									  Actual: <moneda>'.$subas->moneda.'</moneda> <cantidad_'.$value->id.'>'.number_format($value->actual).'</cantidad_'.$value->id.'></span>';
					
					// number_format($value->actual,0,'.','') // entero sin coma
				}

				$imprimir .= '</span> ';



				if(Yii::app()->session['id_usuario'])
				{
					//Verificando si la imágen tiene puja máxima
					$usuarioPM = RegistroPujas::model()->find('id_imagen_s=:imagen AND verificado=:verificado AND idusuario=:idusuario',
					array(
					  ':imagen'=>$value->id,
					  ':verificado'=>1,
					  'idusuario'=>$value->id_usuario,
					));

					$etiqueta = 'Pujar';
					if($value->id_usuario == Yii::app()->session['id_usuario'] && !$usuarioPM)
						$etiqueta = 'Realizar puja máxima';
					if($value->id_usuario == Yii::app()->session['id_usuario'] && $usuarioPM)
						$etiqueta = '(modificar puja máxima)';
					$pujarAjaxLink = CHtml::ajaxLink($etiqueta,
		       										$this->createUrl('site/pujar'), array(
										            //'onclick'=>'$("#pujaModal").dialog("open"); return false;',
										            //'update'=>'#pujaModal'
										            'type'=>'POST',
										            'data' => array('imagen_s'=> '0' ),
										            'context'=>'js:this',
										            'beforeSend'=>'function(xhr,settings){
										            						settings.data = encodeURIComponent(\'imagen_s\')
									          								+ \'=\'
									          								+ encodeURIComponent($(this).attr(\'id\'));
										            }',
										            'success'=>'function(r){$("#pujaModal").html(r).dialog("open"); return false;}'
										        ),
										        array('id'=>$value->id, 'style'=>'color: #014F92;')
											);

    				// Verificando si es primera puja
    				$imgConPujas = RegistroPujas::model()->find('id_imagen_s=:imagen', array(':imagen'=>$value->id,));

    				if($imgConPujas)
						// Puja siguiente
						$siguiente = $value->actual*1.1;	// se icrementa el valor de la imagen por 10%
					else
						$siguiente = $value->base;

					if(!Yii::app()->session['admin'])
					
							if(!($value->id_usuario == Yii::app()->session['id_usuario']) )
							{

								$imprimir .= '<br><w id="'.$value->id.'a">'.$pujarAjaxLink.' <pujasiguienteafterlink><moneda>'.$subas->moneda.'</moneda> 
											  <siguientei_'.$value->id.'>'.number_format($siguiente).'</siguientei_'.$value->id.'><pujasiguienteafterlink></w><BR/>';
							}
							else{
			
								$imprimir .= '<w id="'.$value->id.'a">'.CHtml::image(Yii::app()->getBaseUrl(false).'/images/vendido.png','',
																						array('style'=>'width: 5px;hight:5px;'));
								$imprimir .= '<br>Prox. Puja: <pujasiguienteafterlink><moneda>'.$subas->moneda.'</moneda> 
											  <siguientei_'.$value->id.'>'.number_format($siguiente).'</siguientei_'.$value->id.'><pujasiguienteafterlink>';
								if($usuarioPM)
									$imprimir .= '<span style="color: red;"><p> Puja máxima: <moneda>'.$subas->moneda.'</moneda> '.number_format($usuarioPM->maximo_dispuesto).'</p></span>';
								else
									$imprimir .= '<br>';
								$imprimir .=  $pujarAjaxLink.'</w>';

							}
					}
					elseif(!Yii::app()->session['admin'])
					{
						//Ventana modal de login
						/*$pujarAjaxLink = CHtml::ajaxLink('Pujar',
			        	$this->createUrl('site/login'), array(
											            //'onclick'=>'$("#pujaModal").dialog("open"); return false;',
											            //'update'=>'#pujaModal'
											            'type'=>'POST',
											            'data' => array('modal'=> true ),
											            'context'=>'js:this',
											            'beforeSend'=>'function(xhr,settings){

											            }',
											            'success'=>'function(r){$("#pujaModal").html(r).dialog("open"); return false;}'
											        ),
											        array('id'=>$value->id, 'style'=>'color: #014F92;')
												);*/
						/*temporal */$pujarAjaxLink = CHtml::link('Pujar',array('site/login')); 
						$imprimir .= $pujarAjaxLink.'<BR/>';					
					}
				$imprimir .= '</div>';



				}
		return $imprimir .='</div>'.$fancyElements;

	}

	public function actionResultados()
	{

		$criteria = new CDbCriteria;

		$criteria->condition = 'fuesilenciosa=:fuesilenciosa';
		$criteria->params = array(':fuesilenciosa'=>1);
		$criteria->order = 'id DESC';

		$ultimaSubastaSilenciosa = Subastas::model()->find($criteria);


		$query = ImagenS::model()->findAll('ids=:ids',array(':ids'=>$ultimaSubastaSilenciosa['id']));


		$contador = 0;
		$numero = 0;
		$fancyElements = $imprimir = "";
		//echo '<table width="80%"><tr>';
		$imprimir = '<div id="wrapper_imagens"  width="100%" class="tablaresultado">';
		foreach ($query as $key => $value) {
			$numero ++;
			
			$link = CHtml::link(CHtml::image('','',array('data-original'=>$this->imagenesDir.$value->imagen,'style'=>'vertical-align: bottom;', 'class'=>'lazy', 'onError'=>'this.onerror=null;this.src=\''.Yii::app()->getBaseUrl(true).'/images/loader.gif\';', 'width'=>'auto','height'=>'auto'))
				,'', array('class'=> 'des_'.$value->id,'rel'=>'gallery'));
			
			if($contador==6)
			{
				//$imprimir .= '<tr align="center" valign="bottom">';
			}
				$contador++;
				$imprimir .=  '<div id="elementosImagens" style="height: 160px; text-align: center;" align="center" style="height: 180px;" class="tile '.$value->solonombre.'" data-nombres="'.$value->nombres.'" data-apellidos="'.$value->apellidos.'" data-numero="'.$numero.'">';
				
				$imprimir .=  '<span style="display: inline-block; height:100px; vertical-align: bottom; "> </span> 
										'.$link.'<div style="padding-bottom: 8px;"></div> <loteautor>'.$value->solonombre.'</loteautor>';
					if($value->id_usuario>0)
					{
						
						
						if(Yii::app()->session['admin'])
						{
							$ganador_imagen = Usuariospujas::model()->find('idusuario=:idusuario && idsubasta=:idsubasta', array(':idusuario'=>$value->id_usuario, ':idsubasta'=>$ultimaSubastaSilenciosa->id));
							$imprimir .= '<div>Paleta <paleta_'.$value->id.'>'.$ganador_imagen['paleta'].'</paleta_'.$value->id.'></div>';

						}
						if(Yii::app()->session['id_usuario'] && Yii::app()->session['id_usuario'] == $value->id_usuario)
						{
							$imprimir .= '<br/><w id="'.$value->id.'a">'.CHtml::image(Yii::app()->getBaseUrl(false).'/images/vendido.png','',
																				 array('style'=>'width: 5px;hight:5px;')).'</w>';
						}else
						{
							$imprimir .= ' <br/><span style="color:#f20000;">Vendido</span>';
						}
					}

				$imprimir.='</div>';
			if($contador==6)
			{
				//$imprimir .='</tr>';
				$contador=0;
			}

			$this->mostrandoImagen($value);

		}

		$imprimir .= '</div>';

		
		$this->render('resultados', array('resultados'=>$imprimir,'subasta'=>$ultimaSubastaSilenciosa,'imagenesDir'=>$this->imagenesDir));
	}

	public function mostrandoImagen($imagen){


			$this->widget('ext.fancybox.EFancyBox', array(
					   	 'target'=>'a.des_'.$imagen->id ,
					   	 'config'=>array('scrolling'=>'no',
					   	 				 'fitToView'=>true,
					   	 				 'aspectRatio'=>true,
					   	 				 'title'=>'<p>'.$imagen->descri.'</p>',
					   	 				 'href'=> $this->imagenesDir.$imagen->imageng,
					   	 				  'helpers' =>array('title'=>array('type'=>'inside'))),
			));
			/*Yii::app()->clientScript->registerScript( 'fancybox','
			$("a.des_'.$imagen->id.'").fancybox({
					"scrolling" : "no",
					"fitToView": true,
					"aspectRatio": true,
					"href": "'.Yii::app()->request->baseUrl.'/'.$imagen->imageng.'",
					"title" : "\'<p>'.$imagen->descri.'</p>\'",					
												 
				});
			' );*/

			Yii::app()->clientScript->registerScript( 'fancybox-position','
			$(".fancybox").fancybox({
			    helpers:  {
			        title : {
			            type : \'inside\'
			        },
			        overlay : {
			            showEarly : false
			        }
			    }
			});' , CClientScript::POS_END);


 
			//return '<a style="display:none" id="data_'.$imagen->id.'" href="'.Yii::app()->request->baseUrl.'/'.$imagen->imageng.'" rel="gallery"><img src="'.Yii::app()->request->baseUrl.'/'.$imagen->imageng.'"></img>'.'</a>';
	}


	public function validarImagenid($id){
		$imagen = ImagenS::model()->findByPk($id);
		if($imagen)
			if(Subastas::model()->findByPk($imagen->ids)->silenciosa){
				return $imagen;
			}else
				throw new Exception("Error Processing Request: Subasta inactiva" , 1);
		else
			throw new Exception("Error Processing Request: error id image", 1);
			
	}


	public function actionImagen()
	{
		if(isset($_POST['idimagen']))
		{
			$imagen = $this->validarImagenid($_POST['idimagen']);
			echo CHtml::image($this->imagenesDir.$imagen->imageng).'<p>'.$imagen->descri.'</p>';
		}else
			throw new Exception("Error Processing Request: id not found", 1);
		
	}

	//Acción para el refresco de los elementos, como precios y carrito
	public function actionBuscar()
	{


		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		//$this->render('index');

		//$connection Yii::app()->db;
		//$connection->query("SELECT * FROM imagen_s WHERE ");

		$criteria = new CDbCriteria;

		$criteria->condition = 'silenciosa=:silenciosa';
		$criteria->params = array(':silenciosa'=>1);

		$subas = Subastas::model()->find($criteria);


		$criteria = new CDbCriteria;

		$criteria->condition = 'ids=:ids';
		//$criteria->select = 'id, actual, id_usuario';
		$criteria->params = array(':ids'=>$subas['id']);

		$query = ImagenS::model()->findAll($criteria);

		$res = array();
		foreach ($query as $key => $value) {
			$criteria = new CDbCriteria;

			$criteria->condition = 'idusuario=:idusuario && idsubasta=:idsubasta';
			$criteria->select = 'paleta';
			$criteria->params = array(':idusuario'=>$value->id_usuario, ':idsubasta'=> $subas->id);

			$resultado= Usuariospujas::model()->find($criteria);

			if($resultado){
				// Verificando si es primera puja
				$imgConPujas = RegistroPujas::model()->find('id_imagen_s=:imagen',
				array(
				  ':imagen'=>$value->id,
				));

				if($imgConPujas)
					// Puja siguiente
					$siguiente = $value->actual*1.1;	// se icrementa el valor de la imagen por 10%
				else
					$siguiente = $value->base;	

				if(Yii::app()->session['admin'])
					$res[] =  array('id'=>$value->id,'paleta'=>$resultado['paleta'], 'actual'=>$value->actual, 'siguiente'=>$siguiente);	
				else{
					//$res[] =  array('id'=>$value->id, 'actual'=>number_format($value->actual));
					if (!Yii::app()->session['id_usuario']) {
						$res[] =  array('id'=>$value->id, 'actual'=>$value->actual, 'siguiente'=>$siguiente);
					}else
					{
						$usuarioPM = RegistroPujas::model()->find('id_imagen_s=:imagen AND verificado=:verificado AND idusuario=:idusuario ORDER BY fecha',
							array(
							  ':imagen'=>$value->id,
							  ':verificado'=>1,
							  'idusuario'=>$value->id_usuario,
							  //':maxi' => NULL,
							));
						$etiqueta = 'Pujar';
						if($value->id_usuario == Yii::app()->session['id_usuario'] && !$usuarioPM)
							$etiqueta = 'Realizar puja máxima';
						if($value->id_usuario == Yii::app()->session['id_usuario'] && $usuarioPM)
							$etiqueta = '(modificar puja máxima)';
						$pujarAjaxLink = CHtml::ajaxLink($etiqueta,
			       										$this->createUrl('site/pujar'), array(
											            //'onclick'=>'$("#pujaModal").dialog("open"); return false;',
											            //'update'=>'#pujaModal'
											            'type'=>'POST',
											            'data' => array('imagen_s'=> '0' ),
											            'context'=>'js:this',
											            'beforeSend'=>'function(xhr,settings){
											            						settings.data = encodeURIComponent(\'imagen_s\')
										          								+ \'=\'
										          								+ encodeURIComponent($(this).attr(\'id\'));
											            }',
											            'success'=>'function(r){$("#pujaModal").html(r).dialog("open"); return false;}'
											        ),
											        array('id'=>$value->id, 'style'=>'color: #014F92;')
												);
						if($value->id_usuario == Yii::app()->session['id_usuario']){ // Si la imágen pertenece al usuario actual


							$proxPuja = '<br>Prox. Puja: <pujasiguienteafterlink><moneda>'.$subas->moneda.'</moneda> 
						  	<siguientei_'.$value->id.'>'.number_format($siguiente).'</siguientei_'.$value->id.'><pujasiguienteafterlink>';
							$hastaPM = $proxPuja;
							if($usuarioPM)
								$hastaPM .= '<span style="color: red;"><p> Puja máxima: <moneda>'.$subas['moneda'].'</moneda> '.number_format($usuarioPM->maximo_dispuesto).'</p></span>';
							else
								$hastaPM .= '<br>';
							$res[] =  array('id'=>$value->id, 'actual'=>$value->actual, 'siguiente'=>$siguiente,
								'div'=>CHtml::image(Yii::app()->getBaseUrl(false).'/images/vendido.png','',array('style'=>'width: 5px;hight:5px;')).$hastaPM.' '.$pujarAjaxLink);
						}else
						{
							$pujaSiguiente = ' <pujasiguienteafterlink><moneda>'.$subas->moneda.'</moneda> 
											  <siguientei_'.$value->id.'>'.number_format($siguiente).'</siguientei_'.$value->id.'><pujasiguienteafterlink>';
							$res[] =  array('id'=>$value->id, 'actual'=>$value->actual, 'siguiente'=>$siguiente,
								'div'=>$pujarAjaxLink.$pujaSiguiente );
						}
					}
				}
				// number_format($value->actual,0,'.','') // entero sin coma
			}
		}
		
		//$criteria = new CDbCriteria;

		//$criteria->condition = 'id_usuario=:id_usuario AND verificado=:verificado AND ids=:ids';
		//$criteria->params = array(':id_usuario'=>Yii::app()->session['id_usuario'], ':ids'=>$subas->id,':verificado'=>2);
		//s$criteria->order = 'id_imagen_s DESC';
		//$criteria->select = "id_imagen_s";
		//$criteria->distinct =true;

		//$pujasPerdidas = RegistroPujas::model()->find($criteria);

		$carrito = '';
/*
		foreach ($pujasPerdidas as $key => $pujaPerdida) 
			$mispujasPerdidas[] = ImagenS::model()->find('id=:id AND id_usuario!=:id_usuario', array(':id'=>$pujaPerdida->id_imagen_s, ':id_usuario' => Yii::app()->session['id_usuario']));

		if($mispujasPerdidas)
			foreach ($mispujasPerdidas as $key => $puja) {
				$carrito .= '<div id="vsidebar"><img src="'.Yii::app()->params['imagenesDir'].$puja->imagen.'"></img><br><span style="color: red;">
							'.$puja->solonombre.'</span><p><strong>Puja superada</strong></p></div>';
							//Actual: <moneda>'.$subasta->moneda.'</moneda> <cantidadd_'.$puja->id.'>'.number_format($puja->actual).'</cantidadd_'.$puja->id.'></span></div>';
			}
*/


		$mispujas = ImagenS::model()->findAll('ids=:ids AND id_usuario=:id_usuario', array(':ids'=>$subas->id, ':id_usuario' => Yii::app()->session['id_usuario']));
		if($mispujas)
			foreach ($mispujas as $key => $puja) {
				$carrito .= '<div id="vsidebar"><img src="'.Yii::app()->params['imagenesDir'].$puja->imagen.'"></img><br><span style="color: red;">
							'.$puja->solonombre.'</span><p>Actual: <moneda>'.$subas->moneda.'</moneda> '.number_format($puja->actual).'</p></div>';
							//Actual: <moneda>'.$subasta->moneda.'</moneda> <cantidadd_'.$puja->id.'>'.number_format($puja->actual).'</cantidadd_'.$puja->id.'></span></div>';
			}
		else
			$carrito = 'No ha realizado ninguna puja';

		$res[] = array('carrito' =>  $carrito);
		//print_r($res);

		echo json_encode($res);
		exit();
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		$modal = false;

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];

			$modal = $model->modal;
			if($modal)
				$this->layout='//layouts/column2';
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				if(!$modal)
					$this->redirect(Yii::app()->user->returnUrl);
				else
				{
					echo json_encode(array('id'=>1,'success'=>true,'msg'=>'Login correcto'));
					return;
				}
		}
		
		if(isset($_POST['modal'])){
			$this->layout='//layouts/column2';
			$modal = true;
		}
		// display the login form
		$this->render('login',array('model'=>$model,'modal'=>$modal)); 
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		if(isset(Yii::app()->request->cookies['uc']) || isset(Yii::app()->request->cookies['uc'])){
			unset(Yii::app()->request->cookies['uc']);
			unset(Yii::app()->request->cookies['up']);
		}
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	public function actionPujaradmin()
	{
		$model = new RegistroPujas;

		$imagen = new ImagenS;

		
		if(Yii::app()->session['admin'])
		{

				$this->layout='//layouts/pujaadmin';

				

				$subas = Subastas::model()->find('silenciosa=:silenciosa',array(':silenciosa'=>1));


				$usuarios_puja = Usuariospujas::model()->findAll('idsubasta=:subasta', array(':subasta'=>$subas['id']));

				foreach ($usuarios_puja as $key => $value) {

					$usuarios = Usuarios::model()->find('id=:usuario',array(':usuario'=>$value->idusuario));

					$arreglo[$usuarios['email']] = $usuarios['email'];

					//echo $usuarios['email'].'<br>';

				}

					
				if(isset($_POST['RegistroPujas']))
				{
					$model->attributes=$_POST['RegistroPujas'];

					if(isset($_POST['ImagenS']))
						$imagen->attributes = $_POST['ImagenS'];

					$imagen_modelo = ImagenS::model()->findByPk($model->id_imagen_s);
			
					if($imagen_modelo->puja_indefinida == 1 && $imagen->puja_indefinida == 1)
						throw new Exception("Error Processing Request: Puja Ilimitada ya existente", 1);


						
					if($imagen->puja_indefinida == 1 && $imagen_modelo->puja_indefinida == 0){
						$usuario_actual = Yii::app()->session['admin'];
						$model->codigo = 0;
						$model->paleta = 0;
						$imagen_modelo->puja_indefinida = 1;
					}
					else
					{
						$usuario_actual = Usuarios::model()->find('email=:correo',array(':correo'=>$model->correo))['id'];

						$upc = Usuariospujas::model()->find('idusuario=:idusuario && idsubasta=:idsubasta', array('idusuario'=> $usuario_actual, ':idsubasta'=>$subas->id));

						if($upc){
							$model->codigo = $upc->codigo;
							$model->paleta = $upc->paleta;
						}else
						throw new Exception("Error Processing Request: Recuperando datos del usuario ", 1);
					} 	

	    			$subasta = Subastas::model()->findByPk($imagen_modelo->ids);

					if($model->validate())
					{
	        			$model->ids = $subasta->id;

	       				$this->validaciones($model, $imagen_modelo, $subasta, $usuario_actual);
	       				return;
	   				}
	   			}
	       		//}
	       		//else{
	       			//echo json_encode(array('id'=>0,'success'=>false,'msg'=>'No se ha recibido correo.'));
					//$model->maximo_dispuesto = 0;
					$this->render('pujaradmin', array('usuarios' => $arreglo,'model'=>$model));
				//}


		}else
		{
		//header ("location: http://localhost/odalys/admin/_adminIndex.php");

		}


	}


	public function actionPujar()
	{
	    $model=new RegistroPujas;



	    // uncomment the following code to enable ajax-based validation
	    
	    /*if(isset($_POST['ajax']) && $_POST['ajax']==='registro-pujas-pujar-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }*/
	   
		
		if(@$_POST['datosDeControl']==1)
		{
			echo $_POST['correo'];

		}


	    if(isset($_POST['RegistroPujas'])){
	        $model->attributes=$_POST['RegistroPujas'];

	    	if(!$model->paleta  && isset(Yii::app()->request->cookies['up']))
	    		$model->paleta = 0;
			if(!$model->codigo && isset(Yii::app()->request->cookies['uc']))
	    		$model->codigo = 0;
	    	//$auxidimagen = $model->id_imagen_s;
	        if($model->validate($model,array('maximo_dispuesto','paleta','codigo')))
	        { 	         // form inputs are valid, do something here		
				$usuario_actual = Yii::app()->session['id_usuario'];

				$imagen_modelo = ImagenS::model()->findByPk($model->id_imagen_s);
	    		$subasta = Subastas::model()->findByPk($imagen_modelo->ids);
	    		$criteria = new CDbCriteria();

	    		$criteria->condition = 'idsubasta=:idsubasta AND idusuario=:idusuario';
	    		$criteria->params = array(':idsubasta'=>$subasta->id, ':idusuario'=>$usuario_actual);

				$upc = Usuariospujas::model()->find('idsubasta=:idsubasta AND idusuario=:idusuario',
													array(':idsubasta'=>$subasta->id, ':idusuario'=>$usuario_actual));

				
				$paletaYcodigoVerificado = false;


				if( !Yii::app()->request->cookies['up'] && !Yii::app()->request->cookies['uc'])
				{
					//Introdujo codigo y paleta por primera vez
					if( strtoupper($model->codigo) == $upc->codigo && $model->paleta == $upc->paleta)
					{

						Yii::app()->request->cookies['up'] = new CHttpCookie('up', md5($upc['paleta']));
						Yii::app()->request->cookies['uc'] = new CHttpCookie('uc', md5($upc['codigo']));

						$paletaYcodigoVerificado = true;
					}else
					{
						echo json_encode(array('id'=>1,'success'=>true,'msg'=>'Error en el código o la paleta.'));
						return;
					}

				}else{
				
					// Verificando que el codigo y paleta almacenados en cookie sean las correctas.
					if(Yii::app()->request->cookies['uc']->value == md5($upc->codigo)
						&& Yii::app()->request->cookies['up']->value == md5($upc->paleta))
					{
						$paletaYcodigoVerificado = true;

					}else{
						// La cookie no corresponde
						unset(Yii::app()->request->cookies['uc']);
						unset(Yii::app()->request->cookies['up']);
						echo json_encode(array('id'=>1,'success'=>false,'msg'=>'Se ha detectado una falla de seguridad, introduzca de nuevo su paleta y codigo.'));
						return;
					}
				}

				if($paletaYcodigoVerificado)
				{

				   	//Aqui se va a verificar el monto maximo de la puja y hacer todo lo relacionado con la puja

		        	// si el usuario va ganando la puja
		        	/*if($imagen_modelo->id_usuario == Yii::app()->session['id_usuario'] && !$model->maximo_dispuesto)	{
		        		echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'La última puja por esta obra es suya '.Yii::app()->session['nombre_usuario'].' '.Yii::app()->session['apellido_usuario'].'.'));
		        		//echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Debe introducir una puja máxima para realizar la modificación.'));
		        	}else*/if($subasta->silenciosa) //subasta silenciosa
		        	{


		        		$model->ids = $subasta->id;
		        		$model->idusuario = $usuario_actual;


           				$this->validaciones($model, $imagen_modelo, $subasta, $usuario_actual);

		        	}else{
		        		echo json_encode(array('id'=>1,'success'=>true,'msg'=>'La subasta correspondiente a la imagen recibida no es silenciosa'));
		        	}

				}

	            return;
	        }	//Fin de validate
	       //$model->id_imagen_s = $auxidimagen;

	    } 
	    //$model->maximo_dispuesto = 0;
	    $this->layout = '//layouts/modal';
	    $this->render('pujar',array('model'=>$model));
	}
	

	function validaciones($model, $imagen_modelo, $subasta, $usuario_actual){
/*
$transaction = Yii::app()->db->beginTransaction();
try {

	$transaction->commit();
} catch (Exception $e) {
	$transaction->rollBack();
	throw new CHttpException(null,"No se pudo completar la transacción.", $e->getMessage());
}*/

//Una de las restricciones, es que el usuario que puja no puede ser el mismo que el que ya posee la imágen, en tal caso de que esto ocurra, es para modificación de la puja maxima
				if($usuario_actual  === $imagen_modelo->id_usuario)
				{
					if($model->maximo_dispuesto)
					{ 
	
	        			$puja_siguiente = $imagen_modelo->actual*1.1;
							
							//aqui va puja maxima
	        			if($model->maximo_dispuesto >= $puja_siguiente)
	        			{
							$transaction = $model->dbConnection->beginTransaction();// o  = Yii::app()->db->beginTransaction();	
							try {
								
								//$imagen_modelo->actual = $model->maximo_dispuesto;

								// Ultimo registro de este usuario con esta imagen y verificado 1
								$registro = RegistroPujas::model()->find('id_imagen_s=:id_imagen_s AND verificado=:verificado AND idusuario=:idusuario ORDER BY fecha DESC',
																array(
																  ':id_imagen_s'=>$model->id_imagen_s,
																  ':verificado'=>1,
																  ':idusuario' => $usuario_actual,
																));

								$model->idusuario = $usuario_actual;
								$model->monto_puja = intval($imagen_modelo->actual);
								$model->verificado = 1;
								if(!$model->save())
								{
									$msg = print_r($model->getErrors(),1);
									$transaction->rollBack();
									throw new CHttpException(400,'RegistroPujas model: data not saving: '.$msg );
								}

								if($registro)
								{
									//print_r($registro);
									// Se cambia a 2 porque el registro que ya estaba con el monto puja anterior debe quedar registrado
		        					$registro->verificado = 2;
									if(!$registro->update())
		        					{
		        						$transaction->rollBack();
										$msg = print_r($registro->getErrors(),1);
										throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
									}	
								}

								$imagen_modelo->id_usuario = $usuario_actual;
								if(!$imagen_modelo->save()){
									$msg = print_r($imagen_modelo->getErrors(),1);
									$transaction->rollBack();
									throw new CHttpException(400,'ImagenS: data not saving: '.$msg );
								}else
									echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Su puja ha sido modificada.'));

								$transaction->commit();
							} catch (Exception $e) {
								$transaction->rollBack();
								throw new CHttpException(null,"No se pudo completar la transacción.", $e->getMessage());
							}
						}else
	        			{
	        				echo json_encode(array('id'=>1, 'success'=>false,'msg'=>'Puja maxima debe ser mayor a el 10% del precio actual'));
	        			}
					}else
						echo json_encode(array('id'=>1, 'success'=>false,'msg'=>'Debe introducir un monto de puja máxima. La última puja por esta obra es suya, por esto solo puede modificar la puja máxima.'));
				}
				else
				{
					
//Si hay una imagen con puja indefinida se atiende este caso especial ganando siempre el usuario que tiene la puja(que es un admin)
			        		if($model->maximo_dispuesto) 
			        		{
			        			//Puja maxima

		        				// Verificando si es primera puja
		        				$imgConPujas = RegistroPujas::model()->find('id_imagen_s=:imagen',
								array(
								  ':imagen'=>$model->id_imagen_s,
								  //':maxi' => NULL,
								));

		        				if($imgConPujas)
									// Puja siguiente
									$imagen_modelo->actual *= 1.1;	// se icrementa el valor de la imagen por 10%
								else
									$imagen_modelo->actual =  $imagen_modelo->base;

			        			$puja_siguiente = $imagen_modelo->actual;
		 						
		 						//Se verifica primero que el maximo dispuesto sea mayor o igual que la puja siguiente de la imágen
			        			if($model->maximo_dispuesto >= $puja_siguiente)
			        			{




							//El usuario pierde ante la puja ilimitada del usuario actual que posee la imágen
							if($imagen_modelo->puja_indefinida == 1)		
							{

									$imagen_modelo->actual = $model->maximo_dispuesto * 1.1;

									$model->verificado=2;
			        				$model->idusuario = $usuario_actual;
			        				$model->monto_puja = intval($imagen_modelo->actual);
		        					if(!$model->save())
		        					{
										$msg = print_r($model->getErrors(),1);
										throw new CHttpException(400,'RegistroPujas model: data not saving: '.$msg );
									}
									//throw new CHttpException(400,'RegistroPujas model: data not saving: ' );
									$nuevoregistro = new RegistroPujas();
									$nuevoregistro->ids = $imagen_modelo->ids;
									$nuevoregistro->idusuario = $imagen_modelo->id_usuario;
									$nuevoregistro->id_imagen_s = $imagen_modelo->id;
									$nuevoregistro->monto_puja = intval($imagen_modelo->actual);
									$nuevoregistro->paleta = 0;
									$nuevoregistro->codigo = 0;
									if(!$nuevoregistro->save())
		        					{
										$msg = print_r($nuevoregistro->getErrors(),1);
										throw new CHttpException(400,'RegistroPujas nuevoregistro: data not saving: '.$msg );
									}

									$imagen_modelo->id_usuario = $usuario_actual;
									if(!$imagen_modelo->save()){
										$msg = print_r($imagen_modelo->getErrors(),1);
										throw new CHttpException(400,'ImagenS: data not saving: '.$msg );
									}else
										echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Su puja ha sido realizada con éxito pero fue superada, debido a que existe una puja máxima superior de otro postor.'));	

									//Se le manda el correo al que perdio la puja
									list($controlador) = Yii::app()->createController('Mail');
									$controlador->Pujadores($model->idusuario,$imagen_modelo->descri);

									return;
							}

			        				$registro = RegistroPujas::model()->find('id_imagen_s=:imagen AND verificado=:verificado',
									array(
									  ':imagen'=>$model->id_imagen_s,
									  ':verificado'=>1,
									  //':maxi' => NULL,
									));
									//Esto es para que se guarde como nueva fila
									//$registro->setIsNewRecord(true);

			        				// Existe otra puja con maximo dispuesto
			        				if($registro)
			        				{
					        					$registro->paleta = 0;
					        					$registro->codigo = 0;
				        				if($registro->maximo_dispuesto >  $model->maximo_dispuesto)  
				        				{
				        					// Gana el que ya estaba en la base de datos
				        					if($registro->maximo_dispuesto >= $model->maximo_dispuesto*1.1)
				        						$imagen_modelo->actual = $model->maximo_dispuesto * 1.1;
				        					else
				        						$imagen_modelo->actual = $registro->maximo_dispuesto;


				        					//$registro->verificado = 1;
											$imagen_modelo->id_usuario = $registro->idusuario;

											$model->verificado=2;
					        				$model->idusuario = $usuario_actual;
					        				//$model->monto_puja = intval($imagen_modelo->actual);
				        					if(!$model->save())
				        					{
												$msg = print_r($model->getErrors(),1);
												throw new CHttpException(400,'RegistroPujas model: data not saving: '.$msg );
											}


											$nuevoregistro = new RegistroPujas();
											$nuevoregistro->ids = $registro->ids;
											$nuevoregistro->idusuario =$registro->idusuario;
											$nuevoregistro->id_imagen_s =$registro->id_imagen_s;
											$nuevoregistro->monto_puja = intval($imagen_modelo->actual);
											$nuevoregistro->maximo_dispuesto = $registro->maximo_dispuesto;
											$nuevoregistro->verificado = 1;
											$nuevoregistro->paleta = 0;
											$nuevoregistro->codigo = 0;
											if(!$nuevoregistro->save())
				        					{
												$msg = print_r($nuevoregistro->getErrors(),1);
												throw new CHttpException(400,'RegistroPujas nuevoregistro: data not saving: '.$msg );
											}

											// Se cambia a 2 porque el registro que ya estaba con el monto puja anterior debe quedar registrado
				        					$registro->verificado = 2;
											if(!$registro->save())
				        					{
												$msg = print_r($registro->getErrors(),1);
												throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
											}	

											if(!$imagen_modelo->save()){
												$msg = print_r($imagen_modelo->getErrors(),1);
												throw new CHttpException(400,'ImagenS: data not saving: '.$msg );
											}else
												echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Su puja ha sido realizada con éxito pero fue superada, debido a que existe una puja máxima superior de otro postor.'));	

											//Se le manda el correo al que perdio la puja
											list($controlador) = Yii::app()->createController('Mail');
											$controlador->Pujadores($model->idusuario,$imagen_modelo->descri);

				        				}elseif($registro->maximo_dispuesto <  $model->maximo_dispuesto)
					        				{
					        					//Gana el usuario actual
					        					if($model->maximo_dispuesto >= $registro->maximo_dispuesto*1.1)
				        							$imagen_modelo->actual = $registro->maximo_dispuesto * 1.1;
				        						else
				        							$imagen_modelo->actual = $model->maximo_dispuesto;


												$registro->verificado = 2;
	
												if(!$registro->save()){
													$msg = print_r($registro->getErrors(),1);
													throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
												}

												$model->verificado = 1;
					        				 	$model->monto_puja = intval($imagen_modelo->actual);
					        				 	$model->idusuario = $usuario_actual;

					        					if(!$model->save())
					        					{
													$msg = print_r($model->getErrors(),1);
													throw new CHttpException(400,'Registro Pujas data not saving: '.$msg );
												}

												$imagen_modelo->id_usuario = $usuario_actual;
												if(!$imagen_modelo->save()){
													$msg = print_r($imagen_modelo->getErrors(),1);
													throw new CHttpException(400,'ImagenS data not saving: '.$msg );
												}else{
													echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Su puja ha sido exitosa.'));
												}

												//Se le manda el correo al que perdio la puja
												list($controlador) = Yii::app()->createController('Mail');
												$controlador->Pujadores($registro->idusuario,$imagen_modelo->descri);

					        				}else{

					        					// Existe una puja maxima igual, se la gana el que primero haya hecho la puja
												
												$imagen_modelo->id_usuario = $registro->idusuario;
					        					$imagen_modelo->actual = $registro->maximo_dispuesto;
								
												

												$model->verificado=2;
						        				$model->idusuario = $usuario_actual;
						        				$model->monto_puja = intval($imagen_modelo->actual);
					        					if(!$model->save())
					        					{
													$msg = print_r($model->getErrors(),1);
													throw new CHttpException(400,'RegistroPujas model: data not saving: '.$msg );
												}

												// nuevo registro pero con verificado en 1 y monto puja nuevo
												$nuevoregistro = new RegistroPujas();
												$nuevoregistro->ids = $registro->ids;
												$nuevoregistro->idusuario =$registro->idusuario;
												$nuevoregistro->id_imagen_s =$registro->id_imagen_s;
												$nuevoregistro->monto_puja = intval($imagen_modelo->actual);
												$nuevoregistro->maximo_dispuesto = intval($registro->maximo_dispuesto);
												$nuevoregistro->verificado = 1;
												$nuevoregistro->paleta = 0;
												$nuevoregistro->codigo = 0;
												if(!$nuevoregistro->save())
					        					{
													$msg = print_r($nuevoregistro->getErrors(),1);
													throw new CHttpException(400,'RegistroPujas nuevoregistro: data not saving: '.$msg );
												}

												// Se cambia a 2 porque el registro que ya estaba con el monto puja anterior debe quedar registrado
					        					$registro->verificado = 2;
												if(!$registro->save())
					        					{
													$msg = print_r($registro->getErrors(),1);
													throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
												}	

												if(!$imagen_modelo->save()){
													$msg = print_r($imagen_modelo->getErrors(),1);
													throw new CHttpException(400,'ImagenS: data not saving: '.$msg );
												}else
													echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Su puja ha sido realizada con éxito pero fue superada, debido a que existe una puja máxima superior de otro postor.'));	


												//Se le manda el correo al que perdio la puja
												list($controlador) = Yii::app()->createController('Mail');
												$controlador->Pujadores($model->idusuario,$imagen_modelo->descri);
					        				}
				        				
		
				        			}else
				        			{
				        				// No hay otro pujador con puja maxima

										//Verificando si existe algun pujador previo para enviarle el correo de perdida de subasta
										$criteria = new CDbCriteria;

										$criteria->condition = 'ids=:ids && id_imagen_s=:id_imagen_s';
										$criteria->params = array(':ids'=>$subasta->id,':id_imagen_s'=>$imagen_modelo->id);
										$criteria->order = 'fecha DESC';

										$pujaPrevia = RegistroPujas::model()->find($criteria);


				        				// Verificando si es primera puja
				        				$imgConPujas = RegistroPujas::model()->find('id_imagen_s=:imagen',
										array(
										  ':imagen'=>$model->id_imagen_s,
										  //':maxi' => NULL,
										));

				        				if($imgConPujas)
											// Puja siguiente
											$imagen_modelo->actual *= 1.1;	// se icrementa el valor de la imagen por 10%
										else
											$imagen_modelo->actual =  $imagen_modelo->base;


										$model->verificado = 1;
			        					$model->idusuario = $usuario_actual;
			        				 	$model->monto_puja = intval($imagen_modelo->actual);

			        					if(!$model->save())
			        					{
											$msg = print_r($model->getErrors(),1);
											throw new CHttpException(400,'Registro Pujas data not saving: '.$msg );
										}

										$imagen_modelo->id_usuario = $usuario_actual;
										if(!$imagen_modelo->save()){
											$msg = print_r($imagen_modelo->getErrors(),1);
											throw new CHttpException(400,'ImagenS data not saving: '.$msg );
										}else{
											echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Su puja ha sido exitosa.'));
										}


										// Si existe se manda el correo a ese usuario
										if($pujaPrevia)
										{
											list($controlador) = Yii::app()->createController('Mail');
											$controlador->Pujadores($pujaPrevia->idusuario, $imagen_modelo->descri);	
										}

				        			}

				        			//$model->save(true,array('idusuario'=>Yii::app()->session['id_usuario'],));
				        			
			        			}else
			        			{
			        				echo json_encode(array('id'=>1, 'success'=>false,'msg'=>'Puja maxima debe ser mayor a el 10% del precio actual'));
			        			}

			        		}else
			        		{	// Puja simple
		        				$registro = RegistroPujas::model()->find('id_imagen_s=:imagen AND verificado=:verificado',
								array(
								  ':imagen'=>$model->id_imagen_s,
								  ':verificado'=>1,
								  //':maxi' => NULL,
								));

							//El usuario pierde ante la puja ilimitada del usuario actual que posee la imágen
							if($imagen_modelo->puja_indefinida == 1)		
							{

									$imagen_modelo->actual *= 1.1;


									$model->verificado=2;
			        				$model->idusuario = $usuario_actual;
			        				$model->monto_puja = intval($imagen_modelo->actual);
		        					if(!$model->save())
		        					{
										$msg = print_r($model->getErrors(),1);
										throw new CHttpException(400,'RegistroPujas model: data not saving: '.$msg );
									}

									$nuevoregistro = new RegistroPujas();
									$nuevoregistro->ids = $imagen_modelo->ids;
									$nuevoregistro->idusuario = $imagen_modelo->id_usuario;
									$nuevoregistro->id_imagen_s = $imagen_modelo->id;
									$nuevoregistro->monto_puja = intval($imagen_modelo->actual);
									$nuevoregistro->paleta = 0;
									$nuevoregistro->codigo = 0;
									if(!$nuevoregistro->save())
		        					{
										$msg = print_r($nuevoregistro->getErrors(),1);
										throw new CHttpException(400,'RegistroPujas nuevoregistro: data not saving: '.$msg );
									}

									$imagen_modelo->id_usuario = $usuario_actual;
									if(!$imagen_modelo->save()){
										$msg = print_r($imagen_modelo->getErrors(),1);
										throw new CHttpException(400,'ImagenS: data not saving: '.$msg );
									}else
										echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Su puja ha sido realizada con éxito pero fue superada, debido a que existe una puja máxima superior de otro postor.'));	

		        					//Se le manda el correo al que perdio la puja
									list($controlador) = Yii::app()->createController('Mail');
									$controlador->Pujadores($model->idusuario,$imagen_modelo->descri);
				        		return;
							}

									if($registro)
									{ //ya existe un usuario con puja maxima
		        						
		        						// Puja siguiente
										$imagen_modelo->actual *= 1.1;	// se icrementa el valor de la imagen por 10%

										$registro->paleta = 0;
			        					$registro->codigo = 0;

										if($registro->maximo_dispuesto >= $imagen_modelo->actual){

											//Gana el usuario con puja maxima que estaba en la bd
											
											//Esto es para que se guarde como nueva fila
		        							//$registro->setIsNewRecord(false);
			

					        				$model->idusuario = $usuario_actual;
					        				$model->monto_puja = intval($imagen_modelo->actual);
				        					if(!$model->save())
				        					{
												$msg = print_r($model->getErrors(),1);
												throw new CHttpException(400,'RegistroPujas model: data not saving: '.$msg );
											}
											
											// Se incrementa el valor y sigue con la pieza el mismo de maxima puj
											if($registro->maximo_dispuesto >= $imagen_modelo->actual *= 1.1)
												$imagen_modelo->actual *= 1.1;
											else
												$imagen_modelo->actual = $registro->maximo_dispuesto;

											$imagen_modelo->id_usuario = $registro->idusuario;

											$nuevoregistro = new RegistroPujas();
											$nuevoregistro->ids = $registro->ids;
											$nuevoregistro->idusuario =$registro->idusuario;
											$nuevoregistro->id_imagen_s =$registro->id_imagen_s;
											$nuevoregistro->monto_puja = intval($imagen_modelo->actual);
											$nuevoregistro->maximo_dispuesto = $registro->maximo_dispuesto;
											$nuevoregistro->verificado = 1;
											$nuevoregistro->paleta = 0;
											$nuevoregistro->codigo = 0;
											if(!$nuevoregistro->save())
				        					{
												$msg = print_r($nuevoregistro->getErrors(),1);
												throw new CHttpException(400,'RegistroPujas nuevoregistro: data not saving: '.$msg );
											}

											// Se cambia a 2 porque el registro que ya estaba con el monto puja anterior debe quedar registrado
				        					$registro->verificado = 2;
											if(!$registro->save())
				        					{
												$msg = print_r($registro->getErrors(),1);
												throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
											}	

											if(!$imagen_modelo->save()){
												$msg = print_r($imagen_modelo->getErrors(),1);
												throw new CHttpException(400,'ImagenS: data not saving: '.$msg );
											}else
												echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Su puja ha sido realizada con éxito pero fue superada, debido a que existe una puja máxima superior de otro postor.'));

											//Se le manda el correo al que perdio la puja
											list($controlador) = Yii::app()->createController('Mail');
											$controlador->Pujadores($model->idusuario,$imagen_modelo->descri);

										}else{

											// Gana el usuario con puja simple
											
											//Esto es para que se guarde como nueva fila
		        							//$registro->setIsNewRecord(false);

										
					        				$model->idusuario = $usuario_actual;
					        				$model->monto_puja = intval($imagen_modelo->actual);
				        					if(!$model->save())
				        					{
												$msg = print_r($model->getErrors(),1);
												throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
											}

											$registro->verificado = 2;
											if(!$registro->save())
				        					{
												$msg = print_r($registro->getErrors(),1);
												throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
											}			

											$imagen_modelo->id_usuario = $usuario_actual;
											if(!$imagen_modelo->save()){
												$msg = print_r($imagen_modelo->getErrors(),1);
												throw new CHttpException(400,'ImagenS: data not saving: '.$msg );
											}else{
												echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Su puja ha sido exitosa.'));
											}	

											//Se le manda el correo al que perdio la puja
											list($controlador) = Yii::app()->createController('Mail');
											$controlador->Pujadores($registro->idusuario,$imagen_modelo->descri);									
										}
				
										


									}else{

										// El usuario puja simple

										//Verificando si es primera puja
				        				$imgConPujas = RegistroPujas::model()->find('id_imagen_s=:imagen',
										array(
										  ':imagen'=>$model->id_imagen_s,
										  //':maxi' => NULL,
										));

				        				if($imgConPujas)
											// Puja siguiente
											$imagen_modelo->actual *= 1.1;	// se icrementa el valor de la imagen por 10%
										else
											$imagen_modelo->actual =  $imagen_modelo->base;


					        			//$model->save(true,array('idusuario'=>Yii::app()->session['id_usuario'],));

										//Verificando si existe algun pujador previo para enviarle el correo de perdida de subasta
										$criteria = new CDbCriteria;

										$criteria->condition = 'ids=:ids && id_imagen_s=:id_imagen_s';
										$criteria->params = array(':ids'=>$subasta->id,':id_imagen_s'=>$imagen_modelo->id);
										$criteria->order = 'fecha DESC';

										$pujaPrevia = RegistroPujas::model()->find($criteria);

										// Insertando en registro_pujas (historial de pujas)
					        			$model->idusuario = $usuario_actual;
				        				$model->monto_puja = intval($imagen_modelo->actual);
			        					if(!$model->save())
			        					{
											$msg = print_r($model->getErrors(),1);
											throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
										}

										// Guardando en imagen_s (actualizando el id del usuario)
										$imagen_modelo->id_usuario = $usuario_actual;
										if(!$imagen_modelo->save()){
											$msg = print_r($imagen_modelo->getErrors(),1);
											throw new CHttpException(400,'ImagenS: data not saving: '.$msg );
										}else{
											echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Su puja ha sido exitosa.'));
										}

										//Si existe se le manda el correo a ese usuario
										if($pujaPrevia)
										{
											list($controlador) = Yii::app()->createController('Mail');
											$controlador->Pujadores($pujaPrevia->idusuario, $imagen_modelo->descri);	
										}
									}

							
			        		}
				}
		}

} //Cierra la clase
