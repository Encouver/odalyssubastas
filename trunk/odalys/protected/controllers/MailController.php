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
				'users'=>array('*'),
				//'expression' => '(Yii::app()->session["id_usuario"])'  //cambiar a admin
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

		//Tomo ultima silenciosa
		$criteria->condition = 'fuesilenciosa=:fuesilenciosa';
		$criteria->params = array(':fuesilenciosa'=>1);
		$criteria->order = 'id DESC';

		$silenciosa = Subastas::model()->find($criteria);		
			
		//construyo el titulo del mensaje
		$subject = 'Resultados de la '.$silenciosa['nombre'].' '.$silenciosa['nombrec'];

		//obtengo los resultados de las obras en la subasta finalizada.
		$usuarios = ImagenS::model()->findAll('ids=:ids', array(':ids' => $silenciosa['id']));

		
		foreach ($usuarios as $key => $value)
		{
			//valido que la obra la tenga un uuario y q no vuelva a entrar ese mismo usuario
			if($value->id_usuario and !in_array($value->id_usuario, $arreglo))
			{

				$usuario = Usuarios::model()->find('id=:id', array(':id'=>$value->id_usuario));

				$usuariospuja = Usuariospujas::model()->find('idusuario=:idusuario and idsubasta=:idsubasta', array(':idusuario'=>$usuario['id'], ':idsubasta' => $silenciosa['id']));
				//Metodo hacer invocado cada vez que un pujador pierda en la misma.
				if(!$usuariospuja) continue;

				$correo = $usuario['email'];
				$nombre = $usuario['nombre'];
				$apellido = $usuario['apellido'];

				$paleta = $usuariospuja['paleta'];

				$to = $correo;

				$message = '
		 <div style="padding-left:50px !important; padding-top:10px !important; float:left !important; padding-right:20px !important;">
               <h2 style="padding-bottom:5px !important; font-size:14px !important;">Estimado(a) '.strtoupper($nombre).' '.strtoupper($apellido).', la '.$silenciosa['nombre'].' '.$silenciosa['nombrec'].' ha finalizado a la 1:00 p.m. del día de hoy.</h2>
               <h2 style="padding-bottom:10px !important; font-size:14px !important;">Se le han adjudicado los siguientes lotes:</h2><br/> 
				<table width="100%">
				  <thead>
				    <tr>

				      <th align="left">NOMBRE</th>
				      <th align="left">PALETA</th>
				      <th align="center" style="width: 200px;">LOTE</th>
				      <th align="left">PRECIO DE VENTA DEL MARTILLO</th>
				      <th align="left">COMISION DE LA CASA DE SUBASTA (18% )</th>
				      <th align="left">IMPUESTO SOBRE LA COMISION (12%)</th>
				      <th align="left">TOTAL A PAGAR</th>

				    </tr>
				  </thead>
				  <tbody>';

				  $arreglo[] = $value->id_usuario;

				  $usuarios = ImagenS::model()->findAll('id_usuario=:id_usuario and ids=:idsubasta', array(':id_usuario' => $value->id_usuario, ':idsubasta'=> $silenciosa['id']));
				 
				  foreach ($usuarios as $ky => $valor) {
				  		
				  	$message .=
				  		'
						 <tr>
						 <td align="left">    
					       '.$nombre.' '.$apellido.'
					 </td>

					 <td align="left">
					       '.$paleta.'
					 </td>
					 <td align="center" style="width: 200px;">
					  <!--<img src="http://www.odalys.com/odalys/'.$valor->imagen.'" style="float:left;padding-right:20px;"/>-->
					  '.$valor->descri.'
					</td>';
					 $monto18 = 0;

					 $monto18 = (($valor->actual*18)/100);

					 $iva = 0;
					 $iva = (($monto18*12)/100);

					 $total1 = 0;
					 $total1 = $monto18 + $iva;

					 $total = $total1 + $valor->actual;
					 $message .= 

					 '
					 <td align="center">Bs. '.number_format($valor->actual).'</td>
					 <td align="center">Bs. '.number_format($monto18).'</td>
					 <td align="center">Bs. '.number_format($iva).'</td>
					 
					 <td align="center">
					  Bs. '.number_format($total).'
					 </td>
					 

					</tr>';
					$total = 0;
				  }
				
					$message .=  '</tbody>
				</table>
				<hr>
					Recuerde que las condiciones de la subasta establecen que tiene 7 días para cancelar sus piezas en los espacios de la Casa de Subastas en el Centro Comercial Concresa, PB en el horario de Martes a Sábado de 9:00 a.m. a 5:00 p.m.<br/>

					<br/>
			Atentamente,<br/>
			<br/>
			Casa de Subastas Odalys<br/>
			C. Comercial Concresa, Nivel PB. Local <br/>
			115 y 116, Prados del Este, Baruta 1080,<br/>
			Estado Miranda, Venezuela<br/>
			Telfs: +58 2129795942, +58 2129761773<br/>
			Fax: +58 212 9794068<br/>
			odalys@odalys.com
    	</div> ';


    		$headers  = 'MIME-Version: 1.0' ."\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Grupo Odalys<galeriaccs@odalys.com>'. "\r\n" .
		    'Reply-To: pujas@odalys.com' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();


		     if (mail($to, $subject, $message, $headers)) {
		       		echo $to.'<br/>';
			     	//$this->layout='//layouts/column1';
			    	//$valor = true;
					//$this->render('index', array('valor'=>$valor));
		    	} else {
					echo $to;
					echo "<br>";
			    	echo "error";
					//$this->render('index', array('valor'=>$valor));
		    	
		   		 }

			//	$this->render('compradores', array('valor'=>$message));
		   		 


			}
		}
	}

	public function Pujadores($id, $descri)
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
					Estimado(a) '.$nombre.' '.$apellido.'<br><br>Ha sido superada la puja que realizá por el lote:<br> '.$descri.'<br/>
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
		     	/*$this->layout='//layouts/column1';
		    	$valor = true;
				$this->render('compradores', array('valor'=>$valor));*/
		    } else {

		    	//$this->layout='//layouts/column1';
		    	$valor = false;
				$this->render('compradores', array('valor'=>$valor));
		    	
		    }


	}


}