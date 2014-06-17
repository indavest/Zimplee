<?php
class ZIM_Mylibrary_ReaderController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		if(Mage::getSingleton('customer/session')->isLoggedIn())
		{
			$customerData = Mage::getSingleton('customer/session')->getCustomer();
			$productId = $this->getRequest()->getParam('id');			
			$libraryCollection = Mage::getModel('mylibrary/mylibrary')->getCollection()
									->addFieldToFilter('customer_id', $customerData->getId())
									->addFieldToFilter('product_id', $productId);
			$this->loadLayout(array('default'));
			Mage::register('customer_id', $customerData->getId());
			if($libraryCollection->getSize() <= 0){
				Mage::register('isAllowed', false);
			}else{
				Mage::register('isAllowed', true);
			}

		}
		else
		{
			Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account'))->sendResponse();    
		}
		$this->renderLayout(); 
	}
	
	public function demoAction()
	{
		$this->loadLayout();
		Mage::register('customer_id', "prasad1");
		$this->renderLayout(); 
	}
	
}
?>
