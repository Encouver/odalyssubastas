<?php

//http://www.yiiframework.com/doc/guide/1.1/en/topics.console

class SitemapCommand extends CConsoleCommand
{
    public function actionIndex() {
        echo 'Hola Mundo';
    }

    public function actionAlertaPresubasta() {

        Yii::$enableIncludePath = false;

        //
        //list($MailController) = Yii::app()->createController('Mail');
        //$MailController->mailsend();


        $arreglo = array();

        $criteria = new CDbCriteria;

        // Verificar
        // Existe una subasta silenciosa activa?
        $criteria = new CDbCriteria;

        $criteria->condition = 'silenciosa=:silenciosa';
        $criteria->params = array(':silenciosa'=>1);

        $subas = Subastas::model()->find($criteria);

        if($subas == null) {

            //Tomo ultima silenciosa
            $criteria->condition = 'fuesilenciosa=:fuesilenciosa';
            $criteria->params = array(':fuesilenciosa'=>1);
            $criteria->order = 'id DESC';

            $silenciosa = Subastas::model()->find($criteria);

            // Ya fue enviado los correos masivos.
            if($silenciosa->envio_correos)
                return;

            // Pre Subasta
            $criteria = new CDbCriteria;

            $criteria->condition = 'ids=:ids';
            $criteria->params = array(':ids' => $silenciosa->id);

            $crono = Cronometro::model()->find($criteria);

            $time = new DateTime($crono->fecha_finalizacion);
            //$time->add(new DateInterval('PT1H'));
            $actualTime = new DateTime("now");
            $intervaloPresubasta =  $actualTime->getTimestamp() - $time->getTimestamp();

            // Verificando que se encuentra en los proximos 10 minutos al finalizar la subasta.
           if( !($intervaloPresubasta >=0 && $intervaloPresubasta <= 600) )
                return;

        }else return;

        $footer = Correos::model()->find('id=:id', array('id'=>1));

        //construyo el titulo del mensaje
        $subject = 'Ha finalizado la Pre-'.$silenciosa['nombre'].' '.$silenciosa['nombrec'];

        //obtengo los resultados de las obras en la subasta finalizada.
        //$imagenes = ImagenS::model()->findAll('ids=:ids', array(':ids' => $silenciosa['id']));


        $criteria = new CDbCriteria();
        $criteria->distinct=true;
        $criteria->condition = "ids=".$silenciosa->id;
        $criteria->select = 'id_usuario, ids';
        $imagenes=ImagenS::model()->findAll($criteria);


        $message = "";


        //echo $silenciosa['nombre'];
        //echo "Hola";


        foreach ($imagenes as $key => $value)
        {

            //valido que la obra la tenga un usuario y q no vuelva a entrar ese mismo usuario
/*
            if($value->id_usuario and !in_array($value->id_usuario, $arreglo))
            {*/

                $usuario = Usuarios::model()->find('id=:id', array(':id'=>$value->id_usuario));

                $usuariospuja = Usuariospujas::model()->find('idusuario=:idusuario and idsubasta=:idsubasta', array(':idusuario'=>$usuario['id'], ':idsubasta' => $silenciosa['id']));

                if(!$usuariospuja) continue;

                $correo = $usuario['email'];
                $nombre = $usuario['nombre'];
                $apellido = $usuario['apellido'];

                $paleta = $usuariospuja['paleta'];

                $to = $correo;


                $message = '
		 <div style="padding-left:50px !important; padding-top:10px !important; float:left !important; padding-right:20px !important;">';
                $message .= '<h2>PRE-SUBASTA FINALIZADA</h2><br>

               <h2 style="padding-bottom:5px !important; font-size:14px !important;">Estimado(a) '.strtoupper($nombre).' '.strtoupper($apellido).', la Pre-'.$silenciosa['nombre'].' '.$silenciosa['nombrec'].' ha finalizado a las 5:00 p.m. del día de hoy.</h2>
               <h2 style="padding-bottom:10px !important; font-size:14px !important;">Hasta el momento Ud. tiene la última puja de los siguientes lotes:</h2><br/>
				<table width="100%">
				  <thead>
				    <tr>

				      <th align="left" style="width: 200px;">LOTE</th>
				      <th align="left">ÚLTIMA PUJA</th>

				    </tr>
				  </thead>
				  <tbody>';


                //$arreglo[] = $value->id_usuario;

                $usuarios = ImagenS::model()->findAll('id_usuario=:id_usuario and ids=:idsubasta', array(':id_usuario' => $value->id_usuario, ':idsubasta'=> $silenciosa['id']));

                // Lista de imágenes del usuario.
                foreach ($usuarios as $ky => $valor) {
                    $message .='<tr>
					 <td align="left" style="width: 200px;">
					  <!--<img src="http://www.odalys.com/odalys/'.$valor->imagen.'" style="float:left;padding-right:20px;"/>-->
					  '.$valor->descri.'
					</td>';

                    $message .=

                        '
					 <td align="left">'.$silenciosa['moneda'].' '.number_format($valor->actual).'</td>


					</tr>';

                }

                $message .=  '</tbody>
				</table>
				<hr>';

                $message .= '<h2 style="padding-bottom:10px !important; font-size:14px !important;"> A partir de este momento, tiene hasta el día de mañana a las 10:00 a.m. 
                para ingresar en nuestra plataforma y seleccionar qué desea hacer con los lotes sobre los que tiene la última puja hasta 
                el momento. Haga click aquí para ir a <a href="'.Yii::app()->request->baseUrl.'">Subastas Odalys</a></h2>';

                $message .= '

                Para cada uno de dichos lotes podrá seleccionar alguna de las siguientes opciones:
                <br><br>
                1. Dejar puja máxima, que va a ser realizada por nosotros como una puja en ausencia durante el acto de Subasta en vivo. 
                <br><br>
                2. Dejar puja telefónica, en cuyo caso nos comunicáremos con Ud. el día del acto de Subasta en vivo en el momento que sea subastado su lote. Importante: de no lograr comunicarnos con Ud. durante la subasta, su última puja de la presubasta será tomada como su última oferta en el lote. 
                <br><br>
                3. Asistiré a la subasta en vivo, en este caso su última puja de la presubasta va a ser tomada como su última oferta en el lote, es decir, el lote será subastado en la sala desde ese monto y usted mismo podrá continuar su puja si fuese superada por algún otro postor. 
                <br><br>
                4. Quedarme con mi puja actual, en este caso su última puja de la presubasta va a ser tomada como su última oferta en el lote, es decir, el lote será subastado en la sala desde ese monto. 
                <br><br>
                <b>* Si no selecciona ninguna opción en el tiempo estipulado, automáticamente se selecciona la opción 4.</b>
                    <br>   <br>   <br>
                Recuerde que la Subasta 237 Maestros Venezolanos, se realizará mañana domingo 29 de noviembre a las 11:00 a.m. en el Hotel JW Marriott Caracas. Salón Armando Reverón. Av. Venezuela con calle Mohedano. El Rosal. Caracas.                 
                    <br>    <br>
                    Ante cualquier duda, por favor póngase en contacto con nosotros.


                ';


                $message .= '

                    <br>
                    Atentamente,
                    <br><br>
                    Casa de Subastas Odalys<br>
                    C. Comercial Concresa, Nivel PB. Local <br>
                    115 y 116, Prados del Este, Baruta 1080,<br>
                    Estado Miranda, Venezuela<br>
                    Telfs: +58 2129795942, +58 2129761773<br>
                    Fax: +58 212 9794068<br>
                    odalys@odalys.com<br>


                </div>';

                //echo $message;

                //echo "Fin de mensaje";
                //echo "----------------------------------";
                //echo "<br>";
                echo 'Enviando correo a: '.$to.' con asunto: '.$subject/*.' y el mensaje: '.$message*/;
                echo PHP_EOL;
                $this->mailsend($to,$subject,$message);
                //$MailController->mailsend($to,$subject,$message);
            //var_dump(($value));

           //die;
                $message = "";
                $to= "";
                //$subject = "";
                //	$this->render('compradores', array('valor'=>$message));
            //}
            $message = "";
            $to= "";
            $subject = "";
        }


        // Se marca la subasta que fue silenciosa como  enviada los correos.
        $silenciosa->envio_correos = 1;
        if($silenciosa->save())
            return;

    }

    public function mailsend($to,$subject,$message){
        echo PHP_EOL.'A: '.$to.' '.PHP_EOL;
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
            $mail->ClearAddresses(); //clear addresses for next email sending
            return false;
        }else {
            $to= "";
            $subject = "";
            $message = "";
            $mail->ClearAddresses(); //clear addresses for next email sending
            return true;
        }
    }

    public function actionAlertaFinalizaPresubasta(){

        Yii::$enableIncludePath = false;

        //
        //list($MailController) = Yii::app()->createController('Mail');
        //$MailController->mailsend();


        $arreglo = array();

        $criteria = new CDbCriteria;


        // Verificar
        // Existe una subasta silenciosa activa?
        $criteria = new CDbCriteria;

        $criteria->condition = 'silenciosa=:silenciosa';
        $criteria->params = array(':silenciosa'=>1);

        $subas = Subastas::model()->find($criteria);

        if($subas == null) {

            //Tomo ultima silenciosa
            $criteria->condition = 'fuesilenciosa=:fuesilenciosa';
            $criteria->params = array(':fuesilenciosa'=>1);
            $criteria->order = 'id DESC';

            $silenciosa = Subastas::model()->find($criteria);

            // Ya fue enviado los correos masivos.
            if($silenciosa->envio_correos_pre)
                return;

            // Pre Subasta
            $actualTime = new DateTime("now");
            $intervaloPresubasta =  $actualTime->getTimestamp() - $silenciosa->fechaPresubasta()->getTimestamp();

            // Verificando que se encuentra en los proximos 10 minutos al finalizar la subasta.
            if( !($intervaloPresubasta >=0 && $intervaloPresubasta <= 600) )
                return;

        }else return;

        $footer = Correos::model()->find('id=:id', array('id'=>1));

        //construyo el titulo del mensaje
        $subject = $silenciosa['nombre'].' '.$silenciosa['nombrec'].' en vivo inicia en una hora';

        //obtengo los resultados de las obras en la subasta finalizada.
        //$imagenes = ImagenS::model()->findAll('ids=:ids', array(':ids' => $silenciosa['id']));


        $criteria = new CDbCriteria();
        $criteria->distinct=true;
        $criteria->condition = "ids=".$silenciosa->id;
        $criteria->select = 'id_usuario, ids';
        $imagenes=ImagenS::model()->findAll($criteria);


        $message = "";


        //echo $silenciosa['nombre'];
        //echo "Hola";


        foreach ($imagenes as $key => $value)
        {

            //valido que la obra la tenga un usuario y q no vuelva a entrar ese mismo usuario
            /*
                        if($value->id_usuario and !in_array($value->id_usuario, $arreglo))
                        {*/

            $usuario = Usuarios::model()->find('id=:id', array(':id'=>$value->id_usuario));

            $usuariospuja = Usuariospujas::model()->find('idusuario=:idusuario and idsubasta=:idsubasta', array(':idusuario'=>$usuario['id'], ':idsubasta' => $silenciosa['id']));

            if(!$usuariospuja) continue;

            $correo = $usuario['email'];
            $nombre = $usuario['nombre'];
            $apellido = $usuario['apellido'];

            $paleta = $usuariospuja['paleta'];

            $to = $correo;

            $message .= '<h2> Las obras a las que no dejo puja quedan hasta ahí. Haga click aquí para ir a <a href="'.Yii::app()->request->baseUrl.'">Subastas Odalys </a></h2>';

            $message = '
               <h2 style="padding-bottom:5px !important; font-size:14px !important;">Estimado(a) '.strtoupper($nombre).' '.strtoupper($apellido).', la Pre-'.$silenciosa['nombre'].' '.$silenciosa['nombrec'].' ha finalizado a las 5:00 p.m. del día de hoy.</h2>
               <h2 style="padding-bottom:10px !important; font-size:14px !important;">Hasta el momento Ud. tiene la última puja de los siguientes lotes:</h2><br/>
                <table width="100%">
                  <thead>
                    <tr>

                      <th align="left" style="width: 200px;">LOTE</th>
                      <th align="left">ÚLTIMA PUJA</th>

                    </tr>
                  </thead>
                  <tbody>';



            //$arreglo[] = $value->id_usuario;

            $usuarios = ImagenS::model()->findAll('id_usuario=:id_usuario and ids=:idsubasta', array(':id_usuario' => $value->id_usuario, ':idsubasta'=> $silenciosa['id']));

            // Lista de imágenes del usuario.
            foreach ($usuarios as $ky => $valor) {
                $message .='<tr>
					 <td align="left" style="width: 200px;">
					  <!--<img src="http://www.odalys.com/odalys/'.$valor->imagen.'" style="float:left;padding-right:20px;"/>-->
					  '.$valor->descri.'
					</td>';
                $message .=

                    '
					 <td align="center">'.$silenciosa['moneda'].' '.number_format($valor->actual).'</td>
					</tr>';

            }

            $message .=  '</tbody>
				</table>
				<hr>';


                $message .= '<h2 style="padding-bottom:10px !important; font-size:14px !important;">
                Le recordamos que si no seleccioó alguna de las opciones para los lotes mencionados anteriormente, asumiremos que
                desea quedarse con su puja actual, en este caso su última puja de la presubasta va a ser tomada como su última oferta en el lote, es decir, el lote será subastado en la sala desde ese monto.
                <br><br>
                Recuerde que la Subasta 237 Maestros Venezolanos, se realizará hoy domingo 29 de noviembre a las 11:00 a.m. en el Hotel JW Marriott Caracas. Salón Armando Reverón. Av. Venezuela con calle Mohedano. El Rosal. Caracas.
                <br><br>
                Ante cualquier duda, por favor póngase en contacto con nosotros.


                ';


                $message .= '

                    <br>
                    Atentamente,
                    <br><br>
                    Casa de Subastas Odalys<br>
                    C. Comercial Concresa, Nivel PB. Local <br>
                    115 y 116, Prados del Este, Baruta 1080,<br>
                    Estado Miranda, Venezuela<br>
                    Telfs: +58 2129795942, +58 2129761773<br>
                    Fax: +58 212 9794068<br>
                    odalys@odalys.com<br>


                </div>';

            //echo $message;

            //echo "Fin de mensaje";
            //echo "----------------------------------";
            //echo "<br>";
            echo 'Enviando correo a: '.$to.' con asunto: '.$subject/*.' y el mensaje: '.$message*/;
            echo PHP_EOL;
            $this->mailsend($to,$subject,$message);
            //$MailController->mailsend($to,$subject,$message);
            //var_dump(($value));

            //die;
            $message = "";
            $to= "";
            //$subject = "";
            //	$this->render('compradores', array('valor'=>$message));
            //}
            $message = "";
            $to= "";
            //$subject = "";
        }


        // Se marca la subasta que fue silenciosa como  enviada los correos.
        $silenciosa->envio_correos_pre = 1;
        if($silenciosa->save())
            return;
    }

//public function actionInit() { ... }
}