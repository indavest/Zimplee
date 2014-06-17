<?php
class ZIM_Home_TopperanswerpapersController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{  
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Topper Answers'));
		$this->renderLayout(); 
	}
    
}
?>
