<?php //Yii::app()->clientScript->registerCoreScript('jquery');
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

$idsub = $presubasta->imagen_s_id.'_'.uniqid();

?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'presubasta-form',
        'enableClientValidation'=>true,
        'enableAjaxValidation'=>false,
        'focus'=>array($presubasta,'opcion'),
    )); ?>

<!--    <p class="note">Fields with <span class="required">*</span> are required.</p>
-->
    <?php echo $form->errorSummary($presubasta); ?>
<!--

    <div class="row">
        <?php /*echo $form->labelEx($presubasta,'puja_maxima'); */?>
        <?php /*echo $form->checkBox($presubasta,'puja_maxima',array('size'=>60,'maxlength'=>255)); */?>
        <?php /*echo $form->error($presubasta,'puja_maxima'); */?>
    </div>

    <div class="row">
        <?php /*echo $form->labelEx($presubasta,'puja_telefonica'); */?>
        <?php /*echo $form->checkBox($presubasta,'puja_telefonica',array('size'=>60,'maxlength'=>255)); */?>
        <?php /*echo $form->error($presubasta,'puja_telefonica'); */?>
    </div>

    <div class="row">
        <?php /*echo $form->labelEx($presubasta,'asistir_subasta'); */?>
        <?php /*echo $form->checkBox($presubasta,'asistir_subasta',array('size'=>60,'maxlength'=>255)); */?>
        <?php /*echo $form->error($presubasta,'asistir_subasta'); */?>
    </div>

    <div class="row">
        <?php /*echo $form->labelEx($presubasta,'no_hacer_nada'); */?>
        <?php /*echo $form->checkBox($presubasta,'no_hacer_nada',array('size'=>60,'maxlength'=>255)); */?>
        <?php /*echo $form->error($presubasta,'no_hacer_nada'); */?>
    </div>



    <?php
       //echo $form->radioButtonList($presubasta,'opcion',array('0' => 'Dejar puja máxima', '1' => 'Dejar puja telefónica', '2' => 'Asistir a la subasta en vivo', '3' => 'No hacer nada'));

    ?>

-->
    <?php
    $select = array($presubasta->opcion);
    echo $form->dropDownList($presubasta, 'opcion',
        array('0' => 'Dejar puja máxima', '1' => 'Dejar puja telefónica', '2' => 'Asistir a la subasta en vivo', '3' => 'No hacer nada'),
        array(/*'empty' => '(Selecciona una opción)',*/'id'=>'seleccion_opcion', 'onchange' => 'seleccionPresubasta(this.value);'));
    ?>

    <div class="row" id="monto" style="display: all;">
        <?php echo $form->labelEx($presubasta,'monto'); ?>
        <?php echo $form->textField($presubasta,'monto',array('size'=>60,'maxlength'=>255,
            'oninput'=>'js: var precio = 0;  if($(this).val() != ""){
             precio = $(this).val();}
             else{ precio = 0(); }
             $("#'.$idsub.'").attr("value","Dejar Puja '.$subasta->moneda.' "+number_format(precio));
              ','id'=>'montoValor_'.$idsub)); ?>
        <?php echo $form->error($presubasta,'monto'); ?>
    </div>

    <div class="row">
        <?php echo $form->hiddenField($presubasta,'imagen_s_id',array(/*'value'=>$subasta->imagen*/)); ?>
    </div>

<script type="application/javascript">

    function seleccionPresubasta(opcion){
        if(opcion == 0)
            $("#monto").show();
        else {
            $("#<?php echo $idsub; ?>").attr("value","Dejar Puja");
            $("#montoValor_<?php echo $idsub ?>").val('');
            $("#monto").hide();
        }


    }

/*    $(document).ready(function(){


        $("#seleccion_opcion").change(function(){
            //console.log("Hola");
            var value = this.val();

            if(value == 0)
                $("#monto").show();
            else
                $("#monto").hide();

            alert("DDL value"+value);
            //statement
        });

    });*/

</script>
    <div class="row buttons">
        <?php //echo CHtml::submitButton($presubasta->isNewRecord ? 'Dejar Puja' : 'Dejar Puja'); ?>
        <?php

/*
        Yii::app()->clientScript->registerScript('seleccion_opcionnombre','
$(document).ready(function(){
                                            $("#seleccion_opcion").on("change",function(){
                                                var value = this.val();

                                                if(value == 0)
                                                 $("#monto").show();
                                                else
                                                 $("#monto").hide();

                                                alert("DDL value"+value);
                                                //statement
                                            });
});
																',
            CClientScript::POS_READY);*/


        echo CHtml::ajaxSubmitButton('Dejar Puja', CHtml::normalizeUrl(array('site/presubasta')),
            array('type'=>'POST',//'update'=>'#pujaModal',
                'dataType' => "json",
                //'data' => '{imagen_ss: "0"}',
                'error' =>'function(data){
													//alert("Error");
													//console.log(data);
													if(data["status"] == 200){
														$("#pujaMdal").html(data["responseText"]);
														//$("#pujaModal").attr("style","with:600px;");
													}
													else{
														alert(data["responseText"]);
													}
												}',
                'success' => 'function(data){
													json = data;
														if(data[\'id\']){
															alert(data["msg"]);
															if(data["sucess"]){
																c$("#pujaModal").dialog("close");

															}else
																$("#pujaModal").html(data["responseText"]);
														}else{
															$("#pujaModal").html(data);

														}

												}',
                'context'=>'js:this',

                'beforeSend' => 'function(xhr,settings){

										        }',
                'complete' => 'function(){

										            }',
            ),
            array('class'=>'btn','style'=>'width:300px;','id'=>$idsub));


        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->