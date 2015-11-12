<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


<head>
    <meta charset="UTF-8">

    <!-- Remove this line if you use the .htaccess -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <meta name="viewport" content="width=device-width">

    <meta name="description" content="Subastas Odalys">
    <meta name="author" content="Marcos-Edgar">

    <title> <?php echo CHtml::encode($this->pageTitle); ?> </title>

    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <!--<link rel="shortcut icon" type="image/png" href="favicon.png">-->

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css">


    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>
<!-- Prompt IE 7 users to install Chrome Frame -->
<!--[if lt IE 8]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a
    different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a>
    to experience this site.</p><![endif]-->

<div class="nav-container f-nav">
    <div class="nav">


        <div class="container">

            <header id="navtop">
                <a href="<?php echo Yii::app()->getHomeUrl(); ?>" class="logo fleft">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" width="20px"
                         alt="Casa de Subastas Odalys">
                </a>

                <nav class="fright">
                    <?php

                    if (Yii::app()->session['id_usuario']) {
                        $this->widget('zii.widgets.CMenu', array(
                            'items' => array(
                                //	array('label'=>'Bienvenido(a) '.Yii::app()->session['nombre_usuario'].' '.Yii::app()->session['apellido_usuario']),
                                array('label' => 'Mi cuenta ', 'url' => 'http://www.odalys.com/odalys/micuenta.php', 'linkOptions' => array('target' => '_blank')),
                                array('label' => 'Condiciones de la subasta', 'url' => 'http://odalys.com/odalys/condiciones_silenciosa.php', 'linkOptions' => array('target' => '_blank')),
                                array('label' => 'Volver a  odalys.com', 'url' => 'http://www.odalys.com', 'linkOptions' => array('target' => '_blank')),
                                array('label' => 'Cerrar sesión (' . Yii::app()->session['nombre_usuario'] . ' ' . Yii::app()->session['apellido_usuario'] . ')', 'url' => array('/site/logout')),
                                //array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
                                //array('label'=>'Contact', 'url'=>array('/site/contact')),
                                // NOTA CAMBIAR A ************************** ADMIN ************************** CUANDO TERMINE DE TRABAJARSE
                                //array('label'=>'Puja asistida','url'=>array('site/pujaradmin'), 'visible'=>isset(Yii::app()->session['admin'])),

                            ),
                        ));
                    } else {

                        $this->widget('zii.widgets.CMenu', array(
                            'items' => array(
                                array('label' => 'Bienvenido(a) ' . Yii::app()->session['nombre_usuario'] . ' ' . Yii::app()->session['apellido_usuario'], 'visible' => !Yii::app()->user->isGuest),
                                array('label' => 'Mi cuenta ', 'url' => 'http://www.odalys.com/odalys/micuenta.php', 'visible' => !Yii::app()->user->isGuest, 'linkOptions' => array('target' => '_blank')),
                                array('label' => 'Iniciar sesión', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                                array('label' => 'Condiciones de la subasta', 'url' => 'http://odalys.com/odalys/condiciones_silenciosa.php', 'linkOptions' => array('target' => '_blank')),
                                array('label' => 'Volver a  odalys.com', 'url' => 'http://www.odalys.com', 'linkOptions' => array('target' => '_blank')),
                                array('label' => 'Cerrar sesión (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
                                //array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
                                //array('label'=>'Contact', 'url'=>array('/site/contact')),
                                // NOTA CAMBIAR A ************************** ADMIN ************************** CUANDO TERMINE DE TRABAJARSE
                                //array('label'=>'Puja asistida','url'=>array('site/pujaradmin'), 'visible'=>isset(Yii::app()->session['admin'])),

                            ),
                        ));

                    }



                    ?>
                </nav>

            </header>


            <hr style="clear:both">

            <div style="width: 100% !important;">
                <div class="alignleft" style="font-size: 13px; margin: 0 0 0 0;">
                    <w class="enbold">
                        <nombresubasta><?php

              /*              $criteria = new CDbCriteria;

                            $criteria->condition = 'fuesilenciosa=:fuesilenciosa';
                            $criteria->params = array(':fuesilenciosa'=>1);
                            $criteria->order = 'id DESC';

                            $ultimaSubastaSilenciosa = Subastas::model()->find($criteria);

                                                     // Pre Subasta
                            $criteria = new CDbCriteria;

                            $criteria->condition = 'ids=:ids';
                            $criteria->params = array(':ids'=>$ultimaSubastaSilenciosa->id);

                            $crono = Cronometro::model()->find($criteria);

                            $time = new DateTime($crono->fecha_finalizacion);
                            $actualTime = new DateTime("now");

                            $intervaloPresubasta = $actualTime->getTimestamp() - $time->getTimestamp();

                            // Verificando que se encuentra en la proxima hora al finalizar la subasta.
                            if( $intervaloPresubasta >=0 && $intervaloPresubasta <= 3600 )
                                echo 'Subasta'; else echo 'Pre Subasta';   */?> </nombresubasta>
                    </w>
                    <nombrecsubasta></nombrecsubasta>
                </div>

                <div class="alignright" style="margin: 0 0 0 0;">


                    <?php
                    $subasta = new Subastas();

                    $sub = Subastas::model()->find('silenciosa=1');

                    // Verificando que se encuentra en la proxima hora al finalizar la subasta.
                    if($subasta->enPresubasta()){
                        $fecha = $subasta->fechaPresubasta();
                    }
                    else {

                        $fecha = new DateTime((Cronometro::model()->find('ids=:ids', array(':ids' => $sub['id']))['fecha_finalizacion']));

                    }
                    //$fecha = new DateTime();


                    ?>
                    <!-- ESTO TIENE QUE IR EN EL HEADER CON POSICIÓN FIJADA(QUE SIEMPRE SE VEA)-->
                    <div id="odliczanie-b" class="" syle="margin: 0 0 0 0;"><b><?php echo $subasta->enPresubasta()?'Pre-Subasta: ':$sub!=null?'Subasta: ':'' ?><span
                                data-time="<?php echo $fecha->format('U'); ?>" class="cronometro"></span></b></div>
                </div>

            </div>
            <br>
            <hr style="clear:both">
        </div>
    </div>

</div>

<?php
if (Yii::app()->session['id_usuario']) {
    echo '<div class="container" style= "padding-top: 185px;"> ';
} else {
    echo '<div class="container" style= "padding-top: 180px;"> ';
}
?>

<div class="home-page main">
    <section class="grid-wrap">
        <header class="grid col-full">

            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links' => $this->breadcrumbs,
            )); ?>

            <!--
                        <a href="#" class="arrow fright"></a>-->
        </header>

        <div class="grid col-full mq2-col-full">


            <div style="width: 100% !important; padding-bottom:15px;">
                <div class="alignleft" style="font-size: 13px; margin: 0 0 0 0;">

                    <div id="filtro" class="" style="padding-top:10px"></div>
                </div>

                <div class="alignright" style="margin: 0 0 0 0;">
                    <div id="barraBusqueda" class="" style="width: 200px; margin-bottom: 0;"></div>

                </div>

            </div>
            <br>
            <br>

            <?php echo $content; ?>
        </div>

        <!--<div class="slider grid col-one-half mq2-col-full">
		   <div class="flexslider">
		     <div class="slides">
		       <div class="slide">
		           	<figure>
		                 <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/img2.jpg" alt="">
		                 <figcaption>
		                 	<div>
		                 	<h5>Caption 1</h5>
		                 	<p>Lorem ipsum dolor set amet, lorem, <a href="#">link text</a></p>
		                 	</div>
		                 </figcaption>
		             	</figure>
		           </div>

		           <div class="slide">
		               	<figure>
		                     <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/img.jpg" alt="">
		                     <figcaption>
		                     	<div>
		                     	<h5>Caption 2</h5>
		                     	<p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.</p>
		                     	</div>
		                     </figcaption>
		                 	</figure>
		               </div>
		            </div>
		   </div>
		 </div>-->

    </section>

    <!--<section class="services grid-wrap">
        <header class="grid col-full">
            <hr>
            <p class="fleft">Services</p>
            <a href="#" class="arrow fright">see more services</a>
        </header>

        <article class="grid col-one-third mq3-col-full">
            <aside>01</aside>
            <h5>Web design</h5>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi commodo, ipsum sed pharetra gravida, orci magna rhoncus neque, id pulvinar odio lorem non turpis. Nullam sit amet enim.</p>
        </article>

        <article class="grid col-one-third mq3-col-full">
            <aside>02</aside>
            <h5>Web development</h5>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi commodo, ipsum sed pharetra gravida, orci magna rhoncus neque, id pulvinar odio lorem non turpis. Nullam sit amet enim.</p>
        </article>

        <article class="grid col-one-third mq3-col-full">
            <aside>03</aside>
            <h5>Graphic design</h5>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi commodo, ipsum sed pharetra gravida, orci magna rhoncus neque, id pulvinar odio lorem non turpis. Nullam sit amet enim.</p>
        </article>
    </section>-->

    <!--<section class="works grid-wrap">
	<header class="grid col-full">
			<hr>
			<p class="fleft">Works</p>
			<a href="#" class="arrow fright">see more works</a>
		</header>

			<figure class="grid col-one-quarter mq2-col-one-half">
				<a href="#">
				<img src="<?php //echo Yii::app()->theme->baseUrl; ?>/img/img.jpg" alt="">
				<span class="zoom"></span>
				</a>
				<figcaption>
					<a href="#" class="arrow">Project page!</a>
					<p>Lorem ipsum dolor set amet</p>
				</figcaption>
			</figure>

			<figure class="grid col-one-quarter mq2-col-one-half">
				<a href="#">
				<img src="<?php //echo Yii::app()->theme->baseUrl; ?>/img/img.jpg" alt="">
				<span class="zoom"></span>
				</a>
				<figcaption>
					<a href="#" class="arrow">Project x</a>
					<p>Lorem ipsum dolor set amet</p>
				</figcaption>
			</figure>

			<figure class="grid col-one-quarter mq2-col-one-half">
				<a href="#">
				<img src="<?php //echo Yii::app()->theme->baseUrl; ?>/img/img.jpg" alt="">
				<span class="zoom"></span>
				</a>
				<figcaption>
					<a href="#" class="arrow">Project x</a>
					<p>Lorem ipsum dolor set amet</p>
				</figcaption>
			</figure>

			<figure class="grid col-one-quarter mq2-col-one-half">
				<a href="#">
				<img src="<?php //echo Yii::app()->theme->baseUrl; ?>/img/img.jpg" alt="">
				<span class="zoom"></span>
				</a>
				<figcaption>
					<a href="#" class="arrow">Project x</a>
					<p>Lorem ipsum dolor set amet</p>
				</figcaption>
			</figure>
	</section>
	</div> <!--main-->


    <div id="franja-subasta" class="alerta-subasta">
        <?php
        date_default_timezone_set('America/Caracas');
        // Windows
        //setlocale(LC_ALL,"esp_esp");
        //Linux
        setlocale(LC_ALL,"es_ES");
        $subasta = new Subastas();
        $fechaFinalizacion = $subasta->fechaPresubasta();

        if($subasta->subastaActiva() || $subasta->enPresubasta())
            echo ('La subasta en vivo se realizar&aacute; el '. ucfirst(strftime("%A",$fechaFinalizacion->getTimestamp())).' '.$fechaFinalizacion->format('d-m').' a las '. $fechaFinalizacion->format('h:i:s A')); ?>
    </div>


    <div class="divide-top" style="">
        <footer class="grid-wrap">
            <div class="up grid col-one-third" style="width:200px !important;">
                <a href="<?php echo Yii::app()->getHomeUrl(); ?>" class="logo fleft">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" width="15px"
                         alt="Casa de Subastas Odalys">
                </a>
            </div>

            <div class="grid col-one-third social">


                CARACAS - VENEZUELA<br>
                C. Comercial Concresa, Nivel PB. Locales 115 y 116,<br>
                Urb. Prados del Este, Caracas 1080, Venezuela<br>
                Telfs: +58 212 9795942, +58 212 9761773, <br>Fax: +58 212 9794068<br>
                odalys@odalys.com<br><br>
                MADRID - ESPAÑA<br>
                Orfila 5, 28010, Madrid, España<br>
                Telfs: +34 913194011, +34 913896809<br>
                galeria@odalys.com | info@odalys.es<br><br>
                MIAMI - USA<br>
                Odalys Galería de Arte<br>
                8300 Nw 74th terr Tamarac, Fl 33321. USA<br>
                Phone: +1 954 6819490<br>
                miami@odalys.com<br>


            </div>


            <nav class="grid col-one-third ">
                <?php /*$this->widget('zii.widgets.CMenu',array(
					'items'=>array(
						array('label'=>'Home', 'url'=>array('/site/index')),
						array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
						array('label'=>'Contact', 'url'=>array('/site/contact')),
						array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					),*/
                //));
                ?>
            </nav>
        </footer>
    </div>

</div>

<?php echo '</div>'; ?>

<!-- Javascript - jQuery -->
<script src="http://code.jquery.com/jquery.min.js"></script>
<script>window.jQuery || document.write('&lt;script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-1.7.2.min.js"> &lt;\/script>')</script>

<!--[if (gte IE 6)&(lte IE 8)]>
<script src="js/selectivizr.js"></script>
<![endif]-->

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.flexslider-min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/scripts.js"></script>
<!--<script src="<?php //echo Yii::app()->theme->baseUrl; ?>/js/kkcountdown.min.js"></script>-->

<!--<script src="<?php //echo Yii::app()->theme->baseUrl; ?>/js/jquery.lwtCountdown-1.0.js"></script>-->

<?php
//Lazy Load
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.lazyload.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('lazyload', '// LAZY LOAD
																					$( function () {
																						$("img.lazy").lazyload({
																												    threshold : 200,
																												    event : "mouseover",
																												    event : "scroll",
																												    //failure_limit : 300,
																												    container: $("#wrapper_imagens")  // Descomentar esta linea para cargar todas las imagenes del contenedor
																												});
																					});',
    CClientScript::POS_READY);

?>

<!-- Asynchronous Google Analytics snippet. Change UA-XXXXX-X to be your site's ID. -->
<!--<script>
  var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
  s.parentNode.insertBefore(g,s)}(document,'script'));
</script>-->
</body>
</html>
