<?php

//http://www.yiiframework.com/doc/guide/1.1/en/topics.console

class SitemapCommand extends CConsoleCommand
{
    public function actionIndex() {


    }

    public function actionAlertaPresubasta() {

        //
        list($MailController) = Yii::app()->createController('Mail');
        //$MailController->mailsend();



        $arreglo = array();

        $criteria = new CDbCriteria;

        //Tomo ultima silenciosa
        $criteria->condition = 'fuesilenciosa=:fuesilenciosa';
        $criteria->params = array(':fuesilenciosa'=>1);
        $criteria->order = 'id DESC';

        $silenciosa = Subastas::model()->find($criteria);

        // Ya fue enviado los correos masivos.
        if($silenciosa->envio_correos)
            return;


        // Verificar
        // Existe una subasta silenciosa activa?
        $criteria = new CDbCriteria;

        $criteria->condition = 'silenciosa=:silenciosa';
        $criteria->params = array(':silenciosa'=>1);

        $subas = Subastas::model()->find($criteria);

        if(!$subas) {

            // Pre Subasta
            $criteria = new CDbCriteria;

            $criteria->condition = 'ids=:ids';
            $criteria->params = array(':ids' => $silenciosa->id);

            $crono = Cronometro::model()->find($criteria);

            $time = new DateTime($crono->fecha_finalizacion);
            $actualTime = new DateTime("now");
            $intervaloPresubasta = $actualTime->getTimestamp() - $time->getTimestamp();

            // Verificando que se encuentra en los proximos 10 minutos al finalizar la subasta.
            if( !($intervaloPresubasta >=0 && $intervaloPresubasta <= 600) )
                return;
        }else return;

        $footer = Correos::model()->find('id=:id', array('id'=>1));

        //construyo el titulo del mensaje
        $subject = 'Resultados de la '.$silenciosa['nombre'].' '.$silenciosa['nombrec'];

        //obtengo los resultados de las obras en la subasta finalizada.
        $usuarios = ImagenS::model()->findAll('ids=:ids', array(':ids' => $silenciosa['id']));

        $message = "";


        //echo $silenciosa['nombre'];
        //echo "Hola";


        foreach ($usuarios as $key => $value)
        {
            //valido que la obra la tenga un usuario y q no vuelva a entrar ese mismo usuario

            if($value->id_usuario and !in_array($value->id_usuario, $arreglo))
            {

                $usuario = Usuarios::model()->find('id=:id', array(':id'=>$value->id_usuario));

                $usuariospuja = Usuariospujas::model()->find('idusuario=:idusuario and idsubasta=:idsubasta', array(':idusuario'=>$usuario['id'], ':idsubasta' => $silenciosa['id']));

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

                // Lista de imágenes del usuario.
                foreach ($usuarios as $ky => $valor) {
                    $message .='<tr>
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
					 <td align="center">'.$silenciosa['moneda'].' '.number_format($valor->actual).'</td>
					 <td align="center">'.$silenciosa['moneda'].' '.number_format($monto18).'</td>
					 <td align="center">'.$silenciosa['moneda'].' '.number_format($iva).'</td>

					 <td align="center">
					  '.$silenciosa['moneda'].' '.number_format($total).'
					 </td>


					</tr>';
                    $total = 0;

                }

                $message .=  '</tbody>
				</table>
				<hr>';

                $message .= '<h1> Se va a cerrar la PRE SUBASTA </h1><br>';
                $message .= '<h2> A partir de la recepción de este correo tiene aproximadamente una hora para realizar la presubasta de sus pujas ganadas. Haga click aquí para ir a <a href="'.Yii::app()->request->baseUrl.'">Subastas Odalys </a></h2>';

                $message .= $footer['footer'].'</div>';

                //echo $message;

                //echo "Fin de mensaje";
                //echo "----------------------------------";
                //echo "<br>";
                //$this->mailsend($to,$subject,$message);
                $MailController->mailsend($to,$subject,$message);

                $message = "";
                $to= "";
                $subject = "";
                //	$this->render('compradores', array('valor'=>$message));
            }
            $message = "";
            $to= "";
            $subject = "";
        }
        //echo "hola";
        //print_r($arreglo);


        // Se marca la subasta que fue silenciosa como  enviada los correos.
        $silenciosa->envio_correos = 1;
        if($silenciosa->save())
            return;

    }

    public function mailsend($to,$subject,$message){
        $from = "pujas@odalys.com"; //noreply@odalys.com
        $mail=Yii::app()->Smtpmail;
        $mail->CharSet = 'UTF-8';
        $mail->SetFrom($from, 'Grupo Odalys');
        $mail->AddReplyTo($from, 'Grupo Odalys');
        $mail->Subject    = $subject;
        $mail->MsgHTML($message);
        $mail->AddAddress($to, "");
        if(!$mail->Send()) {
            $to= "";
            $subject = "";
            $message = "";
            return false;
        }else {
            $to= "";
            $subject = "";
            $message = "";
            return true;
        }
    }

//public function actionInit() { ... }
}