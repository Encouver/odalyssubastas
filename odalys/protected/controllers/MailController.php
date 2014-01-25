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
		$arreglo = array();

		$criteria = new CDbCriteria;

		$criteria->condition = 'fuesilenciosa=:fuesilenciosa';
		$criteria->params = array(':fuesilenciosa'=>1);
		$criteria->order = 'id DESC';

		$silenciosa = Subastas::model()->find($criteria);		
			
		$subject = 'Resultados de la '.$silenciosa['nombre'].' '.$silenciosa['nombrec'];

		$usuarios = ImagenS::model()->findAll('ids=:ids', array(':ids' => $silenciosa['id']));

		
		foreach ($usuarios as $key => $value)
		{
			
			if($value->id_usuario and !in_array($value->id_usuario, $arreglo))
			{

				$usuario = Usuarios::model()->find('id=:id', array(':id'=>$value->id_usuario));

				$usuariospuja = Usuariospujas::model()->find('idusuario=:idusuario', array(':idusuario'=>$usuario['id']))
				//Metodo hacer invocado cada vez que un pujador pierda en la misma.

				$correo = $usuario['email'];
				$nombre = $usuario['nombre'];
				$apellido = $usuario['apellido'];

				$paleta = $usuariospuja['paleta'];

				$to = $correo;

				$message = '
		 <div style="padding-left:100px !important; padding-top:10px !important; float:left !important; padding-right:50px !important;">
               <h2 style="padding-bottom:10px !important; font-size:14px !important;">Estimado cliente, la '.$silenciosa['nombre'].' '.$silenciosa['nombrec'].' (Subasta Silenciosa) ha finalizado.</h2>   
				<table class="table">
				  <thead>
				    <tr>
				      <th>Nombre</th>
				      <th>Apellido</th>
				      <th>Paleta</th>
				      <th>Referencia</th>
				      <th>Monto</th>
				      <th>Lote</th>
				    </tr>
				  </thead>
				  <tbody>';

				  $arreglo[] = $value->id_usuario;

				  $usuarios = ImagenS::model()->findAll('ids=:id_usuario', array(':id_usuario' => $value->id_usuario));
				 
				  foreach ($usuarios as $ky => $valor) {
				  		
				  	$message .=
				  		'
						 "<tr>";
						 "<td>";        
					       '.$nombre.'
					 "</td>";
					"<td>";
					       '.$apellido.'
					 "</td>";
					 "<td>";
					       '.$paleta.'
					 "</td>";

					 "<td>";
					        '.$valor->descri.'
					 "</td>"';

					 $monto18 = $valor->actual*1.18;

					 $iva = $monto18*1.12;

					 $total = $monto18 + $iva + $valor->actual;

					 '"<td>";
					  echo "Bs. '.$total.'";
					 "</td>";
					 "<td>";
					  echo "<img src=http://odalys.com/odalys/"'.$valor->imagen.'">";
					"</td>";

					"</tr>"';

				  }
				
					$message .=  '</tbody>
				</table>
				<hr>
					Recuerde que las condiciones de la subasta establecen que tiene 7 días para cancelar sus piezas en los espacios de la Casa de Subastas en el Centro Comercial Concresa, PB en el horario de Martes a Sábado de 10:00 a.m. a 6:00 p.m.<br/>

			Saludos,

			Casa de Subastas Odalys
			C. Comercial Concresa, Nivel PB. Local 
			115 y 116, Prados del Este, Baruta 1080,
			Estado Miranda, Venezuela
			Telfs: +58 2129795942, +58 2129761773
			Fax: +58 212 9794068
			odalys@odalys.com
    	</div> ';


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
	}

	public function actionPujadores($id, $descri)
	{

		$silenciosa = Subastas::model()->find('silenciosa=:silenciosa', array(':silenciosa'=>1));

		$usuario = Usuarios::model()->find('id=:id', array(':id'=>$id));
		//Metodo hacer invocado cada vez que un pujador pierda en la misma.

		$correo = $usuario['email'];
		$nombre = $usuario['nombre'];
		$apellido = $usuario['apellido'];

		
		$to      = $correo;
		$subject = 'Puja superada en la '.$silenciosa['nombre'].' '.$silenciosa['nombrec'];
		$message = 

		'
		<html>
			<head>
  				<title>'.$silenciosa['nombre'].' '.$silenciosa['nombrec'].' Casa de Subastas Odalys</title>
			</head>
				<body>
					Estimado(a) '.$nombre.' '.$apellido.'<br><br>Ha sido superada la puja que realizÃ³ por el lote:<br> '.$descri.'<br/>
					<br>Si desea puede hacer una puja por esta obra nuevamente. <a href="http://subastas.odalys.com/">Ir a la '.$silenciosa['nombre'].' '.$silenciosa['nombrec'].'</a>
					<br/>

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