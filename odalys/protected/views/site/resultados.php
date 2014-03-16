<?php //Yii::app()->clientScript->registerCoreScript('jquery');
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

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

																$("input#autorBusqueda").quicksearch("#wrapper_imagens #elementosImagens");
													        });
												            ', 
														CClientScript::POS_READY);

echo $resultados;
//print_r($resultados);
?>
