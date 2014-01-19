<?php //Yii::app()->clientScript->registerCoreScript('jquery');
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'pujaModal',
		    'options'=>array(
		        'title'=>'Pujar',
		        'width'=>'auto',
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
		));

	    echo 'Modal dialog content here ';
	    
	$this->endWidget('zii.widgets.jui.CJuiDialog');
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
											$("#imagen_"+entry["id"]).empty();
											$("#imagen_"+entry["id"]).html("Paleta : "+entry["paleta"]+"<br/>Precio : "+entry["actual"]);
									});											 
							 }
				});
       		}
		}); 
	});';
    Yii::app()->clientScript->registerScript('autorefresh',$ourscript,CClientScript::POS_READY);

?>

<p id="refreshData">
	<?php
		echo $imprimir;
	?>
</p>



