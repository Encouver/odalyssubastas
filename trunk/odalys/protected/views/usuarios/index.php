<?php
/* @var $this UsuariosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Usuarioses',
);

$this->menu=array(
	array('label'=>'Create Usuarios', 'url'=>array('create')),
	array('label'=>'Manage Usuarios', 'url'=>array('admin')),
);

$this->widget('ext.bcountdown.BCountdown', 
        array(
                //'title'=>'UNDER CONSTRUCTION',  // Title
                //'message'=>'Stay tuned for news and updates', // message for user
                'isDark'=>false,  // two skin dark and light , by default it light i.e isDark=false
                'year'=>'2014',   
                'month'=>'1',
                'day'=>'26',
                'hour'=>'0',
                'min'=>'0',
                'sec'=>'5',
            )
        );


?>

<h1>Usuarioses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
