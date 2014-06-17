<?php
class ZIM_Mylibrary_ReaderController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{  
	    $product_id = $this->getRequest()->getParam('id');
        $product = Mage::getModel('catalog/product')->load($product_id);
        Mage::register('docspadid', $product->getData('docspad_id'));
        $session_id = json_decode(file_get_contents(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."viewer/server/v1/session.php?docId=".$product->getData('docspad_id')))->sessionId;
        $this->getResponse()->setRedirect(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."viewer?id=".$session_id);
        
	}

}
?>
