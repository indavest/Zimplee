<?php
class ZIM_Home_QuestionpapersController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{  
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Question Papers'));
        $this->renderLayout(); 
	}
    
    public function getAttributesAction(){
        $univerityValue = $this->getRequest()->getParam('university');
        $degreeValue = $this->getRequest()->getParam('degree');
        $semesterValue = $this->getRequest()->getParam('degree_semester');
        
        $result = array();
        $degreeList = array();
        $semesterList = array();
        $subjectList = array();
        $result['success'] = true;
        $result['data'] = array();
        $category = Mage::getModel('catalog/category')->load(7);
        $products = Mage::getModel('catalog/product')
                ->getCollection()
                ->addCategoryFilter($category)
                ->addAttributeToSelect('*');
        if($univerityValue != null){
            $products->addFieldToFilter('university', array('eq' => $univerityValue));
        }
        if($degreeValue != null){
            $products->addFieldToFilter('degree', array('eq' => $degreeValue));
        }        
        if($semesterValue != null){
            $products->addFieldToFilter('degree_semester', array('eq' => $semesterValue));
        }
        foreach($products as $loadedProduct){
            array_push($semesterList,$loadedProduct->getData('degree_semester'));    
            array_push($degreeList,$loadedProduct->getData('degree'));    
            array_push($subjectList,$loadedProduct->getData('subject'));    
        }
        array_push($result['data'],$semesterList,$degreeList,$subjectList);
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}
?>
