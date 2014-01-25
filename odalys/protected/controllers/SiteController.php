<?php

class SiteController extends Controller
{

	//public $layout='//layouts/column2';
	/**
	 * Declares class-based actions.
	 */
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

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
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
		$this->render('index', array('imprimir'=>$imprimir));

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


		$contador = 0;
		$con = 0;
		$imprimir ="";
		//echo '<table width="80%"><tr>';
		$fancyElements = '';
		$imprimir = '<table width="100%" class="tablaresultado">';
		foreach ($query as $key => $value) {
			$con ++;

			$resultado= Usuariospujas::model()->find('idusuario=:idusuario',array(':idusuario'=>$value->id_usuario));
			
			

			/*$ajaxLink = CHtml::ajaxLink(CHtml::image($value->imagen,'',array('onError'=>'this.onerror=null;this.src=\'images/3ba.jpg\';')),'?r=site/imagen',array(
																	'type'=>'POST',
																	'dataType' => "html",
																	'data' => array('idimagen'=>$value->id),//'{imagen_ss: "0"}',
																	'context'=>'js:this',
																	'success'=> 'function(data){
																			$("#data_'.$value->id.'").empty();
																			$("#data_'.$value->id.'").html(data);
																			//$(".data_'.$value->id.'").fancybox();
																	}',
															       ), array('id'=> 'des_'.$value->id,'class'=>'iframe', 'rel'=>'gallery','href'=> '#data_'.$value->id));*/
			//cambiar a *********ADMIN******
			$imagenElement = CHtml::image('','',array('data-original'=>$value->imagen, 'class'=>'lazy', 'onError'=>'this.onerror=null;this.src=\'images/3ba.jpg\';', 'width'=>'auto','height'=>'auto'));
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
								        array('id'=>'admin_'.$value->id)
									);
			else//if(Yii::app()->session['id_usuario'])
				$link = CHtml::link($imagenElement, '', array('class'=> 'des_'.$value->id,'rel'=>'gallery'));
				//height="480"
			// Fancybox
			$this->mostrandoImagen($value);

			if($contador==6)
			{
				//echo '<tr>';
				
				$imprimir .= '<tr align="center" valign="bottom">';
			}
				$contador++;
				if($resultado)
				{
					
					//echo '<td><img src="images/3ba.jpg"><br/>'.$con.'<div id="imagen_'.$value->id.'">Paleta : '.$resultado['paleta'].'<br/>Precio : '.$value->actual.'</div><a href="?r=site/pujar">Pujar</a></td>';
					$imprimir .='<td align="center" valign="bottom">'.$link.' <br/>'.$con.'<div id="imagen_'.$value->id.'">';
					if(Yii::app()->session['admin'])
						$imprimir .='Paleta : '.$resultado['paleta'].'<br/>Precio : '.number_format($value->actual).'</div>';
					else
						$imprimir .= 'Precio : '.number_format($value->actual).'</div>';
					
					// number_format($value->actual,0,'.','') // entero sin coma
					// '.$value->imagen.'						//imagen pequeña

				}else
				{
					//echo '<td><img src="images/3ba.jpg" onclick="$(\'#pujaModal\').dialog(\'open\'); return false;"><br/>'.$con.'<div id="imagen_'.$value->id.'">Precio : '.$value->actual.'</div><a href="?r=site/pujar">Pujar</a></td>';
					$imprimir .='<td align="center" valign="bottom">'.$link.'<br/>'.$con.'<div id="imagen_'.$value->id.'">Precio : '.number_format($value->actual).'</div>';
					
					// number_format($value->actual,0,'.','') // entero sin coma
				}

				if(Yii::app()->session['id_usuario'])
					if(!($value->id_usuario == Yii::app()->session['id_usuario']))
					{
					
						$pujarAjaxLink = CHtml::ajaxLink('Pujar',
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
											        array('id'=>$value->id)
												);
						$imprimir .= $pujarAjaxLink.'<BR/></td>';
					}
					else
						$imprimir .= '<p style="color:red;font-size:100%;">.</p></td>';
				else
				{
					$pujarAjaxLink = CHtml::ajaxLink('Pujar',
		        	$this->createUrl('site/login'), array(
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
										        array('id'=>$value->id)
											);
					$imprimir .= $pujarAjaxLink.'<BR/></td>';					
				}

			if($contador==6)
			{
				//echo '</tr>';
				$imprimir .='</tr>';
				$contador=0;
			}

		}
		return $imprimir .='</table>'.$fancyElements;

	}

	public function actionResultados()
	{

		$criteria = new CDbCriteria;

		$criteria->condition = 'fuesilenciosa=:fuesilenciosa';
		$criteria->params = array(':fuesilenciosa'=>1);
		$criteria->order = 'id DESC';

		$resultados = Subastas::model()->find($criteria);


		$query = ImagenS::model()->findAll('ids=:ids',array(':ids'=>$resultados['id']));


		$contador = 0;
		$con = 0;
		$fancyElements = $imprimir = "";
		//echo '<table width="80%"><tr>';
		$imprimir = '<table width="100%" class="tablaresultado"><tr>';
		foreach ($query as $key => $value) {
			$con ++;
			
			$link = CHtml::link(CHtml::image($value->imagen,'',array('onError'=>'this.onerror=null;this.src=\'images/3ba.jpg\';')),'', array('class'=> 'des_'.$value->id,'rel'=>'gallery'));
			
			if($contador==6)
			{
				$imprimir .= '<tr align="center" valign="bottom">';
			}
				$contador++;
				if($value->id_usuario)
				{
					
					$imprimir .=  '<td align="center" valign="bottom">'.$link.'<br>'.$con.' <span style="color:#f20000;">Vendido</span></td>';

				}else
				{
					$imprimir .= '<td align="center" valign="bottom">'.$link.'<br>'.$con.'</td>';
				}

			if($contador==6)
			{
				$imprimir .='</tr>';
				$contador=0;
			}

			$this->mostrandoImagen($value);

		}
		$imprimir .='</table>';
		
		$this->render('resultados', array('resultados'=>$imprimir));
	}

	public function mostrandoImagen($imagen){


			$this->widget('ext.fancybox.EFancyBox', array(
					   	 'target'=>'a.des_'.$imagen->id ,
					   	 'config'=>array('scrolling'=>'no',
					   	 				 'fitToView'=>true,
					   	 				 'aspectRatio'=>true,
					   	 				 'title'=>'<p>'.$imagen->descri.'</p>',
					   	 				 'href'=> Yii::app()->request->baseUrl.'/'.$imagen->imageng,
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
			echo CHtml::image($imagen->imageng).'<p>'.$imagen->descri.'</p>';
		}else
			throw new Exception("Error Processing Request: id not found", 1);
		
	}

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
		$criteria->select = 'id, actual, id_usuario';
		$criteria->params = array(':ids'=>$subas['id']);

		$query = ImagenS::model()->findAll($criteria);

		//echo $query;

		$res = array();
		foreach ($query as $key => $value) {
			$criteria = new CDbCriteria;

			$criteria->condition = 'idusuario=:idusuario';
			$criteria->select = 'paleta';
			$criteria->params = array(':idusuario'=>$value->id_usuario);

			$resultado= Usuariospujas::model()->find($criteria);

			if($resultado){
				if(Yii::app()->session['admin'])
					$res[] =  array('id'=>$value->id,'paleta'=>$resultado['paleta'], 'actual'=>number_format($value->actual));	
				else
					$res[] =  array('id'=>$value->id, 'actual'=>number_format($value->actual));
				// number_format($value->actual,0,'.','') // entero sin coma
			}
		}
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

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model)); 
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
		$model=new RegistroPujas;

		// NOTA CAMBIAR A ************************** ADMIN ************************** CUANDO TERMINE DE TRABAJARSE
		if(Yii::app()->session['id_usuario']){


			$this->layout='//layouts/pujaadmin';

			

			$subas = Subastas::model()->find('silenciosa=:silenciosa',array(':silenciosa'=>1));


			$usuarios_puja = Usuariospujas::model()->findAll('idsubasta=:subasta', array(':subasta'=>$subas['id']));

			foreach ($usuarios_puja as $key => $value) {

				$usuarios = Usuarios::model()->find('id=:usuario',array(':usuario'=>$value->idusuario));

				$arreglo[$usuarios['email']] = $usuarios['email'];

				//echo $usuarios['email'].'<br>';

			}


					//if(isset($_POST['correo']))
					//{
						//$model->correo = $_POST['correo'];

						
					
 						if(isset($_POST['RegistroPujas'])){
 							$model->attributes=$_POST['RegistroPujas'];

 							$usuario_actual = Usuarios::model()->find('email=:correo',array(':correo'=>$model->correo))['id'];

							$upc = Usuariospujas::model()->find('idusuario=:idusuario',array('idusuario'=> $usuario_actual));

							if($upc){
								$model->codigo = $upc->codigo;
								$model->paleta = $upc->paleta;
							}else
							throw new Exception("Error Processing Request: Error recuperando datos del usuario ", 1);
						


							$imagen_modelo = ImagenS::model()->findByPk($model->id_imagen_s);
			    			$subasta = Subastas::model()->findByPk($imagen_modelo->ids);

 							if($model->validate()){
	
			        			$model->ids = $subasta->id;
				        		$imagen_modelo->id_usuario = $upc->idusuario;


		           				$this->validaciones($model, $imagen_modelo, $subasta, $usuario_actual);
		           				return;
	           				}
	           			}
	           		//}
	           		//else{
					//$contenido = //$this->listaImagen($subas);
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


	    	if(!$model->paleta  && isset(Yii::app()->request->cookies['up']) )
	    		$model->paleta = 0;
			if(!$model->codigo && isset(Yii::app()->request->cookies['uc']))
	    		$model->codigo = 0;
	        if($model->validate())
	        { 	         // form inputs are valid, do something here		

				$imagen_modelo = ImagenS::model()->findByPk($model->id_imagen_s);
	    		$subasta = Subastas::model()->findByPk($imagen_modelo->ids);
				$upc = Usuariospujas::model()->find('idsubasta=:idsubasta AND idusuario=:idusuario',array(':idsubasta'=>$subasta->id, ':idusuario'=>Yii::app()->session['id_usuario']));
				
				$usuario_actual = Yii::app()->session['id_usuario'];

				if( !isset(Yii::app()->request->cookies['up']) && !isset(Yii::app()->request->cookies['uc']))
				{
					//Introdujo codigo y paleta por primera vez
					if( $model->codigo == $upc->codigo && $model->paleta == $upc->paleta)
					{

						Yii::app()->request->cookies['up'] = new CHttpCookie('up', md5($upc['paleta']));
						Yii::app()->request->cookies['uc'] = new CHttpCookie('uc', md5($upc['codigo']));

		        	//Aqui se va a verificar el monto maximo de la puja y hacer todo lo relacionado con la puja
			        	//if($model->id_imagen_s == 4593)

			        	// si el usuario va ganando la puja
			        	if($imagen_modelo->id_usuario == Yii::app()->session['id_usuario'])	{
			        		echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Estas a la cabeza en esta subasta '.Yii::app()->session['nombre_usuario'].' '.Yii::app()->session['apellido_usuario']));
			        	}elseif($subasta->silenciosa) //subasta silenciosa
			        	{

			        		$model->ids = $subasta->id;
			        		$imagen_modelo->id_usuario = Yii::app()->session['id_usuario'];


	           				$this->validaciones($model, $imagen_modelo, $subasta, $usuario_actual);

				        	//$_SESSION['admin']	//caso especial
				        	
				        	//$_SESSION['id_usuario']
			        		


			        		//ImagenS::model()->updateByPk($imagenId,array('actual'=>$model->maximo_dispuesto));

			        		//Hay que hacer un trigger en la bd que al actualizar el maximo_dispuesto de la tabla registro_pujas,
			        		//actualice el monto actual de la imagen_s correspondiente a ese registro, al minimo valor de puja siguiente (tomando
			        		// en cuenta los maximos_dispuestos de los otros usuarios que hayan de esa imagen_s) y que se genere 
			        		//el aviso para enviar el correo al usuario que ha sido superado en la puja

			        	}else{
			        		echo json_encode(array('id'=>1,'success'=>false,'msg'=>'La subasta correspondiente a la imagen recibida no es silenciosa'));
			        	}

					}else
					{
						echo json_encode(array('id'=>1,'success'=>false,'msg'=>'Error en el codigo o la paleta.'));
					}

				}else{
					// Verificando que el codigo y paleta almacenados en cookie sean las correctas.
					if(Yii::app()->request->cookies['uc']->value == md5(Usuariospujas::model()->find('idsubasta=:idsubasta',array(':idsubasta'=>$subasta->id))['codigo'])
						&& Yii::app()->request->cookies['up']->value == md5(Usuariospujas::model()->find('idsubasta=:idsubasta',array(':idsubasta'=>$subasta->id))['paleta']))
					{

			        	//Aqui se va a verificar el monto maximo de la puja y hacer todo lo relacionado con la puja
			        	//if($model->id_imagen_s == 4593)

			        	// si el usuario va ganando la puja
			        	if($imagen_modelo->id_usuario == Yii::app()->session['id_usuario'])	{
			        		echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Estas a la cabeza en esta subasta '.Yii::app()->session['nombre_usuario'].' '.Yii::app()->session['apellido_usuario']));
			        	}elseif($subasta->silenciosa) //subasta silenciosa
			        	{

			        		$model->ids = $subasta->id;
			        		$imagen_modelo->id_usuario = Yii::app()->session['id_usuario'];


	           				$this->validaciones($model, $imagen_modelo, $subasta, $usuario_actual);

				        	//$_SESSION['admin']	//caso especial
				        	
				        	//$_SESSION['id_usuario']
			        		


			        		//ImagenS::model()->updateByPk($imagenId,array('actual'=>$model->maximo_dispuesto));

			        		//Hay que hacer un trigger en la bd que al actualizar el maximo_dispuesto de la tabla registro_pujas,
			        		//actualice el monto actual de la imagen_s correspondiente a ese registro, al minimo valor de puja siguiente (tomando
			        		// en cuenta los maximos_dispuestos de los otros usuarios que hayan de esa imagen_s) y que se genere 
			        		//el aviso para enviar el correo al usuario que ha sido superado en la puja

			        	}else{
			        		echo json_encode(array('id'=>1,'success'=>false,'msg'=>'La subasta correspondiente a la imagen recibida no es silenciosa'));
			        	}

					}else{
						// La cookie no corresponde
						unset(Yii::app()->request->cookies['uc']);
						unset(Yii::app()->request->cookies['up']);
						echo json_encode(array('id'=>1,'success'=>false,'msg'=>'Se ha detectado una falla de seguridad, introduzca de nuevo su paleta y codigo.'));
					}
				}
	            return;
	        }

	    }
	    //$model->maximo_dispuesto = 0;
	    $this->layout = '//layouts/modal';
	    $this->render('pujar',array('model'=>$model));
	}
	

	function validaciones($model, $imagen_modelo, $subasta, $usuario_actual){

						
			        		if($model->maximo_dispuesto) 
			        		{
			        			//Puja maxima
			        			$monto_minimo_dispuesto = ($imagen_modelo->actual*1.1)*1.1;
		 						
		 						//aqui va puja maxima
			        			if($model->maximo_dispuesto >= $monto_minimo_dispuesto)
			        			{

			        				$registro = RegistroPujas::model()->find('id_imagen_s=:imagen AND verificado=:verificado',
									array(
									  ':imagen'=>$model->id_imagen_s,
									  ':verificado'=>1,
									  //':maxi' => NULL,
									));

			        				// Existe otra pujador con maximo dispuesto
			        				if($registro)
			        				{

				        				if($registro->maximo_dispuesto >  $model->maximo_dispuesto)  
				        				{
				        					// Gana el que ya estaba en la base de datos
				        					$imagen_modelo->actual = $model->maximo_dispuesto * 1.1;

				        					$registro->verificado = 1;

											$model->verificado=2;

				        				}elseif($registro->maximo_dispuesto <  $model->maximo_dispuesto)
				        				{
				        					//Gana el usuario actual
											$imagen_modelo->actual = $registro->maximo_dispuesto * 1.1;

											$registro->verificado = 2;

											$model->verificado=1;


				        				}else{
				        						// Si ya existe una puja maxima igua se la gana el que primero haya hecho la puja

				        					$imagen_modelo->actual = $registro->maximo_dispuesto;
				        					$registro->monto_puja = $imagen_modelo->actual;
											if(!$registro->insert())
				        					{
												$msg = print_r($registro->getErrors(),1);
												throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
											}else{

												echo json_encode(array('id'=>1, 'success'=>false,'msg'=>'Tu puja ha sido superada.'));
											}
											// Se cambia a 2 porque el registro que ya estaba con el monto puja anterior debe quedar registrado
											$registro->verificado = 2;
				        				}
				        				
				        				
				        				$registro->save();
				        					
				        			}else
				        			{

				        				// No hay otro pujador con puja maxima
				        				echo 'Salvando';
										$imagen_modelo->actual *= 1.1;

				        			}

				        			

				        			//Usuariospujas::model()->findByPk();

									if(!$imagen_modelo->save()){
										$msg = print_r($imagen_modelo->getErrors(),1);
										throw new CHttpException(400,'ImagenS data not saving: '.$msg );
									}
		        				
		        					$model->idusuario = $usuario_actual;
		        				 	$model->monto_puja = $imagen_modelo->actual;
		        					if(!$model->insert())
		        					{
										$msg = print_r($model->getErrors(),1);
										throw new CHttpException(400,'Registro Pujas data not saving: '.$msg );
									}else{

										echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Tu puja ha sido exitosa.'));
									}
								
				        			//$model->save(true,array('idusuario'=>Yii::app()->session['id_usuario'],));
				        			
			        			}else
			        			{
			        				echo json_encode(array('id'=>1, 'success'=>false,'msg'=>'Puja maxima debe ser mayor a dos veces el 10% de la actual'));
			        			}

			        		}else
			        		{	// Puja simple
			        				$registro = RegistroPujas::model()->find('id_imagen_s=:imagen AND verificado=:verificado',
									array(
									  ':imagen'=>$model->id_imagen_s,
									  ':verificado'=>1,
									  //':maxi' => NULL,
									));
									$imagen_modelo->actual *= 1.1;

									if($registro){ //ya existe un usuario con puja maxima
										if($registro->maximo_dispuesto > $imagen_modelo->actual){

											// Se inserta otra vez con el monto puja nuevo
											$registro->monto_puja = $imagen_modelo->actual;
											if(!$registro->insert())
				        					{
												$msg = print_r($registro->getErrors(),1);
												throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
											}else
												echo json_encode(array('id'=>1, 'success'=>false,'msg'=>'Tu puja ha sido superada.'));
											
										}else{
												$registro->verificado = 2;
										
					        				$model->idusuario = $usuario_actual;
					        				$model->monto_puja = $imagen_modelo->actual;
				        					if(!$model->insert())
				        					{
												$msg = print_r($model->getErrors(),1);
												throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
											}else
												echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Tu puja ha sido exitosa.'));
										}

									}else{

						        			// se icrementa el valor de la imagen por 10%
						        			
					        			//$model->save(true,array('idusuario'=>Yii::app()->session['id_usuario'],));

					        			$model->idusuario = $usuario_actual;
				        				$model->monto_puja = $imagen_modelo->actual;
			        					if(!$model->insert())
			        					{
											$msg = print_r($model->getErrors(),1);
											throw new CHttpException(400,'RegistroPujas: data not saving: '.$msg );
										}else{

											echo json_encode(array('id'=>1, 'success'=>true,'msg'=>'Tu puja ha sido exitosa.'));
										}
									}
								if(!$imagen_modelo->save()){
									$msg = print_r($imagen_modelo->getErrors(),1);
									throw new CHttpException(400,'ImagenS: data not saving: '.$msg );
								}
							
			        		}
		}

} //Cierra la clase
