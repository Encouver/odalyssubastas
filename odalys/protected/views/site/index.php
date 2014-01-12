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
$.ajax({
type: "GET",
url: "buscar",
dataType: "html",
data: {
id : $("#Numerosvendidos_idsorteo" ).val(),	
},
beforeSend: function () {
},
success : function(data){
json = data;

},
error : function(XMLHttpRequest, textStatus, errorThrown) {

},
complete : function() { 
$("#busqueda").html(json);

}
});

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Congratulations! You have successfully created your Yii application.</p>

<p id="refreshData">You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>



