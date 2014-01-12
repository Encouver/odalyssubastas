<?php //Yii::app()->clientScript->registerCoreScript('jquery');
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php
    $ourscript = '  
	$( document ).ready(function() {
		//updateMyContent();
		setInterval( "updateMyContent();", 5000 );
		$(function() {
       		updateMyContent = function(){
               $.ajax({
							type: "POST",
							url: "http://localhost/odalyssubastas/odalys/index.php?r=site/buscar",
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
									var datos = json;
									//console.log(json);
									json.forEach(function(entry){
											$("#imagen_"+entry["id"]).empty();
											$("#imagen_"+entry["id"]).html("Paleta : "+entry["paleta"]+"<br/>Precio : "+entry["actual"]);
											
									});
									//$("#refreshData").empty();
									//$("#refreshData").append(json);
											 
							 }
				});
       		}
		}); 
	});';
    Yii::app()->clientScript->registerScript('autorefresh',$ourscript,CClientScript::POS_READY);
?>

<p id="refreshData"></p>



