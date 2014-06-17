<?php
class ZIM_Mylibrary_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{  
        if(Mage::getSingleton('customer/session')->isLoggedIn())
        {
            $this->loadLayout(array('default'));
		}
        else
        {
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account'))->sendResponse();    
        }
		$this->renderLayout(); 
	}

}
?>
