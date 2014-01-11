<?php
/* @var $this UsuariosController */
/* @var $model Usuarios */

$this->breadcrumbs=array(
	'Usuarioses'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Usuarios', 'url'=>array('index')),
	array('label'=>'Create Usuarios', 'url'=>array('create')),
	array('label'=>'Update Usuarios', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Usuarios', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Usuarios', 'url'=>array('admin')),
);
?>

<h1>View Usuarios #<?php echo $model->id; ?></h1>

<?php $this->widget('application.extensions.LiveGridView.RefreshGridView', array(
    'id'=>'items-grid',
    'dataProvider'=>$model->search(),
       'updatingTime'=>6000, // 6 sec
    'filter'=>$model,
    'columns'=>array(
    	'id',
    	'nombre',
        array(
            'class'=>'CButtonColumn',
        ),
    ),
));


?>
