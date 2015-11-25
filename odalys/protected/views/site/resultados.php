<?php //Yii::app()->clientScript->registerCoreScript('jquery');
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<style>
/* TILES */
#wrapper_imagens{
	position: relative;
	margin-top: 20px;
	width: 100%;
	margin: 0 auto;
	overflow: hidden;
}
.tile{
	width: 210px;
	height: 220px;
	margin-bottom: 30px;
    margin-left: 5px;
    margin-right: 5px;
	float: left;
	overflow: hidden;
}
</style>


<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'pujaModal',
	'options'=>array(
		'title'=>'Dejar puja',
		'width'=>'600px',
		'height'=>'auto',
		'autoOpen'=>false,
		'resizable'=>false,
		'modal'=>true,
		'overlay'=>array(
			'backgroundColor'=>'#000',
			'opacity'=>'0.5'
		),
		'buttons'=>array(
			//'Pujar'=>'js:function(){alert("OK");}',
			array('id'=> 'Cancel', 'text'=>'Cancelar','click'=>'js:function(){$(this).dialog("close");}'),
		),
	),
	'htmlOptions'=>array('style'=>'`//z-index: 4;'),
));

//echo 'Modal dialog content here ';

$this->endWidget('zii.widgets.jui.CJuiDialog');


?>

<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/plugin/kkcountdown/js/kkcountdown.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('cronometro-resultados','$(document).ready(function(){
																	$("tiemporestante").html("Tiempo restante: ");
													                $(".cronometro").kkcountdown({
													                	dayText		: "día ",
													                	daysText 	: "días ",
													                    hoursText	: "h ",
													                    minutesText	: "m ",
													                    secondsText	: "s",
													                    displayZeroDays : false,
													                    //callback	: terminarPreSubasta,
													                    oneDayClass	: "one-day"
													                });
													               /* $(".kkcount-down").kkcountdown({
													                	dayText		: " ",
													                	daysText 	: " ",
													                    hoursText	: ":",
													                    minutesText	: ":",
													                    secondsText	: "",
													                    displayZeroDays : false,
													                    callback	: function() {
													                    	$("odliczanie-a").css({"background-color":"#fff",color:"#333"});
													                    },
													                    addClass	: ""
													                });*/
													                $("#go").click(function() {
													                	var secs = $("#secs").val();
													                	$("#secs").parent("span").html("<strong>"+secs+"</strong>").addClass("red");
													                	$("#go").hide();
														                $(".kkcount-down-2")
														                	.attr("data-seconds", secs)
														                	.kkcountdown({
														                	dayText		: "d ",
														                	daysText 	: "dd ",
														                    hoursText	: ":",
														                    minutesText	: ":",
														                    secondsText	: "",
														                    displayZeroDays : false,
														                    textAfterCount: "BOOM!",
														                    warnSeconds : 10,
														                    warnClass   : "red",
														                });
													                });
													            });
																function terminarPreSubasta(){
																	var url = "'.$this->createUrl('site/terminar').'";
																	$(location).attr("href",url);
																}
																var precio;
													            ',
    CClientScript::POS_READY);

?>

<?php

	$baseUrl = Yii::app()->request->baseUrl;

    Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/numberformat.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.quicksearch.js', CClientScript::POS_END);

	Yii::app()->clientScript->registerScript('quicksearch','$(document).ready(function(){

																//$(".tile:nth-child(3n+2)").css("margin-left","30px");
																//$(".tile:nth-child(3n+2)").css("margin-right","30px");
																var barinput = "js: $(window).trigger(\'scroll\');";
																$("div#barraBusqueda").empty().html(\'<input id="autorBusqueda" onkeyup="\'+barinput+\'" oninput="\'+barinput+\'" placeholder="Buscar por autor" style="padding:0.5em !important; width: 100% !important;"/>\');
																$("input#autorBusqueda").quicksearch("#wrapper_imagens #elementosImagens");
													        });
												            ',
														CClientScript::POS_READY);


	Yii::app()->clientScript->registerScript('nombre_subasta','$(document).ready(function(){
																$("nombresubasta").empty().html("'.$subasta->nombre.'");
																$("nombrecsubasta").empty().html("'.$subasta->nombrec.'");
													        });
												            ',
														CClientScript::POS_READY);

	$filtro = "";

	$filtro = "<p>Ordenar por: ";
	$filtro .= CHtml::link("Lote","",array("onclick"=> 'js:$(\'.tablaresultado\').html($(\'div#elementosImagens\').sort(function(a, b) {return $(a).attr(\'data-numero\') - $(b).attr(\'data-numero\');	} ) )'));
	/*$filtro .= "   |   ";
	$filtro .= CHtml::link("Nombre","",array("onclick"=>" $('.tablaresultado').html($('div#elementosImagens').sort(function(a, b) {
																													if ($(a).attr('data-nombres') < $(b).attr('data-nombres')) return -1;
																													if ($(a).attr('data-nombres') > $(b).attr('data-nombres')) return 1;
																													return 0;
																											} )
																				)"));*/
	$filtro .= "   |   ";
	$filtro .= CHtml::link("Artista","",array("onclick"=> 'js:$(\'.tablaresultado\').html($(\'div#elementosImagens\').sort(function(a, b) {	if ($(a).attr(\'data-apellidos\') < $(b).attr(\'data-apellidos\')) return -1; if ($(a).attr(\'data-apellidos\') > $(b).attr(\'data-apellidos\')) return 1; return 0;} ) )'));
	$filtro .= "</p>";

		Yii::app()->clientScript->registerScript('filtro','$(document).ready(function(){
																$("div#filtro").empty().html(\''.$filtro.'\');
														        });
												            ',
														CClientScript::POS_READY);

	if(Yii::app()->session['id_usuario']){
		$carrito = '<div id="carrito">';
		$mispujas = ImagenS::model()->findAll('ids=:ids AND id_usuario=:id_usuario', array(':ids'=>$subasta->id, ':id_usuario' => Yii::app()->session['id_usuario']));
		if($mispujas)
			foreach ($mispujas as $key => $puja) {
				$carrito .= '<div id="vsidebar"><img style="padding-bottom:5px;" src="' . $imagenesDir . $puja->imagen . '"/><br><span style="">
							' . $puja->solonombre . '</span><br><span>Actual: <moneda>' . $subasta->moneda . '</moneda> ' . number_format($puja->actual) . '</span><br>';

				$existe = PreSubastas::model()->find('usuario_id=:usuario_id AND imagen_s_id=:imagen_s_id',array(':usuario_id'=>Yii::app()->session['id_usuario'],'imagen_s_id'=>$puja->id));

				if(!$existe && $subasta->enPresubasta()) {
					$etiqueta = 'Dejar puja';
					$pujarAjaxLink = CHtml::ajaxLink($etiqueta,
						$this->createUrl('site/presubasta'), array(
							//'onclick'=>'$("#pujaModal").dialog("open"); return false;',
							//'update'=>'#pujaModal'
							'type' => 'POST',
							'data' => array('imagen_s' => '0'),
							'context' => 'js:this',
							'beforeSend' => 'function(xhr,settings){
																					settings.data = encodeURIComponent(\'imagen_s\')
																					+ \'=\'
																					+ encodeURIComponent($(this).attr(\'id\'));
															}',
							'success' => 'function(r){$("#pujaModal").html(r).dialog("open"); return false;}'
						),
						array('id' => $puja->id, 'style' => 'color: #014F92;')
					);
					$carrito .= $pujarAjaxLink;
				}

				if($existe) {
					//$imprimir .= '<br> Estatus Presubasta: ';
					//$carrito .= '<br>';
					if($existe->puja_maxima)
						$etiqueta = 'Dejó puja máxima por: '.$subasta->moneda.' '.number_format($existe->monto).' (modificar)<hr>';

					if($existe->puja_telefonica)
						$etiqueta = 'Dejó puja telefónica (modificar)<hr>';

					if($existe->asistir_subasta)
						$etiqueta = 'Asistiré a subasta (modificar)<hr>';

					if($existe->no_hacer_nada)
						$etiqueta = 'Deseo quedarme con mi puja actual (modificar)<hr>';

					if ($subasta->enPresubasta())
					{
						//$etiqueta = 'Modificar puja dejada';
						$pujarAjaxLink = CHtml::ajaxLink($etiqueta,
							$this->createUrl('site/presubasta', array('actualizar' => true)), array(
								//'onclick'=>'$("#pujaModal").dialog("open"); return false;',
								//'update'=>'#pujaModal'
								'type' => 'POST',
								'data' => array('imagen_s' => '0'),
								'context' => 'js:this',
								'beforeSend' => 'function(xhr,settings){
											            						settings.data = encodeURIComponent(\'imagen_s\')
										          								+ \'=\'
										          								+ encodeURIComponent($(this).attr(\'id\'));
											            }',
								'success' => 'function(r){$("#pujaModal").html(r).dialog("open"); return false;}'
							),
							array('id' => $puja->id, 'style' => 'color: #014F92;')
						);
						$carrito .= $pujarAjaxLink;
					}
					else
						$carrito .= $etiqueta;

				}
							//Actual: <moneda>'.$subasta->moneda.'</moneda> <cantidadd_'.$puja->id.'>'.number_format($puja->actual).'</cantidadd_'.$puja->id.'></span></div>';
				$carrito .= '</div>';
			}
		else
			$carrito .= 'No ha realizado ninguna puja';

		$carrito .= '</div>';

		$this->beginWidget('application.extensions.sidebar.Sidebar', array('title' => 'Mis pujas', 'collapsed' => false, 'position'=>'right'));
		//here you display a cmenu or gridview or any other thing as per your needs.
		//or you can simply display contents form variable like below
		//$carrito = "Hola";
		echo $carrito;

		$this->endWidget();
	}

echo $resultados;

?>
<script>

	function Trim(strValue) {
		return strValue.replace(/^\s+|\s+$/g, '');
	}

	function getCookie(key) {
		var result = false;
		if(document.cookie) {
			var mycookieArray = document.cookie.split(';');
			for(i=0; i<mycookieArray.length; i++) {
				var mykeyValue = mycookieArray[i].split('=');
				if(Trim(mykeyValue[0]) == key) result = mykeyValue[1];
			}
		}
		return result;
	}

	function setCookie(key, value, hoursExpire) {
		var ablauf = new Date();
		var expireTime = ablauf.getTime() + (hoursExpire * 60 * 60 * 1000);
		ablauf.setTime(expireTime);
		document.cookie = key + "=" + value + "; expires=" + ablauf.toUTCString();
	}
    <?php                 /*    $actualTime = new DateTime("now");
                        $segundos =  $fecha->getTimestamp() - $actualTime->getTimestamp();*/
//var_dump($subasta->subastaActiva() || $subasta->enPresubasta()); die;
            if($subasta->subastaActiva() || $subasta->enPresubasta()) { ?>

                $(document).ready(function(){


                if(!getCookie("alertado"))
                {
                    // Configuramos para que en media hora se le indique el mensaje nuevamente.
                    setCookie("alertado", true, 0.25);
			alerta("Pre Subasta", "La Pre-Subasta ha finalizado, por favor seleccione una opción para cada una de las piezas que tiene adjudicadas hasta el momento: <br><br> 1. Dejar puja máxima: que va a ser realizada por nosotros como una puja en ausencia durante el acto de Subasta en vivo. <br><br> 2. Dejar puja telefónica: nos comunicáremos con Ud. el día del acto de Subasta en vivo en el momento que sea subastado su lote. <br> Importante: de no lograr comunicarnos con Ud. durante la subasta, su última puja de la presubasta será tomada como su última oferta en el lote. <br><br> 3. Asistiré al acto de Subasta en vivo: en este caso su última puja de la presubasta va a ser tomada como su última oferta en el lote, es decir, el lote será subastado en la sala desde ese monto. <br><br> 4. Quedarme con mi puja actual: en este caso su última puja de la presubasta va a ser tomada como su última oferta en el lote, es decir, el lote será subastado en la sala desde ese monto.", 
				"warning", "De acuerdo");
                
                }
            });

    <?php } ?>

</script>
<?php

/* 	$cookie = new CHttpCookie('alertado', true);
    $cookie->expire = 3600;
    Yii::app()->request->cookies['alertado'] = $cookie;*/
//$subasta->fechaPresubasta();
?>
<!--

<div id="franja-subasta" class="alerta-subasta">
	<?php
/*	date_default_timezone_set('America/Caracas');
	// Windows
	//setlocale(LC_ALL,"esp_esp");
	//Linux
	setlocale(LC_ALL,"es_ES");
	$fechaFinalizacion = new DateTime($crono->fecha_finalizacion);
	$fechaFinalizacion->add(new DateInterval('PT1H'));

	//if($subasta->enPresubasta())
		echo ('La subasta en vivo se realizar&aacute; el '. ucfirst(strftime("%A",$fechaFinalizacion->getTimestamp())).' '.$fechaFinalizacion->format('d-m').' a las '. $fechaFinalizacion->format('h:i:s A')); */?>
</div>
-->