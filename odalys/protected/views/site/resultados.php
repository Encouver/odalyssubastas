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
	width: 110px;
	height: 200px;
	margin-bottom: 30px;
    margin-left: 20px;
    margin-right: 20px;
	float: left;
	overflow: hidden;
}
</style> 



<?php

	$baseUrl = Yii::app()->request->baseUrl;

	Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.quicksearch.js', CClientScript::POS_END);

	Yii::app()->clientScript->registerScript('quicksearch','$(document).ready(function(){

																//$(".tile:nth-child(3n+2)").css("margin-left","30px");
																//$(".tile:nth-child(3n+2)").css("margin-right","30px");
																var barinput = "js: $(window).trigger(\'scroll\');";
																$("span#barraBusqueda").empty().html(\'<input id="autorBusqueda" onkeyup="\'+barinput+\'" oninput="\'+barinput+\'" placeholder="Busqueda por autor" style="padding:0.5em !important; width:40% !important"></input>\');
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

	$filtro = "<p>Filtrar por: </p>"; 
	$filtro .= CHtml::link("Número","",array("onclick"=>" $('.tablaresultado').html($('div#elementosImagens').sort(function(a, b) {
																							    					return $(a).attr('data-numero') - $(b).attr('data-numero');
																										} ) 
																				)"));
	$filtro .= "   |   ";
	$filtro .= CHtml::link("Nombre","",array("onclick"=>" $('.tablaresultado').html($('div#elementosImagens').sort(function(a, b) {																												
																													if ($(a).attr('data-nombres') < $(b).attr('data-nombres')) return -1;
																													if ($(a).attr('data-nombres') > $(b).attr('data-nombres')) return 1;
																													return 0;
																											} ) 
																				)"));
	$filtro .= "   |   ";
	$filtro .= CHtml::link("Apellido","",array("onclick"=>" $('.tablaresultado').html($('div#elementosImagens').sort(function(a, b) {
																													if ($(a).attr('data-apellidos') < $(b).attr('data-apellidos')) return -1;
																													if ($(a).attr('data-apellidos') > $(b).attr('data-apellidos')) return 1;
																													return 0;
																											} ) 
																				)"));

echo $filtro.$resultados;

?>
