<?php

/**
 * dynamic sidebar slider on the either side of the browser
 *
 * I refered Rightsidebar extention from yiiframework.com
 * 
 * @author Ramamoorthy <ramamoorthy071@gmail.com>
 * @version 1.0
 * @license BSD
 */
class Sidebar extends CWidget {
    public $title = 'Sidebar';
	public $position = 'left';
   
    public $collapsed = false;

    public function init() {
	$assetFolder = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
    $publishedAssetsPath = Yii::app()->assetManager->publish($assetFolder);

	if($this->position == 'left')
	{
        Yii::app()->clientScript->registerCssFile($publishedAssetsPath . '/css/leftsidebar.css');
        Yii::app()->clientScript->registerScriptFile($publishedAssetsPath . '/js/leftsidebar.js',CClientScript::POS_END);

        $js = $this->collapsed ?
                'left_menu.setStartStatus(false);' :
                'left_menu.setStartStatus(true);';
        Yii::app()->clientScript->registerScript('leftMenu', $js, CClientScript::POS_LOAD);

        echo '<div class="left_menu" id="leftmenu" style="width: 180px;">';
        
       echo '<div class="title">' ;
		echo  CHtml::encode($this->title);
        echo CHtml::ajaxLink('&gt;&gt;', '', false, array('id' => 'toggle_left_menu'));
        echo '</div>';
	}
	else{
		Yii::app()->clientScript->registerCssFile($publishedAssetsPath . '/css/rightsidebar.css');
        Yii::app()->clientScript->registerScriptFile($publishedAssetsPath . '/js/rightsidebar.js',CClientScript::POS_END);

        $js = $this->collapsed ?
                'right_menu.setStartStatus(false);' :
                'right_menu.setStartStatus(true);';
        Yii::app()->clientScript->registerScript('rightMenu', $js, CClientScript::POS_LOAD);

        echo '<div class="right_menu" id="rightmenu" style="width: 180px;">';
        
       echo '<div class="title">' ;
		echo  CHtml::encode($this->title);
        echo CHtml::ajaxLink('&gt;&gt;', '', false, array('id' => 'toggle_right_menu'));
        echo '</div>';
	}

        parent::init();
    }

    public function run() {
        parent::run();

        echo '</div>';
    }
}
?>
