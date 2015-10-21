<?php
/**
 * BCountdown class file.
 *
 * PHP Version 5.1
 * 
 * @package  Widget
 * @author   FBurhan <sefburhan@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT
 * @link     http://www.yiiframework.com/user/62626/
 */
 
class BCountdown extends CWidget{
        
     
         /**
	 * @var array the options for  BCountdown Widget
	 */
        public $options = array();
         /**
	 * @var string the title of the Countdown. if NOT assigned , the default value("UNDER CONSTRUCTION") will be shown.
	 */
	public $title; 
         /**
	 * @var string the message of the Countdown as to show the reason for site down. if NOT assigned , the default value("Stay tuned for news and updates") will be shown.
	 */
	public $message;      
         /**
	 * @var string the isDark of the Countdown . the default is Light Gray.
	 */
        public $isDark=false;   
         
       // Timestamp
         /**
	 * @var string the year of the Countdown . if NOT assigned ,the default is 0.
	 */
        public $year='0';
        /**
	 * @var string the month of the Countdown . if NOT assigned ,the default is 0.
	 */
        public $month='0';
        /**
	 * @var string the day of the Countdown . if NOT assigned ,the default is 0.
	 */
        public $day='0';
        /**
	 * @var string the hour of the Countdown . if NOT assigned ,the default is 0.
	 */
        public $hour='0';
        /**
	 * @var string the min of the Countdown . if NOT assigned ,the default is 0.
	 */
        public $min='0';
        /**
	 * @var string the sec of the Countdown . if NOT assigned ,the default is 0.
	 */
        public $sec='0';
         /**
	 * @var string the cssFile of the Countdown for Css file.
	 */
      
        public $cssFile;
        /**
	 * @var string the jsFile of the Countdown for Javascript file.
	 */
        
        public $jsFile;
        
        public function init() {
            // Put togehther options for plugin
            $path = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.bcountdown.src', -1, false));
            
            $this->jsFile = $path . '/js/jquery.lwtCountdown-1.0.js';
            if($this->isDark==false){
            $this->cssFile = $path . '/style/main.css';
            }else{
             $this->cssFile = $path . '/style/dark.css'; 
            }
            $cs = Yii::app()->clientScript;
            //$cs = new CClientScript;
            $cs->registerScriptFile($this->jsFile, CClientScript::POS_END);
            $cs->registerCssFile($this->cssFile);
           
			$script = '
       jQuery(document).ready(function() {
				$("#countdown_dashboard").countDown({
					targetDate: {
                    "year":"'.$this->year.'",
                    "month":"'.$this->month.'",
					"day":"'.$this->day.'",
					"hour":"'.$this->hour.'",
					"min":"'.$this->min.'",
					"sec":"'.$this->sec.'"
					}
				});
			 });'; 
            $cs->registerScript('countdown_dashboard', $script);
        }
        
    
         /**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
        public function run() {
		 
		if($this->title == ""){
			//$this->title = "UNDER CONSTRUCTION";
		}
		
		if($this->message == ""){
			//$this->message = "Stay tuned for news and updates";
		}
		 
            echo '
                <div id="contenido">
					<h1>'.$this->title.'</h1>
					<h2 class="subtitle">'.$this->message.'</h2>

				                    <div id="countdown_dashboard">
						<div class="dash weeks_dash">
							<span class="dash_title">Semanas</span>
							<div class="digit">0</div>
							<div class="digit">0</div>
						</div>

						<div class="dash days_dash">
							<span class="dash_title">Días</span>
							<div class="digit">0</div>
							<div class="digit">0</div>
						</div>

						<div class="dash hours_dash">
							<span class="dash_title">Horas</span>
							<div class="digit">0</div>
							<div class="digit">0</div>
						</div>

						<div class="dash minutes_dash">
							<span class="dash_title">Minutos</span>
							<div class="digit">0</div>
							<div class="digit">0</div>
						</div>

						<div class="dash seconds_dash">
							<span class="dash_title">Segundos</span>
							<div class="digit">0</div>
							<div class="digit">0</div>
						</div>
					</div> 
				</div>                
				';
        }
    
}

?>
