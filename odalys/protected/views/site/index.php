<?php //Yii::app()->clientScript->registerCoreScript('jquery');
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php
	$baseUrl = Yii::app()->request->baseUrl;

	Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/numberformat.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile($baseUrl . '/plugin/fancybox/source/jquery.fancybox.pack.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile($baseUrl . '/plugin/fancybox/lib/jquery.mousewheel-3.0.6.pack.js', CClientScript::POS_END);
	
	Yii::app()->clientScript->registerCssFile($baseUrl . '/plugin/fancybox/source/jquery.fancybox.css');

/*
	//Lazy load
	Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.lazyload.min.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScript('lazyload','// LAZY LOAD 
														$(function() {
															$("img.lazy").lazyload({
																					    threshold : 200
																					});
														});', 
														CClientScript::POS_READY);*/


	Yii::app()->clientScript->registerScriptFile($baseUrl . '/plugin/kkcountdown/js/kkcountdown.min.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScript('cronometro','$(document).ready(function(){
																	$("tiemporestante").html("Tiempo restante: ");
													                $(".cronometro").kkcountdown({
													                	dayText		: "día ",
													                	daysText 	: "días ",
													                    hoursText	: "h ",
													                    minutesText	: "m ",
													                    secondsText	: "s",
													                    displayZeroDays : false,
													                    callback	: terminarSubasta,
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
																function terminarSubasta(){
																	var url = "'.$this->createUrl('site/terminar').'";    
																	$(location).attr("href",url);
																}
																var precio;
													            ', 
														CClientScript::POS_READY);

	Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.quicksearch.js', CClientScript::POS_END);

	Yii::app()->clientScript->registerScript('quicksearch','$(document).ready(function(){

																//$(".tile:nth-child(3n+2)").css("margin-left","30px");
																//$(".tile:nth-child(3n+2)").css("margin-right","30px");
																var barinput = "js: $(window).trigger(\'scroll\');";
																$("div#barraBusqueda").empty().html(\'<input id="autorBusqueda" onkeyup="\'+barinput+\'" oninput="\'+barinput+\'" placeholder="Búsqueda por artista" style="padding:0.5em !important; width:100% !important;"></input>\');
																$("input#autorBusqueda").quicksearch("#wrapper_imagens #elementosImagens");
													        });
												            ', 
														CClientScript::POS_READY);

	//$subasta = Subastas::model()->find('silenciosa=1');
	Yii::app()->clientScript->registerScript('nombresubasta','$(document).ready(function(){
																$("nombresubasta").empty().html("'.$subasta->nombre.'");
																$("nombrecsubasta").empty().html("'.$subasta->nombrec.'");
													        });
												            ', 
														CClientScript::POS_READY);
	/*Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.filtertable.min.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.dataTables.min.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.mixitup.min.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScript('tablesorting','$(document).ready(function(){
																 $("#tabla_imagens").mixitup();
													            });
										
													            ', 
														CClientScript::POS_READY);*/
	Yii::app()->clientScript->registerScript('refresh','var time = new Date().getTime();
 
	     $(document.body).bind("mousemove keypress", function(e) {
        	 time = new Date().getTime();
     	});
 
	     function refresh() {
       	  if(new Date().getTime() - time >= 1800000)
       	      window.location.reload(true);
       	  else
             setTimeout(refresh, 300000);
     	}
 
	     setTimeout(refresh, 300000);', CClientScript::POS_READY);

	$fecha = new DateTime((Cronometro::model()->find('ids=:ids',array(':ids'=> Subastas::model()->find('silenciosa=1')['id']))['fecha_finalizacion']));
	//echo 'Fecha Finalización: '.$fecha->format('d-m-Y h:i:s');
/*$this->widget('ext.bcountdown.BCountdown', 
        array(
                //'title'=>'UNDER CONSTRUCTION',  // Title
                //'message'=>'Stay tuned for news and updates', // message for user
                'isDark'=>false,  // two skin dark and light , by default it light i.e isDark=false
                'year'=>$fecha->format('Y'),   
                'month'=>$fecha->format('m'),
                'day'=>$fecha->format('d'),
                'hour'=>$fecha->format('h'),
                'min'=>$fecha->format('i'),
                'sec'=>$fecha->format('s'),
            )
        );
*/
?>
<!--<ul>
    <li class="filter" data-filter="cualquiercosa">cualquiercosa</li>
</ul>-->
<style>
	/*#tabla_imagens .mix{
    opacity: 0;
    display: none;
}*/
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
		        'title'=>'Pujar',
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

		
	$refreshTime = 5000; 	// tiempo de refresco en milisegundos
    $ourscript = '  
	$( document ).ready(function() {
		
		
		//updateMyContent();
		setInterval( "updateMyContent();", '.$refreshTime.' );
		$(function() {
       		updateMyContent = function(){
               $.ajax({
							type: "POST",
							url: "'.$this->createUrl('site/buscar').'",
							dataType: "json",
							data: {

							},
							beforeSend: function () {
	               			},
							success : function(data){
								json = data;
							},
							error : function(XMLHttpRequest, textStatus, errorThrown) {
							
							},
							complete : function() { 
									json.forEach(function(entry){
											if(entry["paleta"])
												$("paleta_"+entry["id"]).empty().html(entry["paleta"]);
											
											if(entry["actual"])
											{
												$("cantidad_"+entry["id"]).empty().html(number_format(entry["actual"]));
												
												//Actualizando la modal
												$("#pujaModal actual_"+entry["id"]).empty().html(number_format(entry["actual"]));
											}

											if(entry["siguiente"])
											{
												$("siguientei_"+entry["id"]).empty().html(number_format(entry["siguiente"]));

												//Actualizando la modal
												$("#pujaModal siguiente_"+entry["id"]).empty().html(number_format(entry["siguiente"]));
												$("#pujaModal #precioboton_"+entry[\'id\']).val(entry["siguiente"]).change();
											}else
											{
												//$("pujasiguienteafterlink").empty();
											}

											if(entry["div"])
												$("w#"+entry["id"]+"a").empty().html(entry["div"]);

											if(entry["carrito"])
												$("div#carrito").empty().html(entry["carrito"]);


									});											 
							 }
				});
       		}
		}); 
	});';


	// Usuario activo que tiene paleta y codigo asignado en esta subasta
	$usuario_activo = Yii::app()->session['admin'] || (Yii::app()->session['id_usuario'] && Usuariospujas::model()->find('idsubasta=:idsubasta AND idusuario=:idusuario', array(':idsubasta'=>$subasta->id,':idusuario'=>Yii::app()->session['id_usuario'])));
	
	if ($usuario_activo)
    	Yii::app()->clientScript->registerScript('autorefresh',$ourscript,CClientScript::POS_READY);

?>
<?php

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

	$imprimir = $imprimir;

	if(Yii::app()->session['id_usuario']){
		$carrito = '<div id="carrito">';
		$mispujas = ImagenS::model()->findAll('ids=:ids AND id_usuario=:id_usuario', array(':ids'=>$subasta->id, ':id_usuario' => Yii::app()->session['id_usuario']));
		if($mispujas)
			foreach ($mispujas as $key => $puja) {
				$carrito .= '<div id="vsidebar"><img src="'.$imagenesDir.$puja->imagen.'"></img><br><span style="">
							'.$puja->solonombre.'</span><p>Actual: <moneda>'.$subasta->moneda.'</moneda> '.number_format($puja->actual).'</p></div>';
							//Actual: <moneda>'.$subasta->moneda.'</moneda> <cantidadd_'.$puja->id.'>'.number_format($puja->actual).'</cantidadd_'.$puja->id.'></span></div>';
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
?>


<p id="refreshData">
	<?php
		echo $imprimir;
	?>
</p>



