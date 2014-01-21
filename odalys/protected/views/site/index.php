<?php //Yii::app()->clientScript->registerCoreScript('jquery');
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php

	$fecha = new DateTime((Cronometro::model()->find('ids=:ids',array(':ids'=> Subastas::model()->find('silenciosa=1')['id']))['fecha_finalizacion']));
	//echo 'Fecha Finalización: '.$fecha->format('d-m-Y h:i:s');
$this->widget('ext.bcountdown.BCountdown', 
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
											$("#imagen_"+entry["id"]).empty();
											if(entry["paleta"])
												$("#imagen_"+entry["id"]).html("Paleta : "+entry["paleta"]+"<br/>Precio : "+entry["actual"]);
											else
												$("#imagen_"+entry["id"]).html("Precio : "+entry["actual"]);
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



