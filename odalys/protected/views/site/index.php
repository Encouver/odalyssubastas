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


	Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.lazyload.min.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScript('lazyload','// LAZY LOAD 
														$(function() {
															$("img.lazy").lazyload({
																					    threshold : 200
																					});
														});', 
														CClientScript::POS_READY);


	Yii::app()->clientScript->registerScriptFile($baseUrl . '/plugin/kkcountdown/js/kkcountdown.min.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScript('cronometro','$(document).ready(function(){
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
											$("#imagen_"+entry["id"]).empty();
											if(entry["paleta"])
												$("#imagen_"+entry["id"]).html("Paleta : "+entry["paleta"]+"<br/>Precio: "+entry["actual"]);
											if(entry["actual"]){
												$("#imagen_"+entry["id"]).html("Precio: "+entry["actual"]);
												
												precio = entry["actual"];
												$("#pujaModal precio#"+entry["id"]).empty();
												$("#pujaModal precio#"+entry["id"]).html( "Precio actual: "+number_format(precio)
																				+"<BR>Puja siguiente: "+number_format(precio*1.1));
												$("#pujaModal #precio").attr("value",precio);
											}
											if(entry["div"]){
												$("w#"+entry["id"]+"a").empty();
												$("w#"+entry["id"]+"a").html(entry["div"]);
											}

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



