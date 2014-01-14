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

		$pujarAjaxLink = CHtml::ajaxLink('Pujar',
		        $this->createUrl('site/pujar'),
		        array(
		            //'onclick'=>'$("#pujaModal").dialog("open"); return false;',
		            //'update'=>'#pujaModal'
		            'success'=>'function(r){$("#pujaModal").html(r).dialog("open"); return false;}'
		        ),
		        array('id'=>'showJuiDialog')
		);
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
			
			if($contador==6)
			{
				//echo '<tr>';

				$imprimir .= '<tr align="center" valign="middle">';
			}
				$contador++;
				if($resultado)
				{
					
					//echo '<td><img src="images/3ba.jpg"><br/>'.$con.'<div id="imagen_'.$value->id.'">Paleta : '.$resultado['paleta'].'<br/>Precio : '.$value->actual.'</div><a href="?r=site/pujar">Pujar</a></td>';
					$imprimir .='<td align="center" valign="middle"><img onclick="$(\'#pujaModal\').dialog(\'open\'); return false;" src="images/3ba.jpg"><br/>'.$con.'<div id="imagen_'.$value->id.'">Paleta : '.$resultado['paleta'].'<br/>Precio : '.$value->actual.'</div>'
					.$pujarAjaxLink.'</td>';


				}else
				{
					//echo '<td><img src="images/3ba.jpg" onclick="$(\'#pujaModal\').dialog(\'open\'); return false;"><br/>'.$con.'<div id="imagen_'.$value->id.'">Precio : '.$value->actual.'</div><a href="?r=site/pujar">Pujar</a></td>';
					$imprimir .='<td align="center" valign="middle"><img onclick="$(\'#pujaModal\').dialog(\'open\'); return false;" src="images/3ba.jpg"><br/>'.$con.'<div id="imagen_'.$value->id.'">Precio : '.$value->actual.'</div>'
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
	    
	    if(isset($_POST['ajax']) && $_POST['ajax']==='registro-pujas-pujar-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    /**/

	    if(isset($_POST['RegistroPujas']))
	    {
	        $model->attributes=$_POST['RegistroPujas'];
	        if($model->validate())
	        {
	            // form inputs are valid, do something here

	        	
	            return;
	        }
	    }
	    $this->layout = '//layouts/modal';
	    $this->render('pujar',array('model'=>$model));
	}

}