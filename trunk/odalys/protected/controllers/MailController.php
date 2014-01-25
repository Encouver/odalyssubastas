<?php

class MailController extends Controller
{

	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('compradores', 'pujadores'),
				'users'=>array('@'),
				'expression' => '(Yii::app()->session["id_usuario"])'  //cambiar a admin
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCompradores()
	{

		$silenciosa = Subastas::model()->find('silenciosa=:silenciosa', array(':silenciosa'=>1));
		

		$ganadores = ImagenS::model()->findAll('ids=:ids', array(':ids' => $silenciosa['id']));

		
		foreach ($ganadores as $key => $value) {
			


			if($value->id_usuario)
			{

					echo $value->id_usuario.'<br/>';

			}
		}

		//echo $silenciosa['id'];

		//Falta consultar los compradores de las obras es decir la tabla imagen_s y hacer un foreach
		/*
		$correo = "edgar.leal0@gmail.com";
		$nombre = "edgar";
		$apellido = "apellido";

		
		$to      = $correo;
		$subject = 'Registro satisfactorio en www.odalys.com';
		$message = 

		'
		<html>
			<head>
  				<title>Grupo Odalys</title>
			</head>
				<body>
					¡Bienvenido(a) '.$nombre.' '.$apellido.'! <br/>Su registro se ha realizado satisfactoriamente satisfactoriamente en www.odalys.com<br><br/>
					Al acceder a su cuenta en <a href="http://www.odalys.com/odalys/inicio.php">www.odalys.com</a> usted podr&aacute;:

					<li>Descargar los resultados de las &uacute;ltimas subastas.</li>
					<li>Dejar pujas en ausencia o telef&oacute;nicas para nuestras subastas.</li>
					<li>Participar en las Subastas Silenciosas.</li>
					<li>Recibir comunicaciones del Grupo Odalys.</li>
					<li>Pr&oacute;ximamente, comprar en nuestra tienda virtual y subastas online.</li>
					
					</body>
		</html>
		';

	 
		 	$headers  = 'MIME-Version: 1.0' ."\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Grupo Odalys<galeriaccs@odalys.com>'. "\r\n" .
		    'Reply-To: pujas@odalys.com' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();




		    if (mail($to, $subject, $message, $headers)) {
		     	$this->layout='//layouts/column1';
		    	$valor = true;
				$this->render('index', array('valor'=>$valor));
		    } else {

			    	$this->layout='//layouts/column1';
			    	$valor = false;
					$this->render('index', array('valor'=>$valor));
		    	
		    }*/


	}

	public function actionPujadores()
	{

		//Metodo hacer invocado cada vez que un pujador pierda en la misma.

		$correo = "edgar.leal0@gmail.com";
		$nombre = "edgar";
		$apellido = "apellido";

		
		$to      = $correo;
		$subject = 'Registro satisfactorio en www.odalys.com';
		$message = 

		'
		<html>
			<head>
  				<title>Grupo Odalys</title>
			</head>
				<body>
					¡Bienvenido(a) '.$nombre.' '.$apellido.'! <br/>Su registro se ha realizado satisfactoriamente satisfactoriamente en www.odalys.com<br><br/>
					Al acceder a su cuenta en <a href="http://www.odalys.com/odalys/inicio.php">www.odalys.com</a> usted podr&aacute;:

					<li>Descargar los resultados de las &uacute;ltimas subastas.</li>
					<li>Dejar pujas en ausencia o telef&oacute;nicas para nuestras subastas.</li>
					<li>Participar en las Subastas Silenciosas.</li>
					<li>Recibir comunicaciones del Grupo Odalys.</li>
					<li>Pr&oacute;ximamente, comprar en nuestra tienda virtual y subastas online.</li>
					
					</body>
		</html>
		';

	 
	 	$headers  = 'MIME-Version: 1.0' ."\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Grupo Odalys<galeriaccs@odalys.com>'. "\r\n" .
		    'Reply-To: pujas@odalys.com' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();




		    if (mail($to, $subject, $message, $headers)) {
		     	$this->layout='//layouts/column1';
		    	$valor = true;
				$this->render('index', array('valor'=>$valor));
		    } else {

		    	$this->layout='//layouts/column1';
		    	$valor = false;
				$this->render('index', array('valor'=>$valor));
		    	
		    }


	}


}