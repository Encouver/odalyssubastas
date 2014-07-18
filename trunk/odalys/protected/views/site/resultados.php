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

	$filtro = "<p>Ordenar por: </p>"; 
	$filtro .= CHtml::link("Lote","",array("onclick"=>" $('.tablaresultado').html($('div#elementosImagens').sort(function(a, b) {
																							    					return $(a).attr('data-numero') - $(b).attr('data-numero');
																										} ) 
																				)"));
	/*$filtro .= "   |   ";
	$filtro .= CHtml::link("Nombre","",array("onclick"=>" $('.tablaresultado').html($('div#elementosImagens').sort(function(a, b) {																												
																													if ($(a).attr('data-nombres') < $(b).attr('data-nombres')) return -1;
																													if ($(a).attr('data-nombres') > $(b).attr('data-nombres')) return 1;
																													return 0;
																											} ) 
																				)"));*/
	$filtro .= "   |   ";
	$filtro .= CHtml::link("Artista","",array("onclick"=>" $('.tablaresultado').html($('div#elementosImagens').sort(function(a, b) {
																													if ($(a).attr('data-apellidos') < $(b).attr('data-apellidos')) return -1;
																													if ($(a).attr('data-apellidos') > $(b).attr('data-apellidos')) return 1;
																													return 0;
																											} ) 
																				)"));

	if(Yii::app()->session['id_usuario']){
		$carrito = '';
		$mispujas = ImagenS::model()->findAll('ids=:ids AND id_usuario=:id_usuario', array(':ids'=>$subasta->id, ':id_usuario' => Yii::app()->session['id_usuario']));
		if($mispujas)
			foreach ($mispujas as $key => $puja) {
				$carrito .= '<div><img src="'.$imagenesDir.$puja->imagen.'"></img><br><span style="color: red;">
							'.$puja->solonombre.'</span></div>';
							//Actual: <moneda>'.$subasta->moneda.'</moneda> <cantidadd_'.$puja->id.'>'.number_format($puja->actual).'</cantidadd_'.$puja->id.'></span></div>';
			}
		else
			$carrito = 'No posee obras';

		$this->beginWidget('application.extensions.sidebar.Sidebar', array('title' => 'Mis pujas', 'collapsed' => false, 'position'=>'right'));
		//here you display a cmenu or gridview or any other thing as per your needs.
		//or you can simply display contents form variable like below
		//$carrito = "Hola";
		echo $carrito;
		
		$this->endWidget();
	}

echo $filtro.$resultados;

?>
