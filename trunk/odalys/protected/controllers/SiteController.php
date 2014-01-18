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

		$criteria->condition = 'activa=:activa';
		$criteria->params = array(':activa'=>1);

		$subas = Subastas::model()->find($criteria);

		$criteria = new CDbCriteria;

		$criteria->condition = 'ids=:ids';
		$criteria->params = array(':ids'=>$subas['id']);


		$query = ImagenS::model()->findAll($criteria);


		$contador = 0;
		$con = 0;
		$imprimir ="";
		//echo '<table width="80%"><tr>';
		$imprimir = '<table width="100%"><tr>';
		foreach ($query as $key => $value) {
			$con ++;
			$criteria = new CDbCriteria;

			$criteria->condition = 'idusuario=:idusuario';
			$criteria->params = array(':idusuario'=>$value->id_usuario);

			$resultado= Usuariospujas::model()->find($criteria);
			
			$pujarAjaxLink = CHtml::ajaxLink('Pujar',
		        $this->createUrl('site/pujar'),
		        array(
		            //'onclick'=>'$("#pujaModal").dialog("open"); return false;',
		            //'update'=>'#pujaModal'
		            'type'=>'POST',
		            'data' => array('imagen_s'=> '0' ),
		            'context'=>'js:this',
		            'beforeSend'=>'function(xhr,settings){
		            						settings.data = encodeURIComponent(\'imagen_s\')
              								+ \'=\'
              								+ encodeURIComponent($(this).attr(\'id\')); //{imagen_s: $(this).attr("id")};
		            }',
		            'success'=>'function(r){$("#pujaModal").html(r).dialog("open"); return false;}'
		        ),
		        array('id'=>$value->id)
			);

			if($contador==6)
			{
				//echo '<tr>';

				$imprimir .= '<tr align="center" valign="middle">';
			}
				$contador++;
				if($resultado)
				{
					
					//echo '<td><img src="images/3ba.jpg"><br/>'.$con.'<div id="imagen_'.$value->id.'">Paleta : '.$resultado['paleta'].'<br/>Precio : '.$value->actual.'</div><a href="?r=site/pujar">Pujar</a></td>';
					$imprimir .='<td align="center" valign="middle"><img onclick="$(\'#'.$value->id.'\').triggerHandler(\'click\');" src="images/3ba.jpg"><br/>'.$con.'<div id="imagen_'.$value->id.'">Paleta : '.$resultado['paleta'].'<br/>Precio : '.$value->actual.'</div>'
					.$pujarAjaxLink.'</td>';


				}else
				{
					//echo '<td><img src="images/3ba.jpg" onclick="$(\'#pujaModal\').dialog(\'open\'); return false;"><br/>'.$con.'<div id="imagen_'.$value->id.'">Precio : '.$value->actual.'</div><a href="?r=site/pujar">Pujar</a></td>';
					$imprimir .='<td align="center" valign="middle"><img onclick="$(\'#'.$value->id.'\').triggerHandler(\'click\');" src="images/3ba.jpg"><br/>'.$con.'<div id="imagen_'.$value->id.'">Precio : '.$value->actual.'</div>'
					.$pujarAjaxLink.'</td>';
				}

			if($contador==6)
			{
				//echo '</tr>';
				$imprimir .='</tr>';
				$contador=0;
			}

			
			//$model = 21;

		}
		$imprimir .='</table>';

		$this->layout='//layouts/column1';

		//$imprimir ="Hola";
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php1'
		$this->render('index', array('imprimir'=>$imprimir));

	}

	public function actionBuscar()
	{


		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		//$this->render('index');

		//$connection Yii::app()->db;
		//$connection->query("SELECT * FROM imagen_s WHERE ");

		$criteria = new CDbCriteria;

		$criteria->condition = 'activa=:activa';
		$criteria->params = array(':activa'=>1);

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
				$res[] =  array('id'=>$value->id,'paleta'=>$resultado['paleta'], 'actual'=>$value->actual);
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
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
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
	   
echo '::::::SSSSSSSSSSSSSS';
if(isset($_POST['imagen_ss']))
echo 'si veo imagen_ss: '.$_POST['imagen_ss'];
else
echo 'no veo imagen_ss en: ';
echo print_r($_POST);

	    if(isset($_POST['RegistroPujas']))
	    {echo 'Existe $_POST[\'RegistroPujas\']: '.print_r($_POST);
	        $model->attributes=$_POST['RegistroPujas'];
	        if($model->validate())
	        { echo 'todo validado!!!!!!!!!!!!!!!!!   '.print_r($model->id_imagen_s);	        		

	    			$imagen_modelo = ImagenS::model()->findByPk($model->id_imagen_s);
	        		$subasta = Subastas::model()->findByPk($imagen_modelo->ids);

	            // form inputs are valid, do something here
	        	//Aqui se va a verificar el monto maximo de la puja y hacer todo lo relacionado con la puja
	        	//if($model->id_imagen_s == 4593)
	        	if($subasta->activa)
	        	{
	        		echo 'ENTREEEEEEEEEEEEEEEEEEEEEEE';


	        		$imagen_modelo->id_usuario = Yii::app()->session['id_usuario'];

	        		if($model->maximo_dispuesto) //aqui se verifica si se envio un monto_maximo.
	        		{
	        			$monto_real = ($imagen_modelo->actual*1.1)*1.1;

	        			if($model->maximo_dispuesto >= $monto_real) //aqui va monto_maximo
	        			{

	        				$registro = RegistroPujas::model()->find('id_imagen_s=:imagen AND verificado=:verificado',
							array(
							  ':imagen'=>$model->id_imagen_s,
							  ':verificado'=>1,
							  //':maxi' => NULL,
							));


	        				if($registro)
	        				{

		        				if($registro->maximo_dispuesto >  $model->maximo_dispuesto)  
		        				{
		        					$imagen_modelo->actual = $model->maximo_dispuesto * 1.1;

		        				}elseif($registro->maximo_dispuesto <  $model->maximo_dispuesto)
		        				{

									$imagen_modelo->actual = $registro->maximo_dispuesto * 1.1;

									$registro->verificado = 2;

									$model->verificado=1;


		        				}else{

		        					while ($imagen_modelo->actual < $model->maximo_dispuesto) {
		        						
		        						$imagen_modelo->actual *= 1.1;

		        						if($registro->verificado == 2){
		        							$registro->verificado = 1;
		        							$registro->monto_puja = $imagen_modelo->actual;
		        							$model->verificado = 2;
		        						}else{
		        							$registro->verificado = 2;
		        							$model->verificado = 1;
		        							$model->monto_puja = $imagen_modelo->actual;
		        						}

		        					}

		        				}

		        				$registro->save();
		        					
		        			}else
		        			{

		        					/// es el primero que ingresa.
		        				echo 'Salvando';
								$imagen_modelo->actual *= 1.1;

								//$model->save();
		        			}

		        			$model->monto_puja = $imagen_modelo->actual;
		        			//$imagen_modelo->paleta = $model 						// <----------- Falta esto


							//$model->save();
							//echo $imagen_modelo->save(false);
							if(!$imagen_modelo->save()){
							$msg = print_r($imagen_modelo->getErrors(),1);
							throw new CHttpException(400,'data not saving: '.$msg );
							}

		        			//$model->save(true,array('idusuario'=>Yii::app()->session['id_usuario'],));
		        			
	        			}else
	        			{
	        				echo 'monto maximo dispuesto debe ser mayor a dos veces el 10% de la actual';
	        			}

	        		}else
	        		{
	        			$imagen_modelo->actual *= 1.1;
	        			echo $imagen_modelo->save(false);
	        			//$model->save(true,array('idusuario'=>Yii::app()->session['id_usuario'],));	

	        		}

		        	//$_SESSION['admin']	//caso especial
		        	
		        	//$_SESSION['id_usuario']
	        		//$model->updateByPk(array('idusuario'=>Yii::app()->session['id_usuario'],'id_imagen_s'=>$imagensId));

	        		//ImagenS::model()->updateByPk($imagenId,array('actual'=>$model->maximo_dispuesto));

	        		//Hay que hacer un trigger en la bd que al actualizar el maximo_dispuesto de la tabla registro_pujas,
	        		//actualice el monto actual de la imagen_s correspondiente a ese registro, al minimo valor de puja siguiente (tomando
	        		// en cuenta los maximos_dispuestos de los otros usuarios que hayan de esa imagen_s) y que se genere 
	        		//el aviso para enviar el correo al usuario que ha sido superado en la puja

	        	}else
	        	{echo 'No se recibio el identificador de la imagen';}
	            return;
	        }

	    }

	    $this->layout = '//layouts/modal';
	    $this->render('pujar',array('model'=>$model));
	}

}