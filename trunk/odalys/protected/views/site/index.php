<?php //Yii::app()->clientScript->registerCoreScript('jquery');
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php
    $ourscript = '  
	$( document ).ready(function() {
		//updateMyContent();
		setInterval( "updateMyContent();", 1000 );
		$(function() {
       		updateMyContent = function(){
               $.ajax({
							type: "POST",
							url: "http://localhost/odalyssubastas/odalys/index.php?r=site/buscar",
							dataType: "html",
							data: {
								//id : $("#Numerosvendidos_idsorteo" ).val(),							
							},
							beforeSend: function () {
	               			},
							success : function(data){
								json = data;

							},
							error : function(XMLHttpRequest, textStatus, errorThrown) {
							
							},
							complete : function() { 
									$("#refreshData").empty();
									$("#refreshData").append(json);
											 
							 }
				});
       		}
		}); 
	});';
    Yii::app()->clientScript->registerScript('autorefresh',$ourscript,CClientScript::POS_READY);
?>

<p id="refreshData"></p>



