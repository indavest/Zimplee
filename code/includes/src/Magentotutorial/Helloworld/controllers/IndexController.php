<?php 
class Magentotutorial_Helloworld_IndexController extends Mage_Core_Controller_Front_Action{
	public function indexAction() {
		//echo'Hello!!';
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function  byeAction(){
		echo 'Good BYE!';
	}
	public function paramsAction(){
		echo '<dl>';
		foreach ($this->getRequest()->getParams() as $key=>$value){
			echo '<dt><strong>Param:</strong>'.$key.'</dt>';
			echo '<dt><strong>Value:</strong>'.$value.'</dt>';
		}
		echo '</dl>';
	}
}

