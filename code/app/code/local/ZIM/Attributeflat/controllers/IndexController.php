<?php
class ZIM_Attributeflat_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{  
		$this->loadLayout(array('default'));
		$this->renderLayout(); 
	}

	public function setAttributeFlatTableAction(){
		$testModel = Mage::getModel('attributeflat/attributeflat');
		$testModel->setProductId(2241);
		$testModel->save();
	}

}
?>
