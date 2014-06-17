<?php
class ZIM_Home_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{  
		$this->loadLayout(array('default'));
		$this->renderLayout(); 
	}
    
    public function topperAnswerPapersAction()
    {
        $this->loadLayout();
        //$this->getLayout()->getBlock('topanswerpapers');
        $this->renderLayout();
    }
}
?>
