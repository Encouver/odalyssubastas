	<?php //Yii::app()->clientScript->registerCoreScript('jquery');
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php

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

<script type="text/javascript">
$(document).ready(function(){
                $(".kkcount-down-1").kkcountdown({
                	dayText		: 'día ',
                	daysText 	: 'días ',
                    hoursText	: 'h ',
                    minutesText	: 'm ',
                    secondsText	: 's',
                    displayZeroDays : false,
                    callback	: test,
                    oneDayClass	: 'one-day'
                });
               /* $(".kkcount-down").kkcountdown({
                	dayText		: ' ',
                	daysText 	: ' ',
                    hoursText	: ':',
                    minutesText	: ':',
                    secondsText	: '',
                    displayZeroDays : false,
                    callback	: function() {
                    	$("odliczanie-a").css({'background-color':'#fff',color:'#333'});
                    },
                    addClass	: ''
                });*/
                $("#go").click(function() {
                	var secs = $("#secs").val();
                	$("#secs").parent("span").html("<strong>"+secs+"</strong>").addClass('red');
                	$("#go").hide();
	                $(".kkcount-down-2")
	                	.attr('data-seconds', secs)
	                	.kkcountdown({
	                	dayText		: 'd ',
	                	daysText 	: 'dd ',
	                    hoursText	: ':',
	                    minutesText	: ':',
	                    secondsText	: '',
	                    displayZeroDays : false,
	                    textAfterCount: 'BOOM!',
	                    warnSeconds : 10,
	                    warnClass   : 'red',
	                });
                });
            });
            function test(){
            	console.log('KONIEC');
            }
</script>

<div id="odliczanie-b"><b><span data-time="1420066800" class="kkcount-down-1">kokokokoko</span></b></div>


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
	$baseUrl = Yii::app()->request->baseUrl;
	Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.lazyload.min.js', CClientScript::POS_END);
	/*Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/fancybox/jquery.fancybox-1.3.4.pack.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/fancybox/jquery.easing-1.3.pack.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/fancybox/jquery.mousewheel-3.0.4.pack.js', CClientScript::POS_END);
	
	Yii::app()->clientScript->registerCssFile($baseUrl . '/js/fancybox/jquery.fancybox-1.3.4.css');*/



	$refreshTime = 5000; 	// tiempo de refresco en milisegundos
    $ourscript = '  
	$( document ).ready(function() {
		// LAZY LOAD
		$(function() {
    		$("img.lazy").lazyload({
									    threshold : 200
									});
		});

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



