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
	height: 200px;
	margin-bottom: 30px;
    margin-left: 5px;
    margin-right: 5px;
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
																$("div#barraBusqueda").empty().html(\'<input id="autorBusqueda" onkeyup="\'+barinput+\'" oninput="\'+barinput+\'" placeholder="Busqueda por autor" style="padding:0.5em !important; width: 100% !important;"></input>\');
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
				$carrito .= '<div><img src="'.$imagenesDir.$puja->imagen.'"></img><br><span style="color: red;">
							'.$puja->solonombre.'</span><p>Actual: <moneda>'.$subasta->moneda.'</moneda> '.number_format($puja->actual).'</p></div>';
							//Actual: <moneda>'.$subasta->moneda.'</moneda> <cantidadd_'.$puja->id.'>'.number_format($puja->actual).'</cantidadd_'.$puja->id.'></span></div>';
			}
		else
			$carrito = 'No ha realizado ninguna puja';

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
